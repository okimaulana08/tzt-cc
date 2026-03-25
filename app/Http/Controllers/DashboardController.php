<?php

namespace App\Http\Controllers;

use App\Services\ProjectService;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(private readonly ProjectService $projectService) {}

    public function index(): Response
    {
        $projects = $this->projectService->forUser(request()->user());

        return Inertia::render('Dashboard', [
            'projects' => $projects,
        ]);
    }
}
