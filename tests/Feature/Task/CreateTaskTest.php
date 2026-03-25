<?php

namespace Tests\Feature\Task;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Label;
use App\Models\Project;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateTaskTest extends TestCase
{
    use RefreshDatabase;

    private User $pm;

    private User $developer;

    private User $client;

    private Project $project;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->pm = User::factory()->create(['email_verified_at' => now()]);
        $this->developer = User::factory()->create(['email_verified_at' => now()]);
        $this->client = User::factory()->create(['email_verified_at' => now()]);

        $this->pm->assignRole('pm');
        $this->developer->assignRole('developer');
        $this->client->assignRole('client');

        $this->project = Project::create([
            'name' => 'Test Project',
            'slug' => 'test-project',
            'owner_id' => $this->pm->id,
            'settings' => ['last_task_number' => 0],
        ]);
        $this->project->members()->attach($this->pm->id, ['role' => 'pm', 'joined_at' => now()]);
        $this->project->members()->attach($this->developer->id, ['role' => 'developer', 'joined_at' => now()]);
    }

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Test Task',
            'status' => TaskStatus::Backlog->value,
            'priority' => TaskPriority::Medium->value,
        ], $overrides);
    }

    public function test_pm_can_create_task_successfully(): void
    {
        $response = $this->actingAs($this->pm)
            ->post(route('projects.tasks.store', $this->project), $this->validPayload());

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', ['title' => 'Test Task', 'project_id' => $this->project->id]);
    }

    public function test_developer_can_create_task_successfully(): void
    {
        $response = $this->actingAs($this->developer)
            ->post(route('projects.tasks.store', $this->project), $this->validPayload());

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', ['title' => 'Test Task', 'project_id' => $this->project->id]);
    }

    public function test_client_cannot_create_task(): void
    {
        $this->project->members()->attach($this->client->id, ['role' => 'client', 'joined_at' => now()]);

        $response = $this->actingAs($this->client)
            ->post(route('projects.tasks.store', $this->project), $this->validPayload());

        $response->assertForbidden();
        $this->assertDatabaseMissing('tasks', ['project_id' => $this->project->id]);
    }

    public function test_task_number_auto_increments_per_project(): void
    {
        $this->actingAs($this->pm)
            ->post(route('projects.tasks.store', $this->project), $this->validPayload(['title' => 'Task A']));
        $this->actingAs($this->pm)
            ->post(route('projects.tasks.store', $this->project), $this->validPayload(['title' => 'Task B']));

        $this->assertDatabaseHas('tasks', ['title' => 'Task A', 'task_number' => 1]);
        $this->assertDatabaseHas('tasks', ['title' => 'Task B', 'task_number' => 2]);
    }

    public function test_task_requires_title(): void
    {
        $response = $this->actingAs($this->pm)
            ->post(route('projects.tasks.store', $this->project), $this->validPayload(['title' => '']));

        $response->assertSessionHasErrors('title');
    }

    public function test_assignee_must_be_project_member(): void
    {
        $outsider = User::factory()->create(['email_verified_at' => now()]);
        $outsider->assignRole('developer');

        $response = $this->actingAs($this->pm)
            ->post(route('projects.tasks.store', $this->project), $this->validPayload(['assignee_id' => $outsider->id]));

        $response->assertSessionHasErrors('assignee_id');
    }

    public function test_label_must_belong_to_project(): void
    {
        $otherProject = Project::create([
            'name' => 'Other Project',
            'slug' => 'other-project',
            'owner_id' => $this->pm->id,
            'settings' => ['last_task_number' => 0],
        ]);
        $foreignLabel = Label::create([
            'project_id' => $otherProject->id,
            'name' => 'Foreign Label',
            'color' => '#000000',
        ]);

        $response = $this->actingAs($this->pm)
            ->post(route('projects.tasks.store', $this->project), $this->validPayload(['label_ids' => [$foreignLabel->id]]));

        $response->assertSessionHasErrors('label_ids.0');
    }
}
