<?php

use App\Enums\{TaskPriority, TaskStatus};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('project_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('sprint_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('parent_id')->nullable()
                  ->references('id')->on('tasks')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('assignee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->string('status')->default(TaskStatus::Backlog->value);
            $table->string('priority')->default(TaskPriority::Medium->value);
            $table->unsignedInteger('task_number')->default(0);
            $table->date('due_date')->nullable();
            $table->unsignedSmallInteger('estimated_hours')->nullable();
            $table->string('github_branch')->nullable();
            $table->string('github_pr_url')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['project_id', 'status']);
            $table->index(['project_id', 'task_number']);
            $table->index('assignee_id');
            $table->index('sprint_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
