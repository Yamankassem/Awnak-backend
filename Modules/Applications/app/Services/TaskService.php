<?php

namespace Modules\Applications\Services;

use Modules\Applications\Models\Task;
use Modules\Applications\Models\Feedback;
use Modules\Applications\Models\TaskHour;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Task Service
 * 
 * Business logic layer for task operations.
 * Handles task management, hour logging, and performance calculations.
 * 
 * @package Modules\Applications\Services
 * @author Your Name
 */
class TaskService
{
    /**
     * Create a new task.
     * 
     * @param array $data Task data
     * @return Task
     */
    public function createTask(array $data): Task
    {
        return Task::create($data);
    }

    /**
     * Update an existing task.
     * 
     * @param int $id Task ID
     * @param array $data Updated data
     * @return Task
     * @throws ModelNotFoundException
     */
    public function updateTask(int $id, array $data): Task
    {
        $task = Task::findOrFail($id);
        $task->update($data);
        return $task->fresh();
    }

    /**
     * Update task status and handle completion logic.
     * 
     * @param int $id Task ID
     * @param string $status New status
     * @return Task
     * @throws ModelNotFoundException
     */
    public function updateTaskStatus(int $id, string $status): Task
    {
        $task = Task::findOrFail($id);
        $task->update(['status' => $status]);
        
        if ($status === 'complete' && !$task->completed_at) {
            $task->update(['completed_at' => now()]);
        }
        
        return $task->fresh();
    }

    /**
     * Delete task status and handle completion logic.
     * 
     * @param int $id Task ID
     * @return Task
     */
    public function deleteTask(int $id): bool
    {
        $task = Task::findOrFail($id);
        return $task->delete();
    }

    /**
     * Log hours for a task with overlap validation.
     * 
     * @param int $taskId Task ID
     * @param array $data Hour data
     * @return TaskHour
     * @throws ValidationException If hours overlap
     */
    public function logHours(int $taskId, array $data): TaskHour
    {
        $this->validateHourOverlap($taskId, $data);
        
        return TaskHour::create(array_merge($data, ['task_id' => $taskId]));
    }

    /**
     * Add feedback for a task with overlap validation.
     * 
     * @param int $taskId Task ID
     * @param array $data Add feedback data
     * @return TaskHour
     */
    public function addFeedback(int $taskId, array $data): Feedback
    {
        return Feedback::create(array_merge($data, ['task_id' => $taskId]));
    }

    
    private function validateHourOverlap(int $taskId, array $data): void
    {
        $overlap = TaskHour::where('task_id', $taskId)
            ->where(function($query) use ($data) {
                $query->whereBetween('started_date', [$data['started_date'], $data['ended_date']])
                      ->orWhereBetween('ended_date', [$data['started_date'], $data['ended_date']])
                      ->orWhere(function($q) use ($data) {
                          $q->where('started_date', '<=', $data['started_date'])
                            ->where('ended_date', '>=', $data['ended_date']);
                      });
            })
            ->exists();
        
        if ($overlap) {
            throw new ValidationException([
                'started_date' => ['There is an overlap in the recorded times.']
            ]);
        }
    }

   
    public function getStats(array $filters = []): array
    {
        $query = Task::query();
        
        if (isset($filters['application_id'])) {
            $query->where('application_id', $filters['application_id']);
        }
        
        if (isset($filters['volunteer_id'])) {
            $query->whereHas('application', function($q) use ($filters) {
                $q->where('volunteer_id', $filters['volunteer_id']);
            });
        }
        
        if (isset($filters['from_date'])) {
            $query->whereDate('created_at', '>=', $filters['from_date']);
        }
        
        if (isset($filters['to_date'])) {
            $query->whereDate('created_at', '<=', $filters['to_date']);
        }
        
        return [
            'total_tasks' => $query->count(),
            'preparation_tasks' => $query->where('status', 'preparation')->count(),
            'active_tasks' => $query->where('status', 'active')->count(),
            'completed_tasks' => $query->where('status', 'complete')->count(),
            'cancelled_tasks' => $query->where('status', 'cancelled')->count(),
            'overdue_tasks' => $query->where('status', 'active')
                ->whereDate('due_date', '<', now())
                ->count(),
            'total_hours' => $query->withSum('taskHours', 'hours')->get()->sum('task_hours_sum_hours'),
        ];
    }

    /**
     * Get volunteer performance statistics.
     * 
     * @param int $volunteerId Volunteer ID
     * @return array Performance metrics
     */
    public function getVolunteerPerformance(int $volunteerId): array
    {
        $tasks = Task::whereHas('application', function($query) use ($volunteerId) {
            $query->where('volunteer_id', $volunteerId);
        })->with(['taskHours', 'feedbacks'])->get();
        
        $totalTasks = $tasks->count();
        $completedTasks = $tasks->where('status', 'complete')->count();
        $totalHours = $tasks->sum(function($task) {
            return $task->taskHours->sum('hours');
        });
        $averageRating = $tasks->avg(function($task) {
            return $task->feedbacks->avg('rating');
        }) ?? 0;
        
        return [
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks,
            'completion_rate' => $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0,
            'total_hours' => $totalHours,
            'average_rating' => round($averageRating, 1),
            'average_hours_per_task' => $completedTasks > 0 ? round($totalHours / $completedTasks, 1) : 0,
            'tasks_by_status' => [
                'preparation' => $tasks->where('status', 'preparation')->count(),
                'active' => $tasks->where('status', 'active')->count(),
                'complete' => $completedTasks,
                'cancelled' => $tasks->where('status', 'cancelled')->count(),
                'overdue' => $tasks->where('status', 'active')
                    ->where('due_date', '<', now())
                    ->count(),
            ],
        ];
    }
}