<?php

namespace Modules\Applications\Services;

use Modules\Applications\Models\Feedback;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Feedback Service
 * 
 * Business logic layer for Feedback operations.
 * Separates business rules from controller logic.
 * 
 * @package Modules\Applications\Services
 * @author Your Name
 */
class FeedbackService
{
    /**
     * Create a new feedback.
     * 
     * @param array $data feedback data
     * @return Feedback
     */
    public function createFeedback(array $data): Feedback
    {
        return Feedback::create($data);
    }

    /**
     * Update an existing feedback.
     * 
     * @param int $id Feedback ID
     * @param array $data Updated data
     * @return Feedback
     */
    public function updateFeedback(int $id, array $data): Feedback
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->update($data);
        return $feedback->fresh();
    }

    /**
     * Delete feedback status and handle completion logic.
     * 
     * @param int $id Feedback ID
     * @return Feedback
     */
    public function deleteFeedback(int $id): bool
    {
        $feedback = Feedback::findOrFail($id);
        return $feedback->delete();
    }
    
    
    
    public function getFeedbackReport(array $filters): array
    {
        $query = Feedback::with(['task.application.volunteer', 'task.application.coordinator']);
        
        if (isset($filters['task_id'])) {
            $query->where('task_id', $filters['task_id']);
        }
        
        if (isset($filters['volunteer_id'])) {
            $query->whereHas('task.application', function($q) use ($filters) {
                $q->where('volunteer_id', $filters['volunteer_id']);
            });
        }
        
        if (isset($filters['min_rating'])) {
            $query->where('rating', '>=', $filters['min_rating']);
        }
        
        if (isset($filters['max_rating'])) {
            $query->where('rating', '<=', $filters['max_rating']);
        }
        
        if (isset($filters['from_date'])) {
            $query->whereDate('created_at', '>=', $filters['from_date']);
        }
        
        if (isset($filters['to_date'])) {
            $query->whereDate('created_at', '<=', $filters['to_date']);
        }
        
        $feedbacks = $query->orderBy('created_at', 'desc')->get();
        
        $summary = [
            'total_feedbacks' => $feedbacks->count(),
            'average_rating' => round($feedbacks->avg('rating') ?? 0, 1),
            'highest_rating' => $feedbacks->max('rating') ?? 0,
            'lowest_rating' => $feedbacks->min('rating') ?? 0,
            'most_common_rating' => $feedbacks->count() > 0 ? 
                $feedbacks->groupBy('rating')->sortByDesc->count()->keys()->first() : 0,
        ];
        
        return [
            'summary' => $summary,
            'feedbacks' => $feedbacks,
            'breakdown' => [
                'by_rating' => $feedbacks->groupBy('rating')->map->count(),
                'by_organization' => $feedbacks->groupBy('name_of_org')->map(function($group, $org) {
                    return [
                        'count' => $group->count(),
                        'average_rating' => round($group->avg('rating'), 1),
                    ];
                }),
                'by_volunteer' => $feedbacks->groupBy('task.application.volunteer.name')->map(function($group) {
                    return [
                        'count' => $group->count(),
                        'average_rating' => round($group->avg('rating'), 1),
                    ];
                }),
            ],
        ];
    }
    
}