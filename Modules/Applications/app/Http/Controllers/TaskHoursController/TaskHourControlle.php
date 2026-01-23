<?php

namespace Modules\Applications\Http\Controllers\TaskHoursController;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Modules\Applications\Http\Requests\TaskHourRequest\StoreTaskHourRequest;
use Modules\Applications\Http\Requests\TaskHourRequest\UpdateTaskHourRequest;

class TaskHourController extends Controller
{
    public function __construct(private TaskHourService $service) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $taskHours = $this->service->getAllTaskHours($request->validated());
        return  response()->json($taskHours);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskHourRequest $request): JsonResponse
    {
        $taskHour = $this->service->createTaskHour($request->validated());
        return response()->json($taskHour, 201);
    }

    /**
     * Show the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $taskHour = $this->service->getTaskHour($id);
        return response()->json($taskHour);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskHourRequest $request, int $id): JsonResponse
    {
        $taskHour = $this->service->updateTaskHour($id, $request->validated());
        return response()->json($taskHour);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->service->deleteTaskHour($id);
        return response()->json(null, 204);
    }

    public function updateStatus(UpdateTaskHourRequest $request, int $id): JsonResponse
    {
        $request->validated();
        $taskHour = $this->service->updateTaskHourStatus($id, $request->status);
        return response()->json($taskHour);
    }
}
