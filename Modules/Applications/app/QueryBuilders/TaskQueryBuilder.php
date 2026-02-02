<?php

namespace Modules\Applications\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;
use Modules\Applications\Models\Task;
use Modules\Core\Models\User;

/**
 * Task Query Builder
 * 
 * Provides fluent query building methods for task queries
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
class TaskQueryBuilder extends Builder
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
     * Filter tasks for current user based on their role.
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
            'opportunity_manager' => $this->forOpportunityManager($this->currentUser->id),
            'organization_admin' => $this->forOrganization($this->currentUser->organization_id),
            default => $this 
        };
    }
     
    /**
     * Filter tasks for a specific volunteer.
     * 
     * @param int $volunteerId Volunteer ID
     * @return self
     */
    public function forVolunteer(int $volunteerId): self
    {
        return $this->whereHas('application', function($query) use ($volunteerId) {
            $query->where('volunteer_id', $volunteerId);
        });
    }

    /**
     * Filter tasks for a specific coordinatorId.
     * 
     * @param int $coordinatorId CoordinatorId ID
     * @return self
     */
    public function forCoordinator(int $coordinatorId): self
    {
        return $this->whereHas('application', function($query) use ($coordinatorId) {
            $query->where('coordinator_id', $coordinatorId);
        });
    }

    /**
     * Filter tasks for a specific user.
     * 
     * @param int $userId User ID
     * @return self
     */
    public function forOpportunityManager(int $userId): self
    {
        return $this->whereHas('application.opportunity', function($query) use ($userId) {
            $query->where('created_by', $userId);
        });
    }

    /**
     * Filter tasks for a specific organization.
     * 
     * @param int $organizationId Organization ID
     * @return self
     */
    public function forOrganization(int $organizationId): self
    {
        return $this->whereHas('application.opportunity', function($query) use ($organizationId) {
            $query->where('organization_id', $organizationId);
        });
    }

    
    public function withStatus(string $status): self
    {
        return $this->where('status', $status);
    }

    /**
     * Filter preparation tasks (status = 'preparation').
     * 
     * @return self
     */
    public function preparation(): self
    {
        return $this->where('status', 'preparation');
    }
    
    /**
     * Filter active tasks (status = 'active').
     * 
     * @return self
     */
    public function active(): self
    {
        return $this->where('status', 'active');
    }

    /**
     * Filter completed tasks (status = 'complete').
     * 
     * @return self
     */
    public function completed(): self
    {
        return $this->where('status', 'complete');
    }

    /**
     * Filter cancelled tasks (status = 'cancelled').
     * 
     * @return self
     */
    public function cancelled(): self
    {
        return $this->where('status', 'cancelled');
    }
    
    /**
     * Filter overdue tasks (active tasks with past due date).
     * 
     * @return self
     */
    public function overdue(): self
    {
        return $this->where('status', 'active')
            ->whereDate('due_date', '<', now());
    }

    
    public function withAllRelations(): self
    {
        return $this->with([
            'application' => function($query) {
                $query->with(['volunteer', 'coordinator', 'opportunity']);
            },
            'taskHours',
            'feedbacks'
        ]);
    }

    
    public function byDueDate(): self
    {
        return $this->orderBy('due_date', 'asc');
    }
}