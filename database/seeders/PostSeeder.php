<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();
        $editor = User::where('email', 'editor@example.com')->first();

        $posts = [
            [
                'user_id' => $admin->id,
                'title' => 'Welcome to TZT App',
                'slug' => 'welcome-to-tzt-app',
                'excerpt' => 'An introduction to our platform and what you can do here.',
                'content' => '<p>Welcome to <strong>TZT App</strong>! This is a full-featured Laravel 13 application built with Blade templates, authentication, role-based permissions, and a complete CRUD system.</p><p>Feel free to explore and create your own posts!</p>',
                'status' => 'published',
                'published_at' => now(),
            ],
            [
                'user_id' => $editor->id,
                'title' => 'Getting Started with Laravel 13',
                'slug' => 'getting-started-with-laravel-13',
                'excerpt' => 'Learn the basics of Laravel 13 and its new features.',
                'content' => '<p>Laravel 13 introduces several exciting improvements. In this post, we cover the key changes and how to take advantage of them in your projects.</p>',
                'status' => 'published',
                'published_at' => now(),
            ],
            [
                'user_id' => $admin->id,
                'title' => 'Draft: Upcoming Features',
                'slug' => 'draft-upcoming-features',
                'excerpt' => 'A sneak peek at what we are building next.',
                'content' => '<p>This post is still a draft. Stay tuned for the full announcement!</p>',
                'status' => 'draft',
                'published_at' => null,
            ],
        ];

        foreach ($posts as $post) {
            Post::firstOrCreate(['slug' => $post['slug']], $post);
        }
    }
}
