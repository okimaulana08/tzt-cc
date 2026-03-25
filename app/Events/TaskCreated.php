<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public readonly Task $task) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel("project.{$this->task->project_id}")];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->task->id,
            'task_code' => $this->task->task_code,
            'title' => $this->task->title,
            'status' => $this->task->status->value,
            'priority' => $this->task->priority->value,
            'assignee' => $this->task->assignee?->only(['id', 'name']),
            'labels' => $this->task->labels->map->only(['id', 'name', 'color']),
        ];
    }
}
