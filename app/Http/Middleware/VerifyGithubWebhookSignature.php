<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Webhook;

class VerifyGithubWebhookSignature
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $signature = $request->header('X-Hub-Signature-256');
        
        if (!$signature) {
            return response()->json(['error' => 'Missing signature'], 403);
        }

        $payload = $request->getContent();
        $data = json_decode($payload, true);
        
        if (!isset($data['repository']['full_name'])) {
            return response()->json(['error' => 'Invalid payload format'], 400);
        }

        $repoFullName = $data['repository']['full_name'];
        
        // Find the webhook secret for this repository
        $webhook = Webhook::where('repo_full_name', $repoFullName)->first();
        
        if (!$webhook) {
            return response()->json(['error' => 'Webhook not registered for this repository'], 403);
        }

        // Verify HMAC SHA-256 signature
        $hash = 'sha256=' . hash_hmac('sha256', $payload, $webhook->secret);

        if (!hash_equals($hash, $signature)) {
            \Illuminate\Support\Facades\Log::warning("Invalid GitHub webhook signature for {$repoFullName}");
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        // Pass the webhook model into the request so the controller can use it
        $request->attributes->set('github_webhook', $webhook);

        return $next($request);
    }
}
