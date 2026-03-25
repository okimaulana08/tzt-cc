<?php

namespace App\Models;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $fillable = [
        'project_id', 'sprint_id', 'parent_id', 'created_by', 'assignee_id',
        'title', 'description', 'status', 'priority', 'task_number',
        'due_date', 'estimated_hours', 'github_branch', 'github_pr_url',
        'order', 'metadata', 'completed_at',
    ];

    protected $casts = [
        'status' => TaskStatus::class,
        'priority' => TaskPriority::class,
        'due_date' => 'date',
        'completed_at' => 'datetime',
        'metadata' => 'array',
    ];

    // ── Relations ──────────────────────────────────────────────────────────

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function sprint(): BelongsTo
    {
        return $this->belongsTo(Sprint::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_id')->orderBy('order');
    }

    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(Label::class, 'task_labels');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class)->oldest();
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class)->latest('created_at');
    }

    // ── Scopes ─────────────────────────────────────────────────────────────

    public function scopeRootOnly(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function scopeAssignedTo(Builder $query, int $userId): Builder
    {
        return $query->where('assignee_id', $userId);
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->whereNotNull('due_date')
            ->where('due_date', '<', now()->toDateString())
            ->where('status', '!=', TaskStatus::Done->value);
    }

    public function scopeForStatus(Builder $query, TaskStatus $status): Builder
    {
        return $query->where('status', $status->value)->orderBy('order');
    }

    // ── Accessors ──────────────────────────────────────────────────────────

    public function getTaskCodeAttribute(): string
    {
        return strtoupper(substr($this->project->slug ?? 'TKT', 0, 3)).'-'.$this->task_number;
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date
            && $this->due_date->isPast()
            && $this->status !== TaskStatus::Done;
    }

    public function getSubtaskProgressAttribute(): array
    {
        $total = $this->subtasks()->count();
        if ($total === 0) {
            return ['done' => 0, 'total' => 0, 'percent' => 0];
        }
        $done = $this->subtasks()->where('status', TaskStatus::Done->value)->count();

        return ['done' => $done, 'total' => $total, 'percent' => (int) round($done / $total * 100)];
    }
}
