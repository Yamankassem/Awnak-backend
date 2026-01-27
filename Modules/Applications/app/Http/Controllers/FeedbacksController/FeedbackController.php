<?php

namespace Modules\Applications\Http\Controllers\FeedbacksController;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Modules\Applications\Models\Feedback;
use Modules\Applications\Services\FeedbacksService\FeedbackService;
use Modules\Applications\Http\Requests\FeedbacksRequest\StoreFeedbackRequest;
use Modules\Applications\Http\Requests\FeedbacksRequest\UpdateFeedbackRequest;
use Modules\Applications\Notifications\FeedbacksNotification\NewFeedbackNotification;
use Modules\Applications\Http\Requests\FeedbacksRequest\IndexFeedbackRequest;

class FeedbackController extends Controller
{
    public function __construct(private FeedbackService $service) {}

    public function index(IndexFeedbackRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Feedback::class);
        $feedbacks = $this->service->getAllFeedbacks($request->validated());
        
        if ($feedbacks instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            return $this->paginated($feedbacks, 'feedback_list_retrieved');
        }
        
        return $this->success($feedbacks, 'feedback_list_retrieved');
    }

    public function store(StoreFeedbackRequest $request): JsonResponse
    {
        $this->authorize('create', Feedback::class);
        
        $feedback = $this->service->createFeedback($request->validated());
        
        $this->sendFeedbackNotification($feedback);
        
        return $this->success($feedback, 'messages.created_success', 201);
    }

    public function update(UpdateFeedbackRequest $request, int $id): JsonResponse
    {
        try {
            $feedback = $this->service->getFeedback($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('messages.feedback_not_found', 404);
        }

        $this->authorize('update', $feedback);
        
        $oldRating = $feedback->rating;
        $feedback = $this->service->updateFeedback($id, $request->validated());
        
        if ($feedback->wasChanged('rating')) {
            \Log::info('Feedback rating updated', [
                'feedback_id' => $feedback->id,
                'old_rating' => $oldRating,
                'new_rating' => $feedback->rating,
                'by' => auth()->id(),
            ]);
        }
        
        return $this->success($feedback, 'messages.updated_success');
    }
    
    public function show(int $id): JsonResponse
    {
        try {
            $feedback = $this->service->getFeedback($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('messages.feedback_not_found', 404);
        }

        $this->authorize('view', $feedback);
        return $this->success($feedback, 'messages.success');
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $feedback = $this->service->getFeedback($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('messages.feedback_not_found', 404);
        }

        $this->authorize('delete', $feedback);
        $this->service->deleteFeedback($id);

        return $this->success(null, 'messages.deleted_success');
    }
    
    
    private function sendFeedbackNotification(Feedback $feedback): void
    {
        try {
            $volunteer = $feedback->task->application->volunteer;
            $volunteer->notify(new NewFeedbackNotification($feedback));
            
            if ($feedback->task->application->coordinator) {
                $feedback->task->application->coordinator->notify(new NewFeedbackNotification($feedback));
            }
            
            \Log::info('Feedback notification sent', [
                'feedback_id' => $feedback->id,
                'rating' => $feedback->rating,
                'task_id' => $feedback->task_id,
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to send feedback notification', [
                'feedback_id' => $feedback->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}