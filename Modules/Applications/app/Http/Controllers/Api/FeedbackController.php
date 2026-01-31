<?php

namespace Modules\Applications\Http\Controllers\Api;

use Log;
use Exception;
use Illuminate\Http\Request;
use Modules\Core\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Modules\Applications\Models\Task;
use Modules\Applications\Models\Feedback;
use Modules\Applications\Services\AuditService;
use Modules\Applications\Services\CacheService;
use Modules\Applications\Models\PerformanceMetric;
use Modules\Applications\Services\FeedbackService;
use Modules\Applications\QueryBuilders\FeedbackQueryBuilder;
use Modules\Applications\Notifications\NewFeedbackNotification;
use Modules\Applications\Http\Requests\FeedbacksRequest\IndexFeedbackRequest;
use Modules\Applications\Http\Requests\FeedbacksRequest\StoreFeedbackRequest;
use Modules\Applications\Http\Requests\FeedbacksRequest\UpdateFeedbackRequest;

/**
 * Feedback Controller
 * 
 * Handles feedback and performance evaluation operations
 * including creation, retrieval, reporting, and volunteer evaluations.
 * 
 * @package Modules\Applications\Http\Controllers
 * @author Your Name
 */
class FeedbackController extends Controller
{
    /**
     * Create a new controller instance.
     * 
     * @param FeedbackService $service
     * @param AuditService $auditService
     * @param CacheService $cacheService
     */
    public function __construct(private FeedbackService $service, private AuditService $auditService, private CacheService $cacheService) {}

    /**
     * Display a listing of feedbacks.
     * 
     * @param IndexFeedbackRequest $request
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * 
     * @api GET /api/feedbacks
     * @permission viewAny feedbacks
     */
    public function index(IndexFeedbackRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Feedback::class);

        $builder = new FeedbackQueryBuilder(Feedback::query(), $request->user());
        $query = $builder->forCurrentUser()->withAllRelations();

        if ($request->filled('type')) {
            if ($request->type === 'performance') {
                $query->performanceEvaluations();
            } elseif ($request->type === 'task_review') {
                $query->taskReviews();
            }
        }

        if ($request->filled('rating')) {
            $query->withRating($request->rating);
        }

        if ($request->filled('task_id')) {
            $query->where('task_id', $request->task_id);
        }

        if ($request->filled('organization')) {
            $query->where('name_of_org', 'like', "%{$request->organization}%");
        }

        if ($request->filled('volunteer')) {
            $query->where('name_of_vol', 'like', "%{$request->volunteer}%");
        }

        if ($request->filled('search')) {
            $query->where('comment', 'like', "%{$request->search}%");
        }

        $feedbacks = $query->latestFirst()->paginate($request->get('per_page', 15));

        return $this->paginated($feedbacks, 'messages.success');
    }

    /**
     * Store a newly created feedback.
     * 
     * @param StoreFeedbackRequest $request
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * 
     * @api POST /api/feedbacks
     * @permission create feedbacks
     */
    public function store(StoreFeedbackRequest $request): JsonResponse
    {
        $this->authorize('create', Feedback::class);

        $task = Task::find($request->task_id);
        if ($task && $task->status !== 'complete') {
            return $this->error('Only completed tasks can be evaluated.', 422);
        }

        if ($request->name_of_org) {
            $existingFeedback = Feedback::where('task_id', $request->task_id)
                ->where('name_of_org', $request->name_of_org)
                ->first();

            if ($existingFeedback) {
                return $this->error('This organization has previously provided an evaluation for this task.', 422);
            }
        }
        $feedbackData = $request->validated();
        unset($feedbackData['metrics']);

        $feedback = Feedback::create($feedbackData);
        
        $this->auditService->log('created', 'Feedback', auth()->user(), [
        'feedback_id' => $feedback->id,
        'task_id' => $feedback->task_id,
        'rating' => $feedback->rating,
        'volunteer_name' => $feedback->name_of_vol,
        'organization' => $feedback->name_of_org
         ]);

        if ($request->filled('metrics')) {
            foreach ($request->metrics as $metric) {
                PerformanceMetric::create([
                    'feedback_id' => $feedback->id,
                    'metric_name' => $metric['name'],
                    'score' => $metric['score'],
                    'notes' => $metric['notes'] ?? null
                ]);
            }

            $this->calculateAverageRating($feedback);
        }
        if ($feedback->isPerformanceEvaluation()) 
            $this->notifyVolunteer($feedback);

        activity()
            ->performedOn($feedback)
            ->causedBy(auth()->user())
            ->withProperties(['has_metrics' => $request->filled('metrics')])
            ->log('A new evaluation has been added' . ($request->filled('metrics') ? ' with performance metrics' : ''));

        return $this->success([
            'feedback' => $feedback,
            'metrics_count' => $feedback->performanceMetrics()->count(),
        ], 'messages.feedback_created', 201);
    }

    private function calculateAverageRating(Feedback $feedback): void
    {
        if ($feedback->performanceMetrics()->exists()) {
            $averageScore = $feedback->performanceMetrics()->avg('score');
            $feedback->update(['rating' => round($averageScore)]);
        }
    }

    /**
     * Display the specified feedback.
     * 
     * @param int $id Feedback ID
     * @return JsonResponse
     * @throws ModelNotFoundException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * 
     * @api GET /api/feedbacks/{id}
     * @permission view feedbacks
     */
    public function show(int $id): JsonResponse
    {
        $feedback = Feedback::with(['task.application.opportunity',
                                   'task.application.volunteer',
                                   'task.application.coordinator'])
                           ->findOrFail($id);

        $this->authorize('view', $feedback);

        return $this->success($feedback, 'messages.success');
    }

    /**
    * Update the specified feedback.
    * 
    * @param UpdateFeedbackRequest $request
    * @param int $id Feedback ID
    * @return JsonResponse
    * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
    * @throws \Illuminate\Auth\Access\AuthorizationException
    * 
    * @api PUT /api/feedbacks/{id}
    * @permission update feedbacks
    */
    public function update(UpdateFeedbackRequest $request, int $id): JsonResponse
    {
        $feedback = Feedback::findOrFail($id);
        $this->authorize('update', $feedback);

        $validated = $request->validate([
            'name_of_org' => 'sometimes|string|max:255',
            'name_of_vol' => 'sometimes|string|max:255',
            'rating' => 'sometimes|integer|min:1|max:5',
            'comment' => 'sometimes|string|min:10|max:1000',
        ]);

        $feedback->update($validated);

        return $this->success($feedback, 'messages.feedback_updated');
    }

    /**
     * Remove the specified feedback.
     * 
     * @param int $id Feedback ID
     * @return JsonResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * 
     * @api DELETE /api/feedbacks/{id}
     * @permission delete feedbacks
     */
    public function destroy(int $id): JsonResponse
    {
        $feedback = Feedback::findOrFail($id);
        $this->authorize('delete', $feedback);

        $feedback->delete();

        return $this->success(null, 'messages.feedback_deleted');
    }

    /**
    * Get volunteer performance evaluations.
    * 
    * @param Request $request
    * @param string $volunteerName Volunteer name
    * @return JsonResponse
    * @throws \Illuminate\Auth\Access\AuthorizationException
    * 
    * @api GET /api/feedbacks/volunteer/{volunteerName}/performance
    * @permission viewAny feedbacks
    */
    public function volunteerPerformance(Request $request, string $volunteerName): JsonResponse
    {
        $this->authorize('viewAny', Feedback::class);

        $builder = new FeedbackQueryBuilder(Feedback::query(), $request->user());
        $evaluations = $builder->forCurrentUser()
            ->performanceEvaluations()
            ->where('name_of_vol', $volunteerName)
            ->withAllRelations()
            ->latestFirst()
            ->paginate($request->get('per_page', 15));

        return $this->paginated($evaluations, 'messages.success');
    }

    /**
    * Get task reviews.
    * 
    * @param Request $request
    * @return JsonResponse
    * @throws \Illuminate\Auth\Access\AuthorizationException
    * 
    * @api GET /api/feedbacks/task-reviews
    * @permission viewAny feedbacks
    */
    public function taskReviews(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Feedback::class);

        $builder = new FeedbackQueryBuilder(Feedback::query(), $request->user());
        $reviews = $builder->forCurrentUser()
            ->taskReviews()
            ->withAllRelations()
            ->latestFirst()
            ->paginate($request->get('per_page', 15));

        return $this->paginated($reviews, 'messages.success');
    }

    /**
    * Get feedback for a specific task.
    * 
    * @param int $taskId Task ID
    * @return JsonResponse
    * 
    * @api GET /api/feedbacks/task/{taskId}
    */
    public function taskFeedback(int $taskId): JsonResponse
    {
        $feedbacks = Feedback::where('task_id', $taskId)
            ->with(['task.application'])
            ->latest()
            ->get();

        return $this->success($feedbacks, 'messages.success');
    }

    /**
     * Generate volunteer performance report.
     * 
     * @param Request $request
     * @param string $volunteerName Volunteer name
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * 
     * @api GET /api/feedbacks/volunteer/{volunteerName}/report
     * @permission viewPerformanceReports feedbacks
     */
    public function volunteerReport(Request $request, string $volunteerName): JsonResponse
    {
        $this->authorize('viewAny', Feedback::class);

        $builder = new FeedbackQueryBuilder(Feedback::query(), $request->user());
        $query = $builder->forCurrentUser()
            ->performanceEvaluations()
            ->where('name_of_vol', $volunteerName);

        $evaluations = $query->withAllRelations()->latestFirst()->get();

        $report = $this->cacheService->remember(
        'volunteer_report_' . $volunteerName . '_' . date('Y-m'),
        function () use ($request, $volunteerName) {
            $builder = new FeedbackQueryBuilder(Feedback::query(), $request->user());
            $query = $builder->forCurrentUser()
                ->performanceEvaluations()
                ->where('name_of_vol', $volunteerName);
            
            $evaluations = $query->withAllRelations()->latestFirst()->get();
            
            if ($evaluations->isEmpty()) {
                throw new Exception('No evaluations found');
            }
            
            return [
                'volunteer_name' => $volunteerName,
                'total_evaluations' => $evaluations->count(),
                'average_rating' => round($evaluations->avg('rating'), 1),
                'latest_evaluation' => $evaluations->first(),
                'strengths' => $this->extractStrengths($evaluations),
                'improvement_areas' => $this->extractImprovementAreas($evaluations),
                'organizations_evaluated' => $evaluations->groupBy('name_of_org')->count(),
                'evaluations_over_time' => $this->getEvaluationsOverTime($evaluations),
            ];
        },
        ['feedbacks', 'reports', 'volunteer_' . $volunteerName],7200 );

        return $this->success($report, 'messages.success');
    }

    /**
    * Notify volunteer about performance evaluation.
    * 
    * @param Feedback $feedback
    * @return void
    */
    private function notifyVolunteer(Feedback $feedback): void
    {
        try {
            Log::info('The volunteer performance has been evaluated', [
                'volunteer_name' => $feedback->name_of_vol,
                'organization' => $feedback->name_of_org,
                'rating' => $feedback->rating,
                'task_id' => $feedback->task_id,
            ]);

            $volunteer = User::where('name', $feedback->name_of_vol)->first();
            if ($volunteer) {
                $volunteer->notify(new NewFeedbackNotification($feedback));
            }

        } catch (Exception $e) {
            Log::error('Failed to send performance evaluation notification', [
                'feedback_id' => $feedback->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
