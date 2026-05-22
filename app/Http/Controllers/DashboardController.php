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
        if ($repoResponse->successful()) {
            $files = collect($repoResponse->json())->pluck('name')->toArray();
        }

        $metaResponse = Http::withToken($user->github_token)
            ->get("https://api.github.com/repos/{$owner}/{$repo}");
        
        $meta = $metaResponse->json();

        $readmeContent = $gemini->generateReadme(
            $meta['name'],
            $meta['description'] ?? 'A professional web project.',
            $meta['language'] ?? 'Unknown',
            $files
        );

        return view('preview', [
            'content' => $readmeContent,
            'repo' => $repo,
            'owner' => $owner
        ]);
    }
}
