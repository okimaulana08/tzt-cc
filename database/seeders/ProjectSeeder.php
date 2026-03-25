<?php

namespace Database\Seeders;

use App\Enums\ProjectRole;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $pm = User::where('email', 'budi@devflow.test')->first();
        $dev1 = User::where('email', 'andi@devflow.test')->first();
        $dev2 = User::where('email', 'sari@devflow.test')->first();
        $qa = User::where('email', 'citra@devflow.test')->first();
        $client = User::where('email', 'client@devflow.test')->first();

        $defaultLabels = [
            ['name' => 'Backend', 'color' => '#378ADD'],
            ['name' => 'Frontend', 'color' => '#BA7517'],
            ['name' => 'QA', 'color' => '#1D9E75'],
            ['name' => 'Bug', 'color' => '#A32D2D'],
            ['name' => 'Feature', 'color' => '#185FA5'],
        ];

        // Project 1
        $project1 = Project::firstOrCreate(
            ['slug' => 'e-commerce-revamp'],
            [
                'name' => 'E-Commerce Revamp',
                'description' => 'Revamp platform e-commerce dengan UI modern dan performa lebih baik.',
                'color' => '#378ADD',
                'owner_id' => $pm->id,
                'settings' => ['last_task_number' => 0],
            ],
        );

        // Add members to project 1
        $this->addMembers($project1, $pm, $dev1, $dev2, $qa, $client);

        // Add default labels to project 1
        foreach ($defaultLabels as $label) {
            $project1->labels()->firstOrCreate(['name' => $label['name']], $label);
        }

        // Project 2
        $project2 = Project::firstOrCreate(
            ['slug' => 'mobile-app-v2'],
            [
                'name' => 'Mobile App v2',
                'description' => 'Pengembangan versi kedua aplikasi mobile dengan fitur baru.',
                'color' => '#1D9E75',
                'owner_id' => $pm->id,
                'settings' => ['last_task_number' => 0],
            ],
        );

        $this->addMembers($project2, $pm, $dev1, $dev2, $qa, $client);

        foreach ($defaultLabels as $label) {
            $project2->labels()->firstOrCreate(['name' => $label['name']], $label);
        }
    }

    private function addMembers(Project $project, User $pm, User $dev1, User $dev2, User $qa, User $client): void
    {
        $membersToAdd = [
            [$pm->id,     ProjectRole::PM->value],
            [$dev1->id,   ProjectRole::Developer->value],
            [$dev2->id,   ProjectRole::Developer->value],
            [$qa->id,     ProjectRole::QA->value],
            [$client->id, ProjectRole::Client->value],
        ];

        foreach ($membersToAdd as [$userId, $role]) {
            if (! $project->members()->where('user_id', $userId)->exists()) {
                $project->members()->attach($userId, ['role' => $role, 'joined_at' => now()]);
            }
        }
    }
}
