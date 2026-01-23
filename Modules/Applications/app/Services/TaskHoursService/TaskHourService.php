<?php

namespace Modules\Applications\Services\TaskHoursService;

use Modules\Applications\Interfaces\ModuleApplicationsInterface;
use Modules\Applications\Transformers\TaskHourResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class TaskHourService implements ModuleApplicationsInterface
{
    public function __construct(
        private ModuleApplicationsInterface $repository
    ){}


    public function getAllTaskHours(array $filters = []): AnonymousResourceCollection
    {
        $taskHours = $this->repository->getAll($filters);
        return TaskHourResource::collection($taskHours);
    }

    public function getTaskHour(int $id): TaskHourResource
    {
        $taskHour = $this->repository->find($id);
        return new TaskHourResource($taskHour);
    } 

    public function createTaskHour(array $data):TaskHourResource
    {
        $taskHour = $this->repository->create($data);
        return new TaskHourResource($taskHour);
    }

    public function updateTaskHour(int $id, array $data): TaskHourResource
    {
        $this->repository->update($id, $data);
        return $this->getTaskHour($id);
    }

    public function deleteTaskHour(int $id): bool
    {
        return $this->repository->delete($id);
    }
    
}
