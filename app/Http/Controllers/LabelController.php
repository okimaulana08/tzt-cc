<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    public function store(Request $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        $project->labels()->create($request->only(['name', 'color']));

        return back()->with('success', 'Label berhasil dibuat.');
    }

    public function update(Request $request, Project $project, Label $label): RedirectResponse
    {
        $this->authorize('update', $project);

        $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        $label->update($request->only(['name', 'color']));

        return back()->with('success', 'Label diupdate.');
    }

    public function destroy(Project $project, Label $label): RedirectResponse
    {
        $this->authorize('update', $project);
        $label->delete();

        return back()->with('success', 'Label dihapus.');
    }
}
