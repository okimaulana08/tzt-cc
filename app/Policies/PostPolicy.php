<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Post $post): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create posts');
    }

    public function update(User $user, Post $post): bool
    {
        return $user->hasPermissionTo('edit posts') || $user->id === $post->user_id;
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->hasPermissionTo('delete posts') || $user->id === $post->user_id;
    }
}
