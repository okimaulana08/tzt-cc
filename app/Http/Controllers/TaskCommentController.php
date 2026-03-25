<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskCommentRequest;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaskCommentController extends Controller
{
    public function store(StoreTaskCommentRequest $request, Project $project, Task $task): RedirectResponse
    {
        $task->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $request->validated('content'),
        ]);

        return back()->with('success', 'Komentar ditambahkan.');
    }

    public function update(Request $request, Project $project, Task $task, TaskComment $comment): RedirectResponse
    {
        $this->authorize('update', $comment);

        $request->validate(['content' => ['required', 'string', 'max:10000']]);

        $comment->update([
            'content' => $request->input('content'),
            'edited_at' => now(),
        ]);

        return back()->with('success', 'Komentar diupdate.');
    }

    public function destroy(Project $project, Task $task, TaskComment $comment): RedirectResponse
    {
        $this->authorize('delete', $comment);
        $comment->delete();

        return back()->with('success', 'Komentar dihapus.');
    }
}
