<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessGithubPushJob;

class WebhookController extends Controller
{
    /**
     * Handle incoming GitHub webhooks.
     * The VerifyGithubWebhookSignature middleware has already validated the payload.
     */
    public function handle(Request $request)
    {
        // GitHub sends ping events when the webhook is first created
        if ($request->header('X-GitHub-Event') === 'ping') {
            return response()->json(['message' => 'pong']);
        }

        if ($request->header('X-GitHub-Event') !== 'push') {
            return response()->json(['message' => 'Ignored event type']);
        }

        $payload = $request->json()->all();
        $webhook = $request->attributes->get('github_webhook');
        
        if (!$webhook) {
            // Fallback for testing when signature verification middleware is disabled
            $repoFullName = $payload['repository']['full_name'] ?? null;
            if ($repoFullName) {
                $webhook = \App\Models\Webhook::where('repo_full_name', $repoFullName)->first();
            }
        }

        if (!$webhook) {
            return response()->json(['error' => 'Webhook not found for this repository'], 404);
        }

        // Ensure we only process pushes to the default branch
        $defaultBranch = $payload['repository']['default_branch'] ?? 'main';
        $ref = $payload['ref'] ?? '';
        
        if ($ref !== "refs/heads/{$defaultBranch}") {
            return response()->json(['message' => "Ignored push to non-default branch: {$ref}"]);
        }

        // Dispatch the background job to process the diff and generate the README
        // We pass the webhook model so the job knows which user token to use
        ProcessGithubPushJob::dispatch($webhook, $payload);

        return response()->json(['message' => 'Webhook queued for processing']);
    }
}
