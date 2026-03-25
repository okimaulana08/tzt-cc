<?php

namespace App\Jobs;

use App\Models\PipelineEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessGitHubWebhookJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 30;

    public array $backoff = [10, 30, 60];

    public function __construct(private readonly PipelineEvent $event) {}

    public function handle(): void
    {
        // Placeholder — akan diisi di Phase 2
        $this->event->update(['processed_at' => now()]);
    }
}
