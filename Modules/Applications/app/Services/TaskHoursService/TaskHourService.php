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
        $query = TaskHour::with(['application.opportunity', 'application.volunteer', 'application.coordinator']);

        $this->applyFilters($query, $filters);
        
        $perPage = $filters['per_page']?? 15;
        
        $taskHours = $query->paginate($perPage);
        
        return TaskHourResource::collection($taskHours);
    }

    public function getTaskHour(int $id): TaskHourResource
    {
        $taskHour = TaskHour::with([ 'tasks'])->find($id);

        if (!$taskHour)
        {   
            throw new ModelNotFoundException('TaskHour not found');
        }

        return new TaskHourResource ($taskHour);
    }

    public function createTaskHour(array $data): TaskHourResource
    {
        $taskHour = TaskHour::create($data);
        return new TaskHourResource ($taskHour);
    }

    public function updateTaskHour(int $id, array $data): TaskHourResource
    {
        $taskHour = TaskHour::find($id);

        if (!$taskHour)
        {   
            throw new ModelNotFoundException('TaskHour not found');
        }
        
        $taskHour->update($data);

        return new TaskHourResource ($taskHour->fresh());
    }

    public function deleteTask(int $id): bool
    {
        $task = Task::find($id);
        
        if (!$taskHour)
        {   
            throw new ModelNotFoundException('TaskHour not found');
        }
        return $taskHour->delete();
    }

    public function updateTaskStatus(int $id, string $status): TaskHourResource
    {
       $taskHour = TaskHour::find($id);

        if (!$taskHour)
        {   
            throw new ModelNotFoundException('TaskHour not found');
        }

         $taskHour->update(['status' => $status]);

        return new TaskHourResource ($taskHour->fresh());
    }

    private function applyFilters($query, array $filters): void
    {
        if (isset($filters['application_id'])) {
            $query->where('application_id', $filters['application_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['volunteer_id'])) {
            $query->where('volunteer_id', $filters['volunteer_id']);
        }

          if (isset($filters['started_date_from'])) {
            $query->whereDate('created_at', '>=', $filters['started_date_from']);
        }
        
        if (isset($filters['started_date_to'])) {
            $query->whereDate('created_at', '<=', $filters['started_date_to']);
        }

        if (isset($filters['ended_date_from'])) {
            $query->whereDate('created_at', '>=', $filters['ended_date_from']);
        }
        
        if (isset($filters['ended_date_to'])) {
            $query->whereDate('created_at', '<=', $filters['ended_date_to']);
        }

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }
        
        $sortBy = $filters['sort_by'] ?? 'due_date';
        $sortOrder = $filters['sort_order'] ?? 'asc';
        $query->orderBy($sortBy, $sortOrder);
    }
    
}
