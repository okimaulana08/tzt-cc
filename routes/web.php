<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectMemberController;
use App\Http\Controllers\SprintController;
use App\Http\Controllers\TaskCommentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Webhooks\GitHubWebhookController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

// GitHub Webhook — no auth, verifikasi via HMAC
Route::post('/webhooks/github', [GitHubWebhookController::class, 'handle'])
     ->name('webhooks.github')
     ->withoutMiddleware([VerifyCsrfToken::class]);

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Projects
    Route::resource('projects', ProjectController::class)->except(['show']);

    Route::prefix('projects/{project}/members')->name('projects.members.')->group(function (): void {
        Route::post('/', [ProjectMemberController::class, 'store'])->name('store');
        Route::delete('{user}', [ProjectMemberController::class, 'destroy'])->name('destroy');
    });

    // Tasks & sub-resources within a project
    Route::prefix('projects/{project}')->name('projects.')->group(function (): void {
        Route::get('board', [TaskController::class, 'board'])->name('board');
        Route::resource('tasks', TaskController::class)->except(['index', 'show']);
        Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
        Route::post('tasks/reorder', [TaskController::class, 'reorder'])->name('tasks.reorder');
        Route::resource('tasks.comments', TaskCommentController::class)->only(['store', 'update', 'destroy']);
        Route::resource('sprints', SprintController::class)->except(['index', 'show']);
        Route::resource('labels', LabelController::class)->except(['index', 'show']);
    });
});

// Legacy posts routes
Route::middleware('auth')->group(function (): void {
    Route::resource('posts', PostController::class)->except(['index', 'show']);
});
Route::resource('posts', PostController::class)->only(['index', 'show']);

require __DIR__ . '/auth.php';
