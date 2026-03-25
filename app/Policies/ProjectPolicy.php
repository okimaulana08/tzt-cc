<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function view(User $user, Project $project): bool
    {
        return $user->can('project.view')
            && ($project->isMember($user) || $project->owner_id === $user->id);
    }

    public function create(User $user): bool
    {
        return $user->can('project.create');
    }

    public function update(User $user, Project $project): bool
    {
        return $user->can('project.update')
            && ($project->owner_id === $user->id || $project->getMemberRole($user) === 'pm');
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->can('project.delete') && $project->owner_id === $user->id;
    }

    public function manage_members(User $user, Project $project): bool
    {
        return $user->can('project.manage_members')
            && ($project->owner_id === $user->id || $project->getMemberRole($user) === 'pm');
    }
}
