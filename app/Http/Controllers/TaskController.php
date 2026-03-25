<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Models\Project;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    public function __construct(private readonly TaskService $taskService) {}

    public function board(Project $project): Response
    {
        $this->authorize('view', $project);

        $tasksByStatus = collect(TaskStatus::cases())->mapWithKeys(fn ($status) => [
            $status->value => $project->tasks()
                ->rootOnly()
                ->forStatus($status)
                ->with(['assignee:id,name', 'labels:id,name,color', 'creator:id,name'])
                ->withCount('comments')
                ->get()
                ->append(['task_code', 'is_overdue', 'subtask_progress']),
        ]);

        return Inertia::render('Tasks/Board', [
            'project' => $project->load('members.user'),
            'tasksByStatus' => $tasksByStatus,
            'statuses' => collect(TaskStatus::cases())->map(fn ($s) => [
                'value' => $s->value, 'label' => $s->label(), 'color' => $s->color(),
            ]),
            'members' => $project->members()->get(),
            'labels' => $project->labels,
            'sprints' => $project->sprints()->where('status', 'active')->get(),
        ]);
    }

    public function store(StoreTaskRequest $request, Project $project): RedirectResponse
    {
        $task = $this->taskService->create($request->validated(), $project, $request->user());

        return back()->with('success', "Task {$task->task_code} berhasil dibuat.");
    }

    public function update(UpdateTaskRequest $request, Project $project, Task $task): RedirectResponse
    {
        $this->taskService->update($task, $request->validated(), $request->user());

        return back()->with('success', 'Task berhasil diupdate.');
    }

    public function updateStatus(UpdateTaskStatusRequest $request, Project $project, Task $task): JsonResponse
    {
        $newStatus = TaskStatus::from($request->validated('status'));

        try {
            $updated = $this->taskService->updateStatus($task, $newStatus, $request->user());
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['task' => $updated->append('task_code')]);
    }

    public function reorder(Request $request, Project $project): JsonResponse
    {
        $request->validate([
            'status' => ['required', 'string'],
            'ordered_ids' => ['required', 'array'],
        ]);

        $this->taskService->reorder(
            $project,
            $request->input('ordered_ids'),
            TaskStatus::from($request->input('status')),
        );

        return response()->json(['ok' => true]);
    }

    public function destroy(Project $project, Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);
        $this->taskService->delete($task, request()->user());

        return back()->with('success', 'Task dihapus.');
    }
}
