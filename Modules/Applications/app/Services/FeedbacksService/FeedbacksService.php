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
        $feedbacks = $this->repository->getAll($filters);
        return FeedbackResource::collection($feedbacks);
    }

    public function getFeedback(int $id): FeedbackResource
    {
        $feedback = $this->repository->find($id);
        return new FeedbackResource($feedback);
    } 

    public function createFeedback(array $data): FeedbackResource
    {
        $feedback = $this->repository->create($data);
        return new FeedbackResource($feedback);
    }

    public function updateFeedback(int $id, array $data): FeedbackResource
    {
        $this->repository->update($id, $data);
        return $this->getFeedback($id);
    }

    public function deleteFeedback(int $id): bool
    {
        return $this->repository->delete($id);
    }
    
}
