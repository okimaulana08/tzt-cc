<?php

namespace App\Services;

use App\Enums\TaskStatus;
use App\Events\TaskCreated;
use App\Events\TaskStatusChanged;
use App\Events\TaskUpdated;
use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TaskService
{
    public function create(array $data, Project $project, User $creator): Task
    {
        return DB::transaction(function () use ($data, $project, $creator): Task {
            $taskNumber = $project->nextTaskNumber();

            $task = Task::create([
                ...$data,
                'project_id' => $project->id,
                'created_by' => $creator->id,
                'task_number' => $taskNumber,
                'order' => Task::where('project_id', $project->id)
                    ->where('status', $data['status'] ?? TaskStatus::Backlog->value)
                    ->max('order') + 1,
            ]);

            if (! empty($data['label_ids'])) {
                $task->labels()->sync($data['label_ids']);
            }

            $this->log($project->id, $task->id, $creator->id, 'task.created', "Task {$task->task_code} dibuat");

            event(new TaskCreated($task->load(['assignee', 'labels', 'creator'])));

            return $task;
        });
    }

    public function updateStatus(Task $task, TaskStatus $newStatus, User $actor): Task
    {
        $oldStatus = $task->status;

        if (! in_array($newStatus, $oldStatus->allowedTransitions())) {
            throw new \InvalidArgumentException(
                "Tidak bisa pindah dari {$oldStatus->label()} ke {$newStatus->label()}",
            );
        }

        $task->update([
            'status' => $newStatus,
            'completed_at' => $newStatus->isTerminal() ? now() : null,
        ]);

        $this->log(
            $task->project_id,
            $task->id,
            $actor->id,
            'task.status_changed',
            "Status berubah dari {$oldStatus->label()} ke {$newStatus->label()}",
            ['before' => ['status' => $oldStatus->value], 'after' => ['status' => $newStatus->value]],
        );

        event(new TaskStatusChanged($task->fresh(), $oldStatus, $newStatus, $actor));

        return $task->fresh();
    }

    public function update(Task $task, array $data, User $actor): Task
    {
        $changed = array_filter(
            array_keys($data),
            fn ($key) => $key !== 'label_ids' && $task->$key != ($data[$key] ?? null),
        );

        $task->update(array_diff_key($data, ['label_ids' => null]));

        if (array_key_exists('label_ids', $data)) {
            $task->labels()->sync($data['label_ids'] ?? []);
        }

        if (! empty($changed)) {
            $this->log($task->project_id, $task->id, $actor->id, 'task.updated',
                'Task diupdate: '.implode(', ', $changed));
        }

        event(new TaskUpdated($task->fresh()->load(['assignee', 'labels'])));

        return $task->fresh();
    }

    public function reorder(Project $project, array $orderedIds, TaskStatus $status): void
    {
        foreach ($orderedIds as $index => $id) {
            Task::where('id', $id)
                ->where('project_id', $project->id)
                ->update(['order' => $index, 'status' => $status->value]);
        }
    }

    public function delete(Task $task, User $actor): void
    {
        $this->log($task->project_id, $task->id, $actor->id, 'task.deleted',
            "Task {$task->task_code} dihapus");
        $task->delete();
    }

    private function log(
        string $projectId,
        ?string $taskId,
        int $userId,
        string $event,
        string $description,
        array $properties = [],
    ): void {
        ActivityLog::create([
            'project_id' => $projectId,
            'task_id' => $taskId,
            'user_id' => $userId,
            'event' => $event,
            'description' => $description,
            'properties' => $properties ?: null,
        ]);
    }
}
