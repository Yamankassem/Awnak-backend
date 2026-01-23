<?php

namespace Modules\Applications\Services\TasksService;

use Modules\Applications\Interfaces\ModuleApplicationsInterface;
use Modules\Applications\Transformers\TaskResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class TaskService implements ModuleApplicationsInterface
{
    public function __construct(
        private ModuleApplicationsInterface $repository
    ){}


    public function getAllTasks(array $filters = []): AnonymousResourceCollection
    {
        $tasks = $this->repository->getAll($filters);
        return TaskResource::collection($tasks);
    }

    public function getTask(int $id): TaskResource
    {
        $task = $this->repository->find($id);
        return new TaskResource($task);
    } 

    public function createTask(array $data):TaskResource
    {
        $task = $this->repository->create($data);
        return new TaskResource($task);
    }

    public function updateTask(int $id, array $data): TaskResource
    {
        $this->repository->update($id, $data);
        return $this->getTask($id);
    }

    public function deleteTask(int $id): bool
    {
        return $this->repository->delete($id);
    }
    
}
