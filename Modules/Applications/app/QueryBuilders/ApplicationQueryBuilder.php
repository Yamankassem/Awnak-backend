<?php

namespace Modules\Applications\QueryBuilders;

use InvalidArgumentException;
use Modules\Core\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Modules\Applications\Models\Application;

/**
 * Application Query Builder
 * 
 * Provides fluent query building methods for application queries
 * with built-in access control and filtering.
 * 
 * @package Modules\Applications\QueryBuilders
 * @author Your Name
 * 
 * @method self forVolunteer(int $volunteerId)
 * @method self forCoordinator(int $coordinatorId)
 * @method self withStatus(string $status)
 */
class ApplicationQueryBuilder extends Builder
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
     * Filter applications based on user role.
     * 
     * @param string $role User role
     * @param int|null $userId User ID
     * @param int|null $organizationId Organization ID
     * @return self
     * 
     * @example
     * $builder->forRole('volunteer', 1);
     * $builder->forRole('organization_admin', null, 5);
     */

    public function forRole(string $role, ?int $userId = null, ?int $organizationId = null): self
    {
        return match(strtolower($role)) {
            'volunteer' => $this->forVolunteer($userId ?? 0),
            'opportunity_manager' => $this->forOpportunityManager($userId ?? 0),
            'organization_admin' => $this->forOrganization($organizationId ?? 0),
            'coordinator' => $this->forCoordinator($userId ?? 0),
            'admin', 'super_admin' => $this,
            default => $this->whereRaw('1 = 0')
        };
    }

    /**
     * Filter applications for a specific volunteer.
     * 
     * @param int $volunteerId Volunteer ID
     * @return self
     */
    public function forVolunteer(int $volunteerId): self
    {
        return $this->where('volunteer_profile_id', $volunteerId);
    }

    /**
     * Filter applications for a specific opportunity manager.
     * 
     * @param int $userId user ID
     * @return self
     */
    public function forOpportunityManager(int $userId): self
    {
        return $this->whereHas('opportunity', function($query) use ($userId) {
            $query->where('created_by', $userId);
        });
    }

    /**
     * Filter applications for a specific opportunity.
     * 
     * @param int $opportunityId opportunity ID
     * @return self
     */
    public function forOpportunity(int $opportunityId): self
    {
        return $this->where('opportunity_id', $opportunityId);
    }
    
    /**
     * Filter applications for a specific organization.
     * 
     * @param int $organizationId organization ID
     * @return self
     */
    public function forOrganization(int $organizationId): self
    {
        return $this->whereHas('opportunity', function($query) use ($organizationId) {
            $query->where('organization_id', $organizationId);
        });
    }

    /**
     * Filter applications for a specific coordinator.
     * 
     * @param int $coordinatorId coordinator ID
     * @return self
     */
    public function forCoordinator(int $coordinatorId): self
    {
        return $this->where('coordinator_id', $coordinatorId);
    }

    /**
     * Filter applications created by current user.
     * 
     * @return self
     * 
     * @throws \RuntimeException If no current user
     */
    public function forCurrentUser(): self
    {
        if (!$this->currentUser) {
            return $this->whereRaw('1 = 0');
        }

        return $this->forRole(
            $this->currentUser->role,
            $this->currentUser->id,
            $this->currentUser->organization_id
        );
    }

    /**
     * Filter applications with specific status.
     * 
     * @param string $status Application status
     * @return self
     * @throws InvalidArgumentException If invalid status
     */
    public function withStatus(string $status): self
    {
        $validStatuses = ['pending', 'waiting_list', 'approved', 'rejected'];
        
        if (!in_array($status, $validStatuses)) {
            throw new InvalidArgumentException("Invalid case. Available cases:" . implode(', ', $validStatuses));
        }

        return $this->where('status', $status);
    }

    
    public function pending(): self
    {
        return $this->where('status', 'pending');
    }

    
    public function waitingList(): self
    {
        return $this->where('status', 'waiting_list');
    }

    
    public function approved(): self
    {
        return $this->where('status', 'approved');
    }

    
    public function rejected(): self
    {
        return $this->where('status', 'rejected');
    }

   
    public function withAllRelations(): self
    {
        return $this->with([
            'opportunity' => function($query) {
                $query->select('id', 'title', 'organization_id', 'created_by');
            },
            'volunteer' => function($query) {
                $query->select('id', 'user_id', 'full_name', 'email');
            },
            'coordinator' => function($query) {
                $query->select('id', 'name', 'email');
            }
        ]);
    }

   
    public function latestFirst(): self
    {
        return $this->orderBy('created_at', 'desc');
    }
    
    public function createdBetween(string $fromDate, string $toDate): self
    {
        return $this->whereBetween('created_at', [$fromDate, $toDate]);
    }

    
    public function searchInDescription(string $searchTerm): self
    {
        return $this->where('description', 'like', "%{$searchTerm}%");
    }
}