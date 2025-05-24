<?php

namespace App\Http\Web\Controllers;

use App\Actions\Projects\CreateProjectAction;
use App\Actions\Projects\UpdateProjectAction;
use App\Http\Controller;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function __construct(
        private CreateProjectAction $createProject,
        private UpdateProjectAction $updateProject
    ) {}

    public function list(): Response
    {
        $projects = Project::query()
            ->whereNull('deleted_at')
            ->get();

        return Inertia::render('Home', [
            'projects' => $projects
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Project/CreateProjectForm');
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $this->createProject->handle([
            ...$request->validated(),
            'user_id'=> (int) auth()->id()
        ]);

        return redirect()
            ->route('projects.list')
            ->with('success', 'Project Created');
    }

    public function edit(Project $project): Response
    {
        return Inertia::render('Project/UpdateProjectForm', [
            'id' => $project->id,
            'name' => $project->name,
            'description' => $project->description,
        ]);
    }

    public function update(UpdateProjectRequest $request, int $projectId): RedirectResponse
    {
        $this->updateProject->handle($request->validated(), $projectId);

        return redirect()
            ->route('projects.list')
            ->with('success', 'Project Updated');
    }

    public function delete(int $projectId): RedirectResponse
    {
        $project = Project::findOrFail($projectId);
        $project->delete();

        return redirect()
            ->route('projects.list')
            ->with('success', 'Project Removed');
    }
}
