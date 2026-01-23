<?php

namespace Modules\Applications\Http\Controllers\TasksController;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Modules\Applications\Http\Requests\TaskRequest\StoreTaskRequest;
use Modules\Applications\Http\Requests\TaskRequest\UpdateTaskRequest;

class TaskController extends Controller
{
    public function __construct(private TaskService $service) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $tasks = $this->service->getAllTasks($request->validated());
        return  response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->service->createTask($request->validated());
        return response()->json($task, 201);
    }

    /**
     * Show the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $task = $this->service->getTask($id);
        return response()->json($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, int $id): JsonResponse
    {
        $task = $this->service->updateTask($id, $request->validated());
        return response()->json($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->service->deleteTask($id);
        return response()->json(null, 204);
    }

    public function updateStatus(UpdateTaskRequest $request, int $id): JsonResponse
    {
        $request->validated();
        $task = $this->service->updateTaskStatus($id, $request->status);
        return response()->json($task);
    }
}
