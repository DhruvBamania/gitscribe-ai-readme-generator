<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Services\GeminiService;

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

        return view('dashboard', compact('repos'));
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
}
