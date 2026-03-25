<?php

namespace App\Events;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly Task $task,
        public readonly TaskStatus $oldStatus,
        public readonly TaskStatus $newStatus,
        public readonly User $actor,
    ) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel("project.{$this->task->project_id}")];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->task->id,
            'task_code' => $this->task->task_code,
            'old_status' => $this->oldStatus->value,
            'new_status' => $this->newStatus->value,
            'actor' => $this->actor->only(['id', 'name']),
        ];
    }
}
