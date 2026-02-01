<?php

namespace Modules\Applications\Services;

use Modules\Applications\Models\TaskHour;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Task hour Service
 * 
 * Business logic layer for task hour operations.
 * Handles task hour management, hour logging, and performance calculations.
 * 
 * @package Modules\Applications\Services
 * @author Your Name
 */
class TaskHourService
{
    /**
     * Create a new task.
     * 
     * @param array $data Task hour data
     * @return TaskHour
     */
    public function createTaskHour(array $data): TaskHour
    {
        return TaskHour::create($data);
    }

    /**
     * Update an existing task.
     * 
     * @param int $id Task hour ID
     * @param array $data Updated data
     * @return TaskHour
     */
    public function updateTaskHour(int $id, array $data): TaskHour
    {
        $taskHour = TaskHour::findOrFail($id);
        $taskHour->update($data);
        return $taskHour->fresh();
    }

    /**
     * Delete task status and handle completion logic.
     * 
     * @param int $id Task hour ID
     * @return TaskHour
     */
    public function deleteTaskHour(int $id): bool
    {
        $taskHour = TaskHour::findOrFail($id);
        return $taskHour->delete();
    }

    
    public function validateNoOverlap(array $data, ?int $excludeId = null): bool
    {
        $query = TaskHour::where('task_id', $data['task_id'])
            ->where(function($q) use ($data) {
                $q->whereBetween('started_date', [$data['started_date'], $data['ended_date']])
                  ->orWhereBetween('ended_date', [$data['started_date'], $data['ended_date']])
                  ->orWhere(function($q2) use ($data) {
                      $q2->where('started_date', '<=', $data['started_date'])
                         ->where('ended_date', '>=', $data['ended_date']);
                  });
            });
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return !$query->exists();
    }

    
    public function getHoursStats(array $filters = []): array
    {
        $query = TaskHour::query();
        
        if (isset($filters['task_id'])) {
            $query->where('task_id', $filters['task_id']);
        }
        
        if (isset($filters['volunteer_profile_id'])) {
            $query->whereHas('task.application', function($q) use ($filters) {
                $q->where('volunteer_profile_id', $filters['volunteer_profile_id']);
            });
        }
        
        if (isset($filters['from_date'])) {
            $query->whereDate('started_date', '>=', $filters['from_date']);
        }
        
        if (isset($filters['to_date'])) {
            $query->whereDate('ended_date', '<=', $filters['to_date']);
        }
        
        $totalHours = $query->sum('hours');
        $recordsCount = $query->count();
        $avgHours = $recordsCount > 0 ? $totalHours / $recordsCount : 0;
        
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->translatedFormat('F Y');
            
            $monthHours = TaskHour::whereYear('started_date', $date->year)
                ->whereMonth('started_date', $date->month)
                ->when(isset($filters['volunteer_profile_id']), function($query) use ($filters) {
                    $query->whereHas('task.application', function($q) use ($filters) {
                        $q->where('volunteer_profile_id', $filters['volunteer_profile_id']);
                    });
                })
                ->sum('hours');
            
            $monthlyStats[$monthName] = $monthHours;
        }
        
        return [
            'total_hours' => $totalHours,
            'total_records' => $recordsCount,
            'average_hours_per_record' => round($avgHours, 1),
            'monthly_stats' => $monthlyStats,
            'hours_by_task' => $this->getHoursByTask($filters),
        ];
    }

    
    private function getHoursByTask(array $filters): array
    {
        $query = TaskHour::with(['task'])
            ->selectRaw('task_id, SUM(hours) as total_hours')
            ->groupBy('task_id')
            ->orderByDesc('total_hours')
            ->limit(10);
        
        if (isset($filters['volunteer_profile_id'])) {
            $query->whereHas('task.application', function($q) use ($filters) {
                $q->where('volunteer_profile_id', $filters['volunteer_profile_id']);
            });
        }
        
        if (isset($filters['from_date'])) {
            $query->whereDate('started_date', '>=', $filters['from_date']);
        }
        
        if (isset($filters['to_date'])) {
            $query->whereDate('ended_date', '<=', $filters['to_date']);
        }
        
        return $query->get()->map(function($item) {
            return [
                'task_id' => $item->task_id,
                'task_title' => $item->task->title ?? 'Unknown',
                'total_hours' => $item->total_hours,
            ];
        })->toArray();
    }

    
    public function getHoursReport(array $filters): array
    {
        $query = TaskHour::with(['task.application.volunteer', 'task.application.coordinator']);
        
        if (isset($filters['task_id'])) {
            $query->where('task_id', $filters['task_id']);
        }
        
        if (isset($filters['volunteer_profile_id'])) {
            $query->whereHas('task.application', function($q) use ($filters) {
                $q->where('volunteer_profile_id', $filters['volunteer_profile_id']);
            });
        }
        
        if (isset($filters['coordinator_id'])) {
            $query->whereHas('task.application', function($q) use ($filters) {
                $q->where('coordinator_id', $filters['coordinator_id']);
            });
        }
        
        if (isset($filters['from_date'])) {
            $query->whereDate('started_date', '>=', $filters['from_date']);
        }
        
        if (isset($filters['to_date'])) {
            $query->whereDate('ended_date', '<=', $filters['to_date']);
        }
        
        $hours = $query->orderBy('started_date', 'desc')->get();
        
        $summary = [
            'total_records' => $hours->count(),
            'total_hours' => $hours->sum('hours'),
            'average_hours_per_day' => $this->calculateAvgHoursPerDay($hours),
            'volunteers_count' => $hours->groupBy('task.application.volunteer_profile_id')->count(),
            'tasks_count' => $hours->groupBy('task_id')->count(),
        ];
        
        return [
            'summary' => $summary,
            'hours' => $hours,
            'breakdown' => $this->getBreakdown($hours),
        ];
    }

    
    private function calculateAvgHoursPerDay($hours): float
    {
        $uniqueDays = $hours->groupBy(function($item) {
            return $item->started_date->format('Y-m-d');
        })->count();
        
        if ($uniqueDays === 0) {
            return 0.0;
        }
        
        return round($hours->sum('hours') / $uniqueDays, 1);
    }

    
    private function getBreakdown($hours): array
    {
        return [
            'by_volunteer' => $hours->groupBy('task.application.volunteer.name')->map(function($group) {
                return [
                    'total_hours' => $group->sum('hours'),
                    'records' => $group->count(),
                ];
            }),
            'by_task' => $hours->groupBy('task.title')->map(function($group) {
                return [
                    'total_hours' => $group->sum('hours'),
                    'records' => $group->count(),
                ];
            }),
            'by_date' => $hours->groupBy(function($item) {
                return $item->started_date->format('Y-m-d');
            })->map(function($group) {
                return [
                    'total_hours' => $group->sum('hours'),
                    'records' => $group->count(),
                ];
            }),
        ];
    }
}