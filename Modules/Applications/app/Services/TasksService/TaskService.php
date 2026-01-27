<?php

namespace Models\Applications\Services\TasksService; 

use Illuminate\Support\Facades\Cache;
use Modules\Applications\Models\Task;
use Modules\Applications\Transformers\TaskResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskService 
{
    public function __construct() {}

    public function getAllTasks(array $filters = []): AnonymousResourceCollection
    {
        $query = Task::with(['application.opportunity', 'application.volunteer', 'application.coordinator']);

        $this->applyFilters($query, $filters);
        
        $perPage = $filters['per_page']?? 15;
        
        $tasks = $query->paginate($perPage);
        
        return TaskResource::collection($tasks);
    }

    public function getTask(int $id): TaskResource
    {
        $task = Task::with(['applications', 'taskHours', 'feedbacks'])->find($id);

        if (!$task)
        {   
            throw new ModelNotFoundException('Task not found');
        }

        return new TaskResource ($task);
    }

    public function createTask(array $data): TaskResource
    {
        $task = Task::create($data);
        return new TaskResource ($task);
    }

    public function updateTask(int $id, array $data): TaskResource
    {
        $task = Task::find($id);

        if (!$task)
        {   
            throw new ModelNotFoundException('Task not found');
        }
        
        $task->update($data);

        return new TaskResource ($task->fresh());
    }

    public function deleteTask(int $id): bool
    {
        $task = Task::find($id);
        
        if (!$task)
        {   
            throw new ModelNotFoundException('Task not found');
        }
        return $task->delete();
    }

    public function updateTaskStatus(int $id, string $status): TaskResource
    {
       $task = Task::find($id);

        if (!$task)
        {   
            throw new ModelNotFoundException('Task not found');
        }

         $task->update(['status' => $status]);

        return new TaskResource ($task->fresh());
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

          if (isset($filters['due_date_from'])) {
            $query->whereDate('created_at', '>=', $filters['due_date_from']);
        }
        
        if (isset($filters['due_date_to'])) {
            $query->whereDate('created_at', '<=', $filters['due_date_to']);
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


    public function getVolunteerTasks($volunteerId)
    {
        $cacheKey = "volunteer_tasks_{$volunteerId}_" . date('Y-m-d-H');
        
        return Cache::remember($cacheKey, 300, function () use ($volunteerId) {
            return Task::whereHas('application', function ($query) use ($volunteerId) {
                $query->where('volunteer_id', $volunteerId);
            })
            ->with(['application.opportunity'])
            ->orderBy('due_date')
            ->get();
        });
    }
}