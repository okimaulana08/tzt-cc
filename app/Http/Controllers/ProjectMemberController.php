<?php

namespace App\Http\Controllers;

use App\Enums\ProjectRole;
use App\Models\Project;
use App\Models\User;
use App\Services\ProjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProjectMemberController extends Controller
{
    public function __construct(private readonly ProjectService $projectService) {}

    public function store(Request $request, Project $project): RedirectResponse
    {
        $this->authorize('manage_members', $project);

        $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'role' => ['required', 'string', 'in:'.implode(',', array_column(ProjectRole::cases(), 'value'))],
        ]);

        $user = User::findOrFail($request->input('user_id'));
        $role = ProjectRole::from($request->input('role'));

        $this->projectService->addMember($project, $user, $role);

        return back()->with('success', "{$user->name} berhasil ditambahkan ke project.");
    }

    public function destroy(Project $project, User $user): RedirectResponse
    {
        $this->authorize('manage_members', $project);

        $this->projectService->removeMember($project, $user);

        return back()->with('success', "{$user->name} dihapus dari project.");
    }
}
