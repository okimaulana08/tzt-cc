<?php

namespace Tests\Unit\Services;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\User;
use App\Services\TaskService;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    private TaskService $service;

    private User $user;

    private Project $project;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->service = new TaskService;
        $this->user = User::factory()->create();
        $this->user->assignRole('pm');

        $this->project = Project::create([
            'name' => 'Test Project',
            'slug' => 'test-project',
            'owner_id' => $this->user->id,
            'settings' => ['last_task_number' => 0],
        ]);
        $this->project->members()->attach($this->user->id, ['role' => 'pm', 'joined_at' => now()]);
    }

    public function test_create_generates_correct_task_number(): void
    {
        $task1 = $this->service->create([
            'title' => 'Task 1',
            'status' => TaskStatus::Backlog->value,
            'priority' => TaskPriority::Medium->value,
        ], $this->project, $this->user);

        $task2 = $this->service->create([
            'title' => 'Task 2',
            'status' => TaskStatus::Backlog->value,
            'priority' => TaskPriority::Medium->value,
        ], $this->project, $this->user);

        $this->assertEquals(1, $task1->task_number);
        $this->assertEquals(2, $task2->task_number);
    }

    public function test_update_status_validates_transitions(): void
    {
        $task = $this->service->create([
            'title' => 'Task',
            'status' => TaskStatus::Backlog->value,
            'priority' => TaskPriority::Medium->value,
        ], $this->project, $this->user);

        $this->expectException(\InvalidArgumentException::class);

        // Cannot go from Backlog directly to Done
        $this->service->updateStatus($task, TaskStatus::Done, $this->user);
    }

    public function test_reorder_updates_order_fields(): void
    {
        $task1 = $this->service->create(['title' => 'Task A', 'status' => TaskStatus::Backlog->value, 'priority' => TaskPriority::Medium->value], $this->project, $this->user);
        $task2 = $this->service->create(['title' => 'Task B', 'status' => TaskStatus::Backlog->value, 'priority' => TaskPriority::Medium->value], $this->project, $this->user);

        $this->service->reorder($this->project, [$task2->id, $task1->id], TaskStatus::Backlog);

        $this->assertEquals(0, $task2->fresh()->order);
        $this->assertEquals(1, $task1->fresh()->order);
    }
}
