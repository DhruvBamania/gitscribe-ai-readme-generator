<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Services\GeminiService;
use App\Services\GitHubService;
use App\Models\Webhook;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    //
    public function index(GitHubService $githubService)
    {
        $user = Auth::user();

        try {
            $repos = \Illuminate\Support\Facades\Cache::remember("user_{$user->id}_repos", 300, function () use ($githubService, $user) {
                return $githubService->getUserRepos($user->github_token);
            });
        } catch (\Exception $e) {
            $repos = [];
            \Illuminate\Support\Facades\Session::flash('error', $e->getMessage());
        }

        $enabledWebhooks = Webhook::where('user_id', $user->id)->pluck('repo_full_name')->toArray();

        return view('dashboard', compact('repos', 'enabledWebhooks'));
    }

    public function generate($owner, $repo, GeminiService $gemini, GitHubService $githubService)
    {
        $user = auth()->user();

        try {
            $meta = $githubService->getRepoMeta($user->github_token, $owner, $repo);
            $repoName = $meta['name'] ?? $repo;
            $defaultBranch = $meta['default_branch'] ?? 'main';

            // 1. Root Level Context
            $items = $githubService->getRepoContents($user->github_token, $owner, $repo);
            $files = [];
            $fileContents = [];
            $keyFiles = ['package.json', 'composer.json', 'Dockerfile', 'docker-compose.yml', 'requirements.txt', 'pom.xml', 'go.mod'];

            foreach ($items as $item) {
                $files[] = $item['name'];
                
                // Fetch standard manifest files
                if (in_array($item['name'], $keyFiles) && $item['type'] === 'file') {
                    $content = $githubService->getFileContent($user->github_token, $owner, $repo, $item['name']);
                    if ($content) {
                        $fileContents[$item['name']] = \Illuminate\Support\Str::limit($content, 1500);
                    }
                }
            }

            // 2. Deep Codebase Explorer (AI-driven)
            $tree = $githubService->getRepoTree($user->github_token, $owner, $repo, $defaultBranch);
            
            // Limit tree to 400 files to prevent exceeding Gemini's prompt limits
            $limitedTree = array_map(function($node) { return $node['path']; }, array_slice($tree, 0, 400));
            
            $deepFiles = $gemini->pickFilesToExplore($repoName, json_encode($limitedTree));

            foreach ($deepFiles as $deepFile) {
                // Fetch each AI-selected file
                $content = $githubService->getFileContent($user->github_token, $owner, $repo, $deepFile);
                if ($content) {
                    $fileContents[$deepFile] = \Illuminate\Support\Str::limit($content, 1500);
                }
            }

            // 3. Final README Generation
            $readmeContent = $gemini->generateReadme(
                $repoName,
                $meta['description'] ?? 'A professional web project.',
                $meta['language'] ?? 'Unknown',
                $files,
                $fileContents
            );

            return view('preview', [
                'content' => $readmeContent,
                'repo' => $repo,
                'owner' => $owner
            ]);

        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', $e->getMessage());
        }
    }

    public function toggleWebhook(Request $request, GitHubService $githubService)
    {
        $user = auth()->user();
        $owner = $request->input('owner');
        $repo = $request->input('repo');
        $repoFullName = "{$owner}/{$repo}";
        
        $existingWebhook = Webhook::where('user_id', $user->id)->where('repo_full_name', $repoFullName)->first();

        if ($existingWebhook) {
            // Disable (Delete)
            if ($existingWebhook->github_webhook_id) {
                $githubService->deleteWebhook($user->github_token, $owner, $repo, $existingWebhook->github_webhook_id);
            }
            $existingWebhook->delete();
            return back()->with('success', "Auto-updates disabled for {$repoFullName}.");
        } else {
            // Enable (Create)
            $secret = Str::random(32);
            $webhookUrl = url('/api/webhooks/github'); // Using full URL
            
            $response = $githubService->createWebhook($user->github_token, $owner, $repo, $webhookUrl, $secret);
            
            if ($response->successful()) {
                Webhook::create([
                    'user_id' => $user->id,
                    'repo_full_name' => $repoFullName,
                    'github_webhook_id' => $response->json()['id'],
                    'secret' => $secret
                ]);
                return back()->with('success', "Auto-updates enabled for {$repoFullName}!");
            }
            
            return back()->with('error', "Failed to enable auto-updates. Ensure GitScribe has Admin access to the repository.");
        }
    }
}
