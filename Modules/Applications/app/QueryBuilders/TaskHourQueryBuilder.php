<?php

namespace Modules\Applications\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;
use Modules\Applications\Models\TaskHour;
use Modules\Core\Models\User;

/**
 * Task hour Query Builder
 * 
 * Provides fluent query building methods for task hour queries
 * with role-based filtering and common scopes.
 * 
 * @package Modules\Applications\QueryBuilders
 * @author Your Name
 * 
 * @method self forVolunteer(int $volunteerId)
 * @method self forCoordinator(int $coordinatorId)
 * @method self active()
 * @method self completed()
 * @method self overdue()
 */
class TaskHourQueryBuilder extends Builder
{
    /**
     * Current authenticated user.
     * 
     * @var User|null
     */
    private ?User $currentUser = null;

    /**
     * Create a new query builder instance.
     * 
     * @param \Illuminate\Database\Query\Builder $query
     * @param User|null $user
     */
    public function __construct($query, ?User $user = null)
    {
        parent::__construct($query);
        $this->currentUser = $user;
    }

    /**
     * Filter task hours for current user based on their role.
     * 
     * @return self
     */
    public function forCurrentUser(): self
    {
        if (!$this->currentUser) {
            return $this->whereRaw('1 = 0');
        }

        return match($this->currentUser->role) {
            'volunteer' => $this->forVolunteer($this->currentUser->id),
            'coordinator' => $this->forCoordinator($this->currentUser->id),
            default => $this
        };
    }

    /**
     * Filter task hours for a specific volunteer.
     * 
     * @param int $volunteerId Volunteer ID
     * @return self
     */
    public function forVolunteer(int $volunteerId): self
    {
        return $this->whereHas('task.application', function($query) use ($volunteerId) {
            $query->where('volunteer_id', $volunteerId);
        });
    }

    /**
     * Filter task hours for a specific coordinator.
     * 
     * @param int $coordinatorId Coordinator ID
     * @return self
     */
    public function forCoordinator(int $coordinatorId): self
    {
        return $this->whereHas('task.application', function($query) use ($coordinatorId) {
            $query->where('coordinator_id', $coordinatorId);
        });
    }

    
    public function betweenDates(string $fromDate, string $toDate): self
    {
        return $this->whereDate('started_date', '>=', $fromDate)
            ->whereDate('ended_date', '<=', $toDate);
    }

    /**
     * Filter task hours for a specific task.
     * 
     * @param int $taskId Task ID
     * @return self
     */
    public function forTask(int $taskId): self
    {
        return $this->where('task_id', $taskId);
    }
}