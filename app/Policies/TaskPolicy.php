<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function delete(User $user, Task $task): bool
    {
        return $user->can('task.delete')
            && ($task->created_by === $user->id || $user->hasRole(['pm', 'super_admin']));
    }
}
