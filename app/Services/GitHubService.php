<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GitHubService
{
    /**
     * Create a webhook for the given repository.
     */
    public function createWebhook($token, $owner, $repo, $webhookUrl, $secret)
    {
        $baseUrl = "https://api.github.com/repos/{$owner}/{$repo}";
        
        $response = Http::withToken($token)->post("{$baseUrl}/hooks", [
            'name' => 'web',
            'active' => true,
            'events' => ['push'],
            'config' => [
                'url' => $webhookUrl,
                'content_type' => 'json',
                'secret' => $secret,
                'insecure_ssl' => '0'
            ]
        ]);

        return $response;
    }

    /**
     * Delete a webhook.
     */
    public function deleteWebhook($token, $owner, $repo, $hookId)
    {
        $baseUrl = "https://api.github.com/repos/{$owner}/{$repo}";
        return Http::withToken($token)->delete("{$baseUrl}/hooks/{$hookId}");
    }

    /**
     * Fetch the diff of a push payload.
     */
    public function getCompareDiff($token, $owner, $repo, $before, $after)
    {
        $baseUrl = "https://api.github.com/repos/{$owner}/{$repo}";
        $response = Http::withToken($token)
            ->withHeaders(['Accept' => 'application/vnd.github.v3.diff'])
            ->get("{$baseUrl}/compare/{$before}...{$after}");
            
        if ($response->successful()) {
            return $response->body();
        }
        
        return null;
    }

    /**
     * Fetch the contents of a specific file.
     */
    public function getFileContent($token, $owner, $repo, $path)
    {
        $baseUrl = "https://api.github.com/repos/{$owner}/{$repo}";
        $response = Http::withToken($token)
            ->withHeaders(['Accept' => 'application/vnd.github.v3.raw'])
            ->get("{$baseUrl}/contents/{$path}");
            
        if ($response->successful()) {
            return $response->body();
        }
        
        return null;
    }

    /**
     * Create a new branch, commit content, and open a Pull Request.
     */
    public function createPullRequest($token, $owner, $repo, $content, $commitMessage, $prTitle, $prBody)
    {
        $baseUrl = "https://api.github.com/repos/{$owner}/{$repo}";

        // Get default branch and its SHA
        $repoData = Http::withToken($token)->get($baseUrl)->json();
        $defaultBranch = $repoData['default_branch'];

        $branchData = Http::withToken($token)->get("{$baseUrl}/git/ref/heads/{$defaultBranch}")->json();
        $baseSha = $branchData['object']['sha'];

        // Create new branch
        $newBranchName = 'gitscribe-readme-' . time();
        Http::withToken($token)->post("{$baseUrl}/git/refs", [
            'ref' => "refs/heads/{$newBranchName}",
            'sha' => $baseSha
        ]);

        // Check for existing README
        $fileCheck = Http::withToken($token)->get("{$baseUrl}/contents/README.md");
        $fileSha = $fileCheck->successful() ? $fileCheck->json()['sha'] : null;

        // Commit new README
        $commitPayload = [
            'message' => $commitMessage,
            'content' => base64_encode($content),
            'branch' => $newBranchName
        ];
        
        if ($fileSha) {
            $commitPayload['sha'] = $fileSha; 
        }

        Http::withToken($token)->put("{$baseUrl}/contents/README.md", $commitPayload);

        // Open Pull Request
        $prResponse = Http::withToken($token)->post("{$baseUrl}/pulls", [
            'title' => $prTitle,
            'body' => $prBody,
            'head' => $newBranchName,
            'base' => $defaultBranch
        ]);

        return $prResponse;
    }

    public function createWikiPullRequest($token, $owner, $repo, $filesArray, $commitMessage, $prTitle, $prBody)
    {
        $baseUrl = "https://api.github.com/repos/{$owner}/{$repo}";

        // 1. Get default branch and base commit SHA
        $repoData = Http::withToken($token)->get($baseUrl)->json();
        $defaultBranch = $repoData['default_branch'];

        $branchData = Http::withToken($token)->get("{$baseUrl}/git/ref/heads/{$defaultBranch}")->json();
        $baseCommitSha = $branchData['object']['sha'];

        // Get the base tree SHA from the base commit
        $commitData = Http::withToken($token)->get("{$baseUrl}/git/commits/{$baseCommitSha}")->json();
        $baseTreeSha = $commitData['tree']['sha'];

        // 2. Create Blobs for each file
        $treeNodes = [];
        foreach ($filesArray as $path => $content) {
            $blobResponse = Http::withToken($token)->post("{$baseUrl}/git/blobs", [
                'content' => $content,
                'encoding' => 'utf-8'
            ]);
            $blobSha = $blobResponse->json()['sha'];

            $treeNodes[] = [
                'path' => $path,
                'mode' => '100644',
                'type' => 'blob',
                'sha' => $blobSha
            ];
        }

        // 3. Create a new Tree
        $treeResponse = Http::withToken($token)->post("{$baseUrl}/git/trees", [
            'base_tree' => $baseTreeSha,
            'tree' => $treeNodes
        ]);
        $newTreeSha = $treeResponse->json()['sha'];

        // 4. Create a Commit
        $commitResponse = Http::withToken($token)->post("{$baseUrl}/git/commits", [
            'message' => $commitMessage,
            'tree' => $newTreeSha,
            'parents' => [$baseCommitSha]
        ]);
        $newCommitSha = $commitResponse->json()['sha'];

        // 5. Create a new Branch
        $newBranchName = 'gitscribe-wiki-' . time();
        Http::withToken($token)->post("{$baseUrl}/git/refs", [
            'ref' => "refs/heads/{$newBranchName}",
            'sha' => $newCommitSha
        ]);

        // 6. Create Pull Request
        $prResponse = Http::withToken($token)->post("{$baseUrl}/pulls", [
            'title' => $prTitle,
            'body' => $prBody,
            'head' => $newBranchName,
            'base' => $defaultBranch
        ]);

        return $prResponse;
    }

    /**
     * Handle rate limits for responses.
     */
    protected function handleRateLimit($response)
    {
        if ($response->status() === 403 || $response->status() === 429) {
            throw new \Exception('GitHub API rate limit exceeded. Please try again later.');
        }
        return $response;
    }

    /**
     * Fetch user repositories.
     */
    public function getUserRepos($token)
    {
        $response = Http::withToken($token)
            ->get('https://api.github.com/user/repos', [
                'sort' => 'updated',       
                'per_page' => 12,          
                'affiliation' => 'owner' 
            ]);

        $this->handleRateLimit($response);

        return $response->successful() ? $response->json() : [];
    }

    /**
     * Fetch repository contents (root directory).
     */
    public function getRepoContents($token, $owner, $repo)
    {
        $response = Http::withToken($token)
            ->get("https://api.github.com/repos/{$owner}/{$repo}/contents");

        $this->handleRateLimit($response);

        return $response->successful() ? $response->json() : [];
    }

    /**
     * Fetch repository metadata.
     */
    public function getRepoMeta($token, $owner, $repo)
    {
        $response = Http::withToken($token)
            ->get("https://api.github.com/repos/{$owner}/{$repo}");

        $this->handleRateLimit($response);

        return $response->successful() ? $response->json() : [];
    }

    /**
     * Fetch the complete file tree for Deep Explorer.
     */
    public function getRepoTree($token, $owner, $repo, $defaultBranch)
    {
        $response = Http::withToken($token)
            ->get("https://api.github.com/repos/{$owner}/{$repo}/git/trees/{$defaultBranch}?recursive=1");

        $this->handleRateLimit($response);

        if ($response->successful()) {
            // Filter out trees (directories) and keep only blobs (files) to save space
            $tree = $response->json()['tree'] ?? [];
            return array_values(array_filter($tree, function ($item) {
                return $item['type'] === 'blob';
            }));
        }

        return [];
    }
}
