<?php

namespace Tests\Feature\Task;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTaskStatusTest extends TestCase
{
    use RefreshDatabase;

    private User $pm;

    private Project $project;

    private Task $task;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->pm = User::factory()->create(['email_verified_at' => now()]);
        $this->pm->assignRole('pm');

        $this->project = Project::create([
            'name' => 'Test Project',
            'slug' => 'test-project',
            'owner_id' => $this->pm->id,
            'settings' => ['last_task_number' => 0],
        ]);
        $this->project->members()->attach($this->pm->id, ['role' => 'pm', 'joined_at' => now()]);

        $taskNumber = $this->project->nextTaskNumber();
        $this->task = Task::create([
            'project_id' => $this->project->id,
            'created_by' => $this->pm->id,
            'title' => 'Test Task',
            'status' => TaskStatus::Backlog,
            'priority' => TaskPriority::Medium,
            'task_number' => $taskNumber,
            'order' => 1,
        ]);
    }

    public function test_status_can_change_from_backlog_to_in_progress(): void
    {
        $response = $this->actingAs($this->pm)
            ->patchJson(
                route('projects.tasks.updateStatus', [$this->project, $this->task]),
                ['status' => TaskStatus::InProgress->value],
            );

        $response->assertOk();
        $this->assertDatabaseHas('tasks', ['id' => $this->task->id, 'status' => TaskStatus::InProgress->value]);
    }

    public function test_cannot_skip_directly_to_done_from_backlog(): void
    {
        $response = $this->actingAs($this->pm)
            ->patchJson(
                route('projects.tasks.updateStatus', [$this->project, $this->task]),
                ['status' => TaskStatus::Done->value],
            );

        $response->assertStatus(422);
    }

    public function test_completed_at_set_when_status_becomes_done(): void
    {
        $service = new TaskService;
        $service->updateStatus($this->task, TaskStatus::InProgress, $this->pm);
        $service->updateStatus($this->task->fresh(), TaskStatus::Review, $this->pm);
        $service->updateStatus($this->task->fresh(), TaskStatus::Done, $this->pm);

        $this->assertNotNull($this->task->fresh()->completed_at);
    }

    public function test_completed_at_cleared_when_task_reopened(): void
    {
        $service = new TaskService;
        $service->updateStatus($this->task, TaskStatus::InProgress, $this->pm);
        $service->updateStatus($this->task->fresh(), TaskStatus::Review, $this->pm);
        $service->updateStatus($this->task->fresh(), TaskStatus::Done, $this->pm);

        // Reopen from Done to Backlog
        $service->updateStatus($this->task->fresh(), TaskStatus::Backlog, $this->pm);

        $this->assertNull($this->task->fresh()->completed_at);
    }
}
