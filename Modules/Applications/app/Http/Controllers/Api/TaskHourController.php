<?php

namespace Modules\Applications\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Modules\Applications\Models\TaskHour;
use Illuminate\Validation\ValidationException;
use Modules\Applications\Services\AuditService;
use Modules\Applications\Services\TaskHourService;
use Modules\Applications\QueryBuilders\TaskHourQueryBuilder;
use Modules\Applications\Http\Requests\TaskHoursRequest\IndexTaskHourRequest;
use Modules\Applications\Http\Requests\TaskHoursRequest\StoreTaskHourRequest;
use Modules\Applications\Http\Requests\TaskHoursRequest\UpdateTaskHourRequest;

/**
 * TaskHour Controller
 *
 * Handles task hour logging operations including
 * creation, retrieval, updating, and reporting.
 *
 * @package Modules\Applications\Http\Controllers
 * @author Your Name
 */
class TaskHourController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param TaskHourService $service
     * @param AuditService $auditService
     */
    public function __construct(private TaskHourService $service, private AuditService $auditService)
    {
    }

    /**
     * Display a listing of task hours.
     *
     * @param IndexTaskHourRequest $request
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @api GET /api/task-hours
     * @permission viewAny taskHours
     */
    public function index(IndexTaskHourRequest $request): JsonResponse
    {
        $this->authorize('viewAny', TaskHour::class);

        $builder = new TaskHourQueryBuilder(TaskHour::query(), $request->user());
        $query = $builder->forCurrentUser()->with(['task.application']);

        if ($request->filled('task_id')) {
            $query->forTask($request->task_id);
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->betweenDates($request->from_date, $request->to_date);
        }

        if ($request->filled('min_hours')) {
            $query->where('hours', '>=', $request->min_hours);
        }

        if ($request->filled('max_hours')) {
            $query->where('hours', '<=', $request->max_hours);
        }

        $hours = $query->latest()->paginate($request->get('per_page', 15));

        return $this->paginated($hours, 'messages.success');
    }

    /**
     * Store a newly created taskHour.
     *
     * @param StoreTaskHourRequest $request
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @api POST /api/taskHours
     * @permission create taskHours
     */
    public function store(StoreTaskHourRequest $request): JsonResponse
    {
        $this->authorize('create', TaskHour::class);

        $this->checkDateOverlap($request->validated());

        $taskHour = TaskHour::create($request->validated());

        $this->auditService->log('created', 'TaskHour', auth()->user(), [
        'task_hour_id' => $taskHour->id,
        'task_id' => $taskHour->task_id,
        'hours' => $taskHour->hours,
        'started_date' => $taskHour->started_date,
        'ended_date' => $taskHour->ended_date,
        'volunteer_profile_id' => $taskHour->task->application->volunteer_profile_id ?? null
        ]);

        return $this->success($taskHour, 'messages.taskHour_created', 201);
    }

    /**
     * Display the specified taskHour.
     *
     * @param int $id TaskHour ID
     * @return JsonResponse
     * @throws ModelNotFoundException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @api GET /api/taskHours/{id}
     * @permission view taskHours
     */
    public function show(int $id): JsonResponse
    {
        $taskHour = TaskHour::with(['task.application.volunteer', 'task.application.coordinator'])
                            ->findOrFail($id);

        $this->authorize('view', $taskHour);

        return $this->success($taskHour, 'messages.success');
    }


    /**
 * Update the specified task hour.
 *
 * @param UpdateTaskHourRequest $request
 * @param int $id TaskHour ID
 * @return JsonResponse
 * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
 * @throws \Illuminate\Auth\Access\AuthorizationException
 * @throws \Illuminate\Validation\ValidationException
 *
 * @api PUT /api/task-hours/{id}
 * @permission update taskHours
 */
    public function update(UpdateTaskHourRequest $request, int $id): JsonResponse
    {
        $taskHour = TaskHour::findOrFail($id);
        $this->authorize('update', $taskHour);

        $this->checkDateOverlap($request->validated(), $taskHour->id);

        $taskHour->update($request->validated());

        return $this->success($taskHour, 'messages.taskHour_updated');
    }

    /**
     * Remove the specified task hour.
     *
     * @param int $id TaskHour ID
     * @return JsonResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @api DELETE /api/task-hours/{id}
     * @permission delete taskHours
     */
    public function destroy(int $id): JsonResponse
    {
        $taskHour = TaskHour::findOrFail($id);
        $this->authorize('delete', $taskHour);

        $taskHour->delete();

        return $this->success(null, 'messages.taskHour_deleted');
    }

    /**
     * Get task hour statistics.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @api GET /api/task-hours/stats
     * @permission viewAny taskHours
     */
    public function stats(Request $request): JsonResponse
    {
        $this->authorize('viewAny', TaskHour::class);

        $builder = new TaskHourQueryBuilder(TaskHour::query(), $request->user());
        $query = $builder->forCurrentUser();

        $stats = [
            'total_hours' => $query->sum('hours'),
            'hours_this_month' => $query->whereMonth('started_date', now()->month)
                                        ->sum('hours'),
            'hours_this_week' => $query->whereBetween('started_date', [
                                        now()->startOfWeek(), now()->endOfWeek()
                                        ])->sum('hours'),
            'avg_hours_per_day' => $this->calculateAvgHoursPerDay($query),
            'total_days_worked' => $query->distinct('started_date')->count('started_date'),
        ];

        return $this->success($stats, 'messages.success');
    }

    /**
     * Check for date overlap in task hours.
     *
     * @param array $data Task hour data
     * @param int|null $excludeId TaskHour ID to exclude from check
     * @return void
     * @throws \Illuminate\Validation\ValidationException If overlap exists
     */
    private function checkDateOverlap(array $data, ?int $excludeId = null): void
    {
        $query = TaskHour::where('task_id', $data['task_id'])
            ->where(function ($q) use ($data) {
                $q->whereBetween('started_date', [$data['started_date'], $data['ended_date']])
                  ->orWhereBetween('ended_date', [$data['started_date'], $data['ended_date']])
                  ->orWhere(function ($q2) use ($data) {
                      $q2->where('started_date', '<=', $data['started_date'])
                         ->where('ended_date', '>=', $data['ended_date']);
                  });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        if ($query->exists()) {
            throw new ValidationException([
                'started_date' => ['There is an overlap in the recorded hours']
            ]);
        }
    }

    /**
     * Calculate average hours per day.
     *
     * @param mixed $query TaskHour query builder
     * @return float Average hours per day
     */
    private function calculateAvgHoursPerDay($query): float
    {
        $totalHours = $query->sum('hours');
        $daysWorked = $query->distinct('started_date')->count('started_date');

        if ($daysWorked === 0) {
            return 0.0;
        }

        return round($totalHours / $daysWorked, 1);
    }
}
