<?php

namespace App\Http\Requests;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('task.create')
            && $this->route('project')->isMember($this->user());
    }

    public function rules(): array
    {
        $project = $this->route('project');

        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:50000'],
            'status' => ['required', Rule::enum(TaskStatus::class)],
            'priority' => ['required', Rule::enum(TaskPriority::class)],
            'assignee_id' => [
                'nullable', 'integer',
                Rule::exists('users', 'id'),
                Rule::exists('project_members', 'user_id')->where('project_id', $project->id),
            ],
            'sprint_id' => [
                'nullable', 'string',
                Rule::exists('sprints', 'id')->where('project_id', $project->id),
            ],
            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
            'estimated_hours' => ['nullable', 'integer', 'min:1', 'max:999'],
            'label_ids' => ['nullable', 'array'],
            'label_ids.*' => [Rule::exists('labels', 'id')->where('project_id', $project->id)],
            'github_branch' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\-_\/\.]+$/'],
            'github_pr_url' => ['nullable', 'url', 'max:500'],
            'parent_id' => [
                'nullable', 'string',
                Rule::exists('tasks', 'id')->where('project_id', $project->id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul task wajib diisi.',
            'assignee_id.exists' => 'Assignee harus merupakan member project ini.',
            'github_branch.regex' => 'Format branch tidak valid.',
            'due_date.after_or_equal' => 'Due date tidak boleh di masa lalu.',
        ];
    }
}
