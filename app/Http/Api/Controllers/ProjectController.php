<?php

namespace App\Http\Api\Controllers;

use App\Actions\Projects\CreateProjectAction;
use App\Actions\Projects\UpdateProjectAction;
use App\Http\Controller;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 * @OA\Tag(
 *     name="Projects",
 *     description="API Endpoints for managing projects"
 * )
 */
class ProjectController extends Controller
{
    public function __construct(
        private CreateProjectAction $createProject,
        private UpdateProjectAction $updateProject
    ) {}

    /**
     * @OA\Get(
     *     path="/api/projects",
     *     summary="List all projects",
     *     tags={"Projects"},
     *     @OA\Response(
     *         response=200,
     *         description="List of projects",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ProjectResource"))
     *     )
     * )
     */
    public function list(): AnonymousResourceCollection
    {
        $projects = Project::query()
            ->with('user')
            ->whereNull('deleted_at')
            ->paginate(15);

        return ProjectResource::collection($projects);
    }

    /**
     * @OA\Get(
     *     path="/api/projects/{id}",
     *     summary="Get a single project",
     *     tags={"Projects"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Project found",
     *         @OA\JsonContent(ref="#/components/schemas/ProjectResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Project not found"
     *     )
     * )
     */
    public function show(int $project): ProjectResource
    {
        $project = Project::query()
            ->with('user')
            ->where('id', $project)
            ->whereNull('deleted_at')
            ->firstOrFail();

        return new ProjectResource($project);
    }

    /**
     * @OA\Post(
     *     path="/api/projects",
     *     summary="Create a new project",
     *     tags={"Projects"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreProjectRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Project created",
     *         @OA\JsonContent(ref="#/components/schemas/ProjectResource")
     *     )
     * )
     */
    public function store(StoreProjectRequest $request): ProjectResource
    {
        $project = $this->createProject->handle([
            ...$request->validated(),
            'user_id'=> (int) auth()->id()
        ]);

        return new ProjectResource($project);
    }

    /**
     * @OA\Put(
     *     path="/api/projects/{id}",
     *     summary="Update an existing project",
     *     tags={"Projects"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateProjectRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Project updated",
     *         @OA\JsonContent(ref="#/components/schemas/ProjectResource")
     *     )
     * )
     */
    public function update(UpdateProjectRequest $request, int $projectId): ProjectResource
    {
        $project = $this->updateProject->handle($request->validated(), $projectId);

        return new ProjectResource($project);
    }

    /**
     * @OA\Delete(
     *     path="/api/projects/{id}",
     *     summary="Delete a project",
     *     tags={"Projects"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Project deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Project Deleted")
     *         )
     *     )
     * )
     */
    public function delete(int $projectId): JsonResponse
    {
        $project = Project::findOrFail($projectId);
        $project->delete();

        return response()->json([
            'message' => 'Project Deleted'
        ], Response::HTTP_OK);
    }
}
