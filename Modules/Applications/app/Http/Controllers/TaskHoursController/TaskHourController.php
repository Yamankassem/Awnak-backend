<?php

namespace Modules\Applications\Http\Controllers\TaskHoursController;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Modules\Applications\Models\TaskHour;
use Modules\Applications\Services\TaskHoursService\TaskHourService;
use Modules\Applications\Http\Requests\TaskHoursRequest\StoreTaskHourRequest;
use Modules\Applications\Http\Requests\TaskHoursRequest\UpdateTaskHourRequest;
use Modules\Applications\Notifications\TaskHoursNotification\HoursLoggedNotification;
use Modules\Applications\Http\Requests\TaskHoursRequest\IndexTaskHourRequest;

class TaskHourController extends Controller
{
    public function __construct(private TaskHourService $service) {}

    public function index(IndexTaskHourRequest $request): JsonResponse
    {
        $this->authorize('viewAny', TaskHour::class);
        $taskHours = $this->service->getAllTaskHours($request->validated());
        
        if ($taskHours instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            return $this->paginated($taskHours, 'taskHour_list_retrieved');
        }
        
        return $this->success($taskHours, 'taskHour_list_retrieved');
    }

    public function store(StoreTaskHourRequest $request): JsonResponse
    {
        $this->authorize('create', TaskHour::class);
        
        $taskHour = $this->service->createTaskHour($request->validated());
        
        $this->sendHoursLoggedNotification($taskHour);
        
        return $this->success($taskHour, 'messages.created_success', 201);
    }

    public function update(UpdateTaskHourRequest $request, int $id): JsonResponse
    {
        try {
            $taskHour = $this->service->getTaskHour($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('messages.taskHour_not_found', 404);
        }

        $this->authorize('update', $taskHour);
        
        $oldHours = $taskHour->hours;
        $taskHour = $this->service->updateTaskHour($id, $request->validated());
        
        if ($taskHour->wasChanged('hours')) {
            \Log::info('Task hours updated', [
                'task_hour_id' => $taskHour->id,
                'old_hours' => $oldHours,
                'new_hours' => $taskHour->hours,
                'by' => auth()->id(),
            ]);
        }
        
        return $this->success($taskHour, 'messages.updated_success');
    }
    
    public function show(int $id): JsonResponse
    {
        try {
            $taskHour = $this->service->getTaskHour($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('messages.taskHour_not_found', 404);
        }

        $this->authorize('view', $taskHour);
        return $this->success($taskHour, 'messages.success');
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $taskHour = $this->service->getTaskHour($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('messages.taskHour_not_found', 404);
        }

        $this->authorize('delete', $taskHour);
        $this->service->deleteTaskHour($id);

        return $this->success(null, 'messages.deleted_success');
    }
    
    
    private function sendHoursLoggedNotification(TaskHour $taskHour): void
    {
        try {
            if ($taskHour->task->application->coordinator) {
                $taskHour->task->application->coordinator->notify(
                    new HoursLoggedNotification($taskHour)
                );
            }
            
            if ($taskHour->task->application->opportunity->createdBy) {
                $taskHour->task->application->opportunity->createdBy->notify(
                    new HoursLoggedNotification($taskHour)
                );
            }
            
            \Log::info('Hours logged notification sent', [
                'task_hour_id' => $taskHour->id,
                'hours' => $taskHour->hours,
                'task_id' => $taskHour->task_id,
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to send hours logged notification', [
                'task_hour_id' => $taskHour->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}