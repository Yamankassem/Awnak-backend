<?php

namespace Modules\Applications\Http\Controllers\TasksController;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Modules\Applications\Models\Task;
use Modules\Applications\Mail\TaskStatusChangedMail;
use Modules\Applications\Services\TasksService\TaskService;
use Modules\Applications\Http\Requests\TasksRequest\IndexTaskRequest;
use Modules\Applications\Http\Requests\TasksRequest\StoreTaskRequest;
use Modules\Applications\Http\Requests\TasksRequest\UpdateTaskRequest;
use Modules\Applications\Notifications\TasksNotification\TaskStatusChanged;
use Modules\Applications\Notifications\TasksNotification\NewTaskNotification;

class TaskController extends Controller
{
    public function __construct(private TaskService $service)
    {
    }

    public function index(IndexTaskRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Task::class);
        $tasks = $this->service->getAllTasks($request->validated());
        
        if ($tasks instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            return $this->paginated($tasks, 'task_list_retrieved');
        }
        
        return $this->success($tasks, 'task_list_retrieved');
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $this->authorize('create', Task::class);

        $task = $this->service->createTask($request->validated());

        $this->sendTaskAssignedNotification($task);

        return $this->success($task, 'messages.created_success', 201);
    }

    public function show(int $id): JsonResponse
    {
        try {
            $task = $this->service->getTask($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('messages.task_not_found', 404);
        }

        $this->authorize('view', $task);
        return $this->success($task, 'messages.success');
    }

    public function update(UpdateTaskRequest $request, int $id): JsonResponse
    {
        try {
            $task = $this->service->getTask($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('messages.task_not_found', 404);
        }

        $this->authorize('update', $task);

        $oldStatus = $task->status;
        $task = $this->service->updateTask($id, $request->validated());

        if ($task->wasChanged('status')) {
            $this->sendTaskStatusChangedNotification($task, $oldStatus);
        }

        return $this->success($task, 'messages.updated_success');
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $task = $this->service->getTask($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('messages.task_not_found', 404);
        }

        $this->authorize('delete', $task);
        $this->service->deleteTask($id);

        return $this->success(null, 'messages.deleted_success');
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        try {
            $task = $this->service->getTask($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('messages.task_not_found', 404);
        }

        $this->authorize('changeStatus', $task);

        $validated = $request->validate(['status' => 'required|in:active,complete']);

        $oldStatus = $task->status;
        $task = $this->service->updateTaskStatus($id, $validated['status']);

        $this->sendTaskStatusChangedNotification($task, $oldStatus);

        return $this->success($task, 'messages.updated_success');
    }


    private function sendTaskAssignedNotification(Task $task): void
    {
        try {
            $task->application->volunteer->notify(new NewTaskNotification($task));

            \Log::info('Task assigned notification sent', ['task_id' => $task->id]);

        } catch (\Exception $e) {
            \Log::error('Failed to send task assigned notification', [
                'task_id' => $task->id,
                'error' => $e->getMessage(),
            ]);
        }
    }


    private function sendTaskStatusChangedNotification(Task $task, string $oldStatus): void
    {
        try {
            $task->application->volunteer->notify(new TaskStatusChanged($task, $oldStatus, $task->status));

            if ($task->application->volunteer->email) {
                Mail::to($task->application->volunteer->email)
                    ->queue(new TaskStatusChangedMail($task, $oldStatus, $task->status, [
                        'subject' => 'تم تحديث حالة مهمتك',
                        'recipientName' => $task->application->volunteer->name,
                    ]));
            }

            if ($task->application->coordinator) {
                $task->application->coordinator->notify(new TaskStatusChanged($task, $oldStatus, $task->status));

                Mail::to($task->application->coordinator->email)
                    ->queue(new TaskStatusChangedMail($task, $oldStatus, $task->status, [
                        'subject' => 'تم تحديث حالة مهمة متطوع',
                        'recipientName' => $task->application->coordinator->name,
                    ]));
            }

            \Log::info('Task status changed notification sent', [
                'task_id' => $task->id,
                'from' => $oldStatus,
                'to' => $task->status,
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to send task status change notification', [
                'task_id' => $task->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}