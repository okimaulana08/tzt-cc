<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessGitHubWebhookJob;
use App\Models\PipelineEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GitHubWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $secret = config('services.github.webhook_secret');
        $signature = $request->header('X-Hub-Signature-256');
        $payload = $request->getContent();

        if (! $this->verifySignature($secret, $payload, $signature)) {
            Log::warning('GitHub webhook: invalid signature', ['ip' => $request->ip()]);

            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $eventType = $request->header('X-GitHub-Event');
        $githubId = $request->header('X-GitHub-Delivery');

        if (PipelineEvent::where('github_event_id', $githubId)->exists()) {
            return response()->json(['status' => 'already_processed']);
        }

        $event = PipelineEvent::create([
            'event_type' => $eventType,
            'github_event_id' => $githubId,
            'repository' => data_get($request->json()->all(), 'repository.full_name', ''),
            'branch' => $this->extractBranch($request->json()->all()),
            'payload' => $request->json()->all(),
        ]);

        ProcessGitHubWebhookJob::dispatch($event)->onQueue('webhooks');

        return response()->json(['status' => 'queued', 'event_id' => $event->id]);
    }

    private function verifySignature(?string $secret, string $payload, ?string $signature): bool
    {
        if (! $secret || ! $signature) {
            return false;
        }

        $expected = 'sha256='.hash_hmac('sha256', $payload, $secret);

        return hash_equals($expected, $signature);
    }

    private function extractBranch(array $payload): ?string
    {
        return data_get($payload, 'ref')
            ? str_replace('refs/heads/', '', data_get($payload, 'ref'))
            : data_get($payload, 'pull_request.head.ref');
    }
}
