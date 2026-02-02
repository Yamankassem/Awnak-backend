<?php

namespace Modules\Applications\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;
use Modules\Applications\Models\Feedback;
use Modules\Core\Models\User;

/**
 * Feedback Query Builder
 * 
 * Provides fluent query building methods for feedback queries
 * with built-in access control and filtering.
 * 
 * @package Modules\Applications\QueryBuilders
 * @author Your Name
 * 
 * @method self forVolunteer(int $volunteerId)
 * @method self forCoordinator(int $coordinatorId)
 * @method self withStatus(string $status)
 */
class FeedbackQueryBuilder extends Builder
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
     * Filter feedbacks created by current user.
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

        return match($this->currentUser->role) {
            'volunteer' => $this->forVolunteer($this->currentUser->name),
            'coordinator' => $this->forCoordinatorTasks(),
            'opportunity_manager' => $this->forOpportunityManager(),
            'organization_admin' => $this->forOrganization($this->currentUser->organization_id),
            default => $this 
        };
    }

    /**
     * Filter feedbacks for a specific volunteer.
     * 
     * @param int $volunteerId Volunteer ID
     * @return self
     */
    public function forVolunteer(string $volunteerName): self
    {
        return $this->where('name_of_vol', $volunteerName);
    }

    /**
     * Filter feedbacks for a specific coordinator.
     * 
     * @param int $coordinatorId coordinator ID
     * @return self
     */
    public function forCoordinatorTasks(): self
    {
        return $this->whereHas('task.application', function($query) {
            $query->where('coordinator_id', $this->currentUser->id);
        });
    }

    /**
     * Filter feedbacks for a specific opportunity manager.
     * 
     * @param int $userId user ID
     * @return self
     */
    public function forOpportunityManager(): self
    {
        return $this->whereHas('task.application.opportunity', function($query) {
            $query->where('created_by', $this->currentUser->id);
        });
    }

    /**
     * Filter feedbacks for a specific organization.
     * 
     * @param int $organizationId organization ID
     * @return self
     */
    public function forOrganization(string $organizationName): self
    {
        return $this->where('name_of_org', 'like', "%{$organizationName}%");
    }

    
    public function performanceEvaluations(): self
    {
        return $this->whereNotNull('name_of_org')
                   ->whereNotNull('name_of_vol')
                   ->whereNotNull('rating');
    }

    
    public function taskReviews(): self
    {
        return $this->whereNotNull('rating')
                   ->whereNotNull('comment');
    }

    
    public function withRating(int $rating): self
    {
        return $this->where('rating', $rating);
    }

    
    public function positive(): self
    {
        return $this->whereBetween('rating', [4, 5]);
    }

    
    public function negative(): self
    {
        return $this->whereBetween('rating', [1, 2]);
    }

    
    public function withoutResponse(): self
    {
        return $this->whereNull('rating')
                   ->whereNotNull('comment');
    }

    
    public function withAllRelations(): self
    {
        return $this->with([
            'task' => function($query) {
                $query->with(['application.opportunity', 'application.volunteer', 'application.coordinator']);
            }
        ]);
    }

    
    public function latestFirst(): self
    {
        return $this->orderBy('created_at', 'desc');
    }

    
    public function highestRatedFirst(): self
    {
        return $this->orderBy('rating', 'desc')
                   ->orderBy('created_at', 'desc');
    }
}