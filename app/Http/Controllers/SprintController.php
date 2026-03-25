<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Sprint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SprintController extends Controller
{
    public function store(Request $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'goal' => ['nullable', 'string', 'max:1000'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        $project->sprints()->create($request->only(['name', 'goal', 'starts_at', 'ends_at']));

        return back()->with('success', 'Sprint berhasil dibuat.');
    }

    public function update(Request $request, Project $project, Sprint $sprint): RedirectResponse
    {
        $this->authorize('update', $project);

        $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:100'],
            'goal' => ['nullable', 'string', 'max:1000'],
            'status' => ['nullable', 'string', 'in:planning,active,completed'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date'],
        ]);

        $sprint->update($request->only(['name', 'goal', 'status', 'starts_at', 'ends_at']));

        return back()->with('success', 'Sprint diupdate.');
    }

    public function destroy(Project $project, Sprint $sprint): RedirectResponse
    {
        $this->authorize('update', $project);
        $sprint->delete();

        return back()->with('success', 'Sprint dihapus.');
    }
}
