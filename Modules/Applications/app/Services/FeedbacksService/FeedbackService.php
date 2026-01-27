<?php

namespace Modules\Applications\Services\FeedbacksService;

use Modules\Applications\Interfaces\ModuleApplicationsInterface;
use Modules\Applications\Transformers\FeedbackResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class FeedbackService implements ModuleApplicationsInterface
{
    public function __construct(
        private ModuleApplicationsInterface $repository
    ){}


    public function getAllFeedbacks(array $filters = []): AnonymousResourceCollection
    {
        $query = Feedback::with(['opportunity', 'volunteer', 'coordinator']);

        $this->applyFilters($query, $filters);
        
        $perPage = $filters['per_page']?? 15;
        
        $feedbacks = $query->paginate($perPage);
        
        return FeedbackResource::collection($feedbacks);
    }

    public function getFeedback(int $id): FeedbackResource
    {
        $feedback = Feedback::with(['tasks'])->find($id);

        if (!$feedback)
        {   
            throw new ModelNotFoundException('Feedback not found');
        }

        return new FeedbackResource ($feedback);
    }

    public function createFeedback(array $data): FeedbackResource
    {
        $feedback = Feedback::create($data);
        return new FeedbackResource ($feedback);
    }

    public function updateFeedback(int $id, array $data): FeedbackResource
    {
        $feedback = Feedback::find($id);

        if (!$feedback)
        {   
            throw new ModelNotFoundException('Feedback not found');
        }
        
        $feedback->update($data);

        return new FeedbackResource ($feedback->fresh());
    }

    public function deleteFeedback(int $id): bool
    {
        $feedback = Feedback::find($id);
        
        if (!$feedback)
        {   
            throw new ModelNotFoundException('Feedback not found');
        }
        return $feedback->delete();
    }

    public function updateFeedbackStatus(int $id, string $status): ApplicationResource
    {
       $feedback = Feedback::find($id);

        if (!$feedback)
        {   
            throw new ModelNotFoundException('Feedback not found');
        }

         $feedback->update(['status' => $status]);

        return new FeedbackResource ($feedback->fresh());
    }

    private function applyFilters($query, array $filters): void
    {
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['volunteer_id'])) {
            $query->where('volunteer_id', $filters['volunteer_id']);
        }

       
        if (isset($filters['opportunity_id'])) {
            $query->where('opportunity_id', $filters['opportunity_id']);
        }
        
         if (isset($filters['coordinator_id'])) {
            $query->where('coordinator_id', $filters['coordinator_id']);
        }

          if (isset($filters['from_date'])) {
            $query->whereDate('created_at', '>=', $filters['from_date']);
        }
        
        if (isset($filters['to_date'])) {
            $query->whereDate('created_at', '<=', $filters['to_date']);
        }

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('description', 'like', '%' . $filters['search'] . '%');
            });
        }
        
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);
    }
    
}
