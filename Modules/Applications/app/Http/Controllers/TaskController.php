<?php

namespace Modules\Applications\Http\Controllers;

use Log;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Modules\Applications\Models\Task;
use Modules\Applications\Models\Feedback;
use Modules\Applications\Models\TaskHour;
use Modules\Applications\Services\TaskService;
use Modules\Applications\Services\AuditService;
use Modules\Applications\Services\CacheService;
use Modules\Applications\QueryBuilders\TaskQueryBuilder;
use Modules\Applications\Http\Requests\TasksRequest\IndexTaskRequest;
use Modules\Applications\Http\Requests\TasksRequest\StoreTaskRequest;
use Modules\Applications\Http\Requests\TasksRequest\UpdateTaskRequest;
use Modules\Applications\Notifications\TasksNotification\TaskStatusChanged;
use Modules\Applications\Http\Requests\TasksRequest\UpdateStatusTaskRequest;
use Modules\Applications\Notifications\TasksNotification\NewTaskNotification;

/**
 * Task Controller
 *
 * Handles all task-related operations including creation,
 * retrieval, updating, status changes, and logging hours.
 *
 * @package Modules\Applications\Http\Controllers
 * @author Your Name
 */
class TaskController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param TaskService $service
     * @param AuditService $auditService
     * @param CacheService $cacheService
     */
    public function __construct(private TaskService $service, private AuditService $auditService, private CacheService $cacheService)
    {
    }


    /**
     * Display a listing of tasks.
     *
     * @param IndexTaskRequest $request
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @api GET /api/tasks
     * @permission viewAny tasks
     */
    public function index(IndexTaskRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Task::class);

        $builder = new TaskQueryBuilder(Task::query(), $request->user());
        $query = $builder->forCurrentUser()->withAllRelations();

        if ($request->filled('status')) {
            $query->withStatus($request->status);
        }

        if ($request->filled('application_id')) {
            $query->where('application_id', $request->application_id);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
        }

        $tasks = $query->latestFirst()->paginate($request->get('per_page', 15));

        return $this->paginated($tasks, 'messages.success');
    }

    /**
     * Store a newly created task.
     *
     * @param StoreTaskRequest $request
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @api POST /api/tasks
     * @permission create tasks
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $this->authorize('create', Task::class);

        $task = Task::create($request->validated());

        $this->auditService->log('created', 'Task', auth()->user(), [
        'task_id' => $task->id,
        'title' => $task->title,
        'application_id' => $task->application_id,
        'status' => $task->status
        ]);

        if ($task->application->volunteer) {
            $task->application->volunteer->notify(new NewTaskNotification($task));
        }

        return $this->success($task, 'messages.task_created', 201);
    }

    /**
     * Display the specified task.
     *
     * @param int $id Task ID
     * @return JsonResponse
     * @throws ModelNotFoundException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @api GET /api/tasks/{id}
     * @permission view tasks
     */
    public function show(int $id): JsonResponse
    {
        $task = Task::with(['application.opportunity', 'application.volunteer',
                           'application.coordinator', 'taskHours', 'feedbacks'])
                    ->findOrFail($id);

        $this->authorize('view', $task);

        return $this->success($task, 'messages.success');
    }

    /**
     * Update the specified task.
     *
     * @param UpdateTaskRequest $request
     * @param int $id Task ID
     * @return JsonResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @api PUT /api/tasks/{id}
     * @permission update tasks
     */
    public function update(UpdateTaskRequest $request, int $id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $this->authorize('update', $task);

        $oldStatus = $task->status;
        $task->update($request->validated());

        if ($task->wasChanged('status')) {
            $this->sendStatusChangeNotifications($task, $oldStatus);
        }

        return $this->success($task, 'messages.task_updated');
    }

    /**
    * Remove the specified task.
    *
    * @param int $id Task ID
    * @return JsonResponse
    * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
    * @throws \Illuminate\Auth\Access\AuthorizationException
    *
    * @api DELETE /api/tasks/{id}
    * @permission delete tasks
    */
    public function destroy(int $id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $this->authorize('delete', $task);

        $task->delete();

        return $this->success(null, 'messages.task_deleted');
    }

    /**
    * Update task status.
    *
    * @param UpdateStatusTaskRequest $request
    * @param int $id Task ID
    * @return JsonResponse
    * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
    * @throws \Illuminate\Auth\Access\AuthorizationException
    *
    * @api PATCH /api/tasks/{id}/status
    * @permission changeStatus tasks
    */
    public function updateStatus(UpdateStatusTaskRequest $request, int $id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $this->authorize('changeStatus', $task);

        $oldStatus = $task->status;
        $task->update(['status' => $request->validated('status')]);

        $this->auditService->log('status_changed', 'Task', auth()->user(), [
        'task_id' => $task->id,
        'title' => $task->title,
        'old_status' => $oldStatus,
        'new_status' => $task->status,
        'volunteer_id' => $task->application->volunteer_id ?? null
        ]);

        $this->sendStatusChangeNotifications($task, $oldStatus);

        return $this->success($task, 'messages.task_status_updated');
    }

    /**
 * Get preparation tasks.
 *
 * @param Request $request
 * @return JsonResponse
 * @throws \Illuminate\Auth\Access\AuthorizationException
 *
 * @api GET /api/tasks/preparation
 * @permission viewAny tasks
 */
    public function preparation(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Task::class);

        $builder = new TaskQueryBuilder(Task::query(), $request->user());
        $tasks = $builder->forCurrentUser()
            ->preparation()
            ->withAllRelations()
            ->latestFirst()
            ->paginate($request->get('per_page', 15));

        return $this->paginated($tasks, 'messages.success');
    }

    /**
     * Get active tasks.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @api GET /api/tasks/active
     * @permission viewAny tasks
     */
    public function active(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Task::class);

        $builder = new TaskQueryBuilder(Task::query(), $request->user());
        $tasks = $builder->forCurrentUser()
            ->active()
            ->withAllRelations()
            ->latestFirst()
            ->paginate($request->get('per_page', 15));

        return $this->paginated($tasks, 'messages.success');
    }

    /**
     * Get completed tasks.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @api GET /api/tasks/completed
     * @permission viewAny tasks
     */
    public function completed(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Task::class);

        $builder = new TaskQueryBuilder(Task::query(), $request->user());
        $tasks = $builder->forCurrentUser()
            ->completed()
            ->withAllRelations()
            ->latestFirst()
            ->paginate($request->get('per_page', 15));

        return $this->paginated($tasks, 'messages.success');
    }

    /**
     * Get cancelled tasks.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @api GET /api/tasks/cancelled
     * @permission viewAny tasks
     */
    public function cancelled(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Task::class);

        $builder = new TaskQueryBuilder(Task::query(), $request->user());
        $tasks = $builder->forCurrentUser()
            ->cancelled()
            ->withAllRelations()
            ->latestFirst()
            ->paginate($request->get('per_page', 15));

        return $this->paginated($tasks, 'messages.success');
    }

    /**
     * Get overdue tasks.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @api GET /api/tasks/overdue
     * @permission viewAny tasks
     */
    public function overdue(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Task::class);

        $builder = new TaskQueryBuilder(Task::query(), $request->user());
        $tasks = $builder->forCurrentUser()
            ->overdue()
            ->withAllRelations()
            ->latestFirst()
            ->get();

        return $this->success($tasks, 'messages.success');
    }

    /**
     * Log hours for a specific task.
     *
     * @param Request $request
     * @param int $id Task ID
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     *
     * @api POST /api/tasks/{id}/log-hours
     * @permission logHours task
     */
    public function logHours(Request $request, int $id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $this->authorize('logHours', $task);

        $validated = $request->validate([
            'hours' => 'required|integer|min:1|max:12',
            'started_date' => 'required|date',
            'ended_date' => 'required|date|after_or_equal:started_date',
            'note' => 'nullable|string|max:500',
        ]);

        $taskHour = TaskHour::create(array_merge($validated, ['task_id' => $task->id]));

        return $this->success($taskHour, 'messages.taskHour_created', 201);
    }


    /**
 * Add feedback to a task.
 *
 * @param Request $request
 * @param int $id Task ID
 * @return JsonResponse
 * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
 * @throws \Illuminate\Auth\Access\AuthorizationException
 * @throws \Illuminate\Validation\ValidationException
 *
 * @api POST /api/tasks/{id}/feedback
 * @permission addFeedback task
 */
    public function addFeedback(Request $request, int $id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $this->authorize('addFeedback', $task);

        $validated = $request->validate([
            'name_of_org' => 'required|string|max:255',
            'name_of_vol' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        $feedback = Feedback::create(array_merge($validated, ['task_id' => $task->id]));

        return $this->success($feedback, 'messages.feedback_created', 201);
    }

    /**
     * Get feedbacks for a specific task.
     *
     * @param int $id Task ID
     * @return JsonResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @api GET /api/tasks/{id}/feedbacks
     * @permission viewFeedbacks task
     */
    public function feedbacks(int $id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $this->authorize('viewFeedbacks', $task);

        $feedbacks = $task->feedbacks()->with(['task.application'])->latest()->get();

        return $this->success($feedbacks, 'messages.success');
    }

    /**
     * Get logged hours for a specific task.
     *
     * @param int $id Task ID
     * @return JsonResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @api GET /api/tasks/{id}/hours
     * @permission viewHours task
     */
    public function hours(int $id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $this->authorize('viewHours', $task);

        $hours = $task->taskHours()->with(['task.application'])->latest()->get();

        return $this->success($hours, 'messages.success');
    }

    /**
     * Get task statistics.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @api GET /api/tasks/stats
     * @permission viewAny tasks
     */
    public function stats(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Task::class);

        $builder = new TaskQueryBuilder(Task::query(), $request->user());
        $query = $builder->forCurrentUser();

        $stats = [
            'total_tasks' => $query->count(),
            'preparation_tasks' => $query->preparation()->count(),
            'active_tasks' => $query->active()->count(),
            'completed_tasks' => $query->completed()->count(),
            'cancelled_tasks' => $query->cancelled()->count(),
            'overdue_tasks' => $query->overdue()->count(),
            'avg_completion_time' => $this->calculateAvgCompletionTime($query),
        ];

        $stats = $this->cacheService->remember(
        'task_stats_' . date('Y-m-d'),
        function () use ($request) {
            $builder = new TaskQueryBuilder(Task::query(), $request->user());
            $query = $builder->forCurrentUser();
            
            return [
                'total_tasks' => $query->count(),
                'preparation_tasks' => $query->preparation()->count(),
                'active_tasks' => $query->active()->count(),
                'completed_tasks' => $query->completed()->count(),
                'cancelled_tasks' => $query->cancelled()->count(),
                'overdue_tasks' => $query->overdue()->count(),
                'avg_completion_time' => $this->calculateAvgCompletionTime($query),
            ];
        },
        ['tasks', 'stats', 'user_' . $request->user()->id],3600 );

        return $this->success($stats, 'messages.success');
    }

    /**
     * Send notifications when task status changes.
     *
     * @param Task $task
     * @param string $oldStatus Previous status
     * @return void
     */
    private function sendStatusChangeNotifications(Task $task, string $oldStatus): void
    {
        try {
            if ($task->application->volunteer) {
                $task->application->volunteer->notify(
                    new TaskStatusChanged($task, $oldStatus, $task->status)
                );
            }

            if ($task->application->coordinator) {
                $task->application->coordinator->notify(
                    new TaskStatusChanged($task, $oldStatus, $task->status)
                );
            }

            activity()
                ->performedOn($task)
                ->causedBy(auth()->user())
                ->withProperties([
                    'old_status' => $oldStatus,
                    'new_status' => $task->status
                ])
                ->log('The task status has been changed');

        } catch (Exception $e) {
            Log::error('Failed to send task status notifications', [
                'task_id' => $task->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Calculate average completion time for tasks.
     *
     * @param mixed $query Task query builder
     * @return string|null Average days or null if no completed tasks
     */
    private function calculateAvgCompletionTime($query): ?string
    {
        $completedTasks = $query->completed()
            ->whereNotNull('created_at')
            ->whereNotNull('completed_at')
            ->get();

        if ($completedTasks->isEmpty()) {
            return null;
        }

        $totalDays = $completedTasks->sum(function ($task) {
            return $task->created_at->diffInDays($task->completed_at);
        });

        $avgDays = $totalDays / $completedTasks->count();

        return round($avgDays, 1) . ' day';
    }

}
