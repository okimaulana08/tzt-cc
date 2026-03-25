<?php

namespace App\Services;

use App\Enums\ProjectRole;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    public function create(array $data, User $creator): Project
    {
        return DB::transaction(function () use ($data, $creator): Project {
            $project = Project::create([...$data, 'owner_id' => $creator->id]);

            $project->members()->attach($creator->id, [
                'role' => ProjectRole::PM->value,
                'joined_at' => now(),
            ]);

            return $project;
        });
    }

    public function addMember(Project $project, User $user, ProjectRole $role): void
    {
        if ($project->isMember($user)) {
            throw new \DomainException('User sudah menjadi member project ini.');
        }

        $project->members()->attach($user->id, [
            'role' => $role->value,
            'joined_at' => now(),
        ]);
    }

    public function removeMember(Project $project, User $user): void
    {
        if ($project->owner_id === $user->id) {
            throw new \DomainException('Owner project tidak bisa dihapus dari project.');
        }

        $project->members()->detach($user->id);
    }

    public function forUser(User $user): Collection
    {
        return Project::active()
            ->forUser($user)
            ->withCount(['tasks', 'members'])
            ->with(['owner'])
            ->latest()
            ->get();
    }
}
