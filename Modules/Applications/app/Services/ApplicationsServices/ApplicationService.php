<?php

namespace Modules\Applications\Services\ApplicationsService;

use Modules\Applications\Interfaces\ApplicationInterface;
use Modules\Applications\Transformers\ApplicationResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class ApplicationService implements ModuleApplicationsInterface
{
    public function __construct(
        private ApplicationInterface $repository
    ){}


    public function getAllApplications(array $filters = []): AnonymousResourceCollection
    {
        $applications = $this->repository->getAll($filters);
        return ApplicationResource::collection($applications);
    }

    public function getApplication(int $id): ApplicationResource
    {
        $application = $this->repository->find($id);
        return new ApplicationResource($application);
    } 

    public function createApplication(array $data): ApplicationResource
    {
        $application = $this->repository->create($data);
        return new ApplicationResource($application);
    }

    public function updateApplication(int $id, array $data): ApplicationResource
    {
        $this->repository->update($id, $data);
        return $this->getApplication($id);
    }

    public function deleteApplication(int $id): bool
    {
        return $this->repository->delete($id);
    }
    
}
