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
    public function index()
    {
        $user = Auth::user();

        $response = Http::withToken($user->github_token)
            ->get('https://api.github.com/user/repos', [
                'sort' => 'updated',       
                'per_page' => 12,          
                'affiliation' => 'owner' 
            ]);

        $repos = $response->successful() ? $response->json() : [];
        $enabledWebhooks = Webhook::where('user_id', $user->id)->pluck('repo_full_name')->toArray();

        return view('dashboard', compact('repos', 'enabledWebhooks'));
    }

    public function generate($owner, $repo, GeminiService $gemini)
    {
        $user = auth()->user();

        $repoResponse = Http::withToken($user->github_token)
            ->get("https://api.github.com/repos/{$owner}/{$repo}/contents");
        
        $files = [];
        $fileContents = [];
        $keyFiles = ['package.json', 'composer.json', 'Dockerfile', 'docker-compose.yml', 'requirements.txt', 'pom.xml', 'go.mod'];

        if ($repoResponse->successful()) {
            $items = $repoResponse->json();
            foreach ($items as $item) {
                $files[] = $item['name'];
                
                // Fetch deep context for key files
                if (in_array($item['name'], $keyFiles) && $item['type'] === 'file') {
                    $contentResponse = \Illuminate\Support\Facades\Http::withToken($user->github_token)
                        ->withHeaders(['Accept' => 'application/vnd.github.v3.raw'])
                        ->get("https://api.github.com/repos/{$owner}/{$repo}/contents/{$item['name']}");
                        
                    if ($contentResponse->successful()) {
                        // Limit to 1500 chars to save tokens and prevent huge prompts
                        $fileContents[$item['name']] = \Illuminate\Support\Str::limit($contentResponse->body(), 1500);
                    }
                }
            }
        }

        $metaResponse = Http::withToken($user->github_token)
            ->get("https://api.github.com/repos/{$owner}/{$repo}");
        
        $meta = $metaResponse->json();

        $readmeContent = $gemini->generateReadme(
            $meta['name'],
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
