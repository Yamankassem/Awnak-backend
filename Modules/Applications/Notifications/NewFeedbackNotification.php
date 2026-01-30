<?php

namespace Modules\Applications\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Applications\Models\Feedback;

/**
 * New Feedback Notification
 * 
 * Notifies volunteers when they receive new performance evaluations or task feedback.
 * This notification is triggered when organizations or coordinators submit feedback
 * on a volunteer's performance after task completion.
 * 
 * @package Modules\Applications\Notifications
 * @author Your Name
 * @since 1.0.0
 */
class NewFeedbackNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The feedback record containing the evaluation.
     * 
     * @var Feedback
     */
    protected Feedback $feedback;

    /**
     * Create a new notification instance.
     * 
     * @param Feedback $feedback The feedback record that triggered the notification
     */
    public function __construct(Feedback $feedback)
    {
        $this->feedback = $feedback;
    }

    /**
     * Get the notification's delivery channels.
     * 
     * This notification is delivered both to the database (for in-app notifications)
     * and via email to ensure volunteers are aware of new evaluations.
     * 
     * @param mixed $notifiable The entity receiving the notification
     * @return array<string> Delivery channels
     */
    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     * 
     * Creates an email notification with details about the performance evaluation,
     * including rating, comments, and the evaluating organization.
     * 
     * @param mixed $notifiable The entity receiving the notification
     * @return MailMessage Formatted email message
     * 
     * @example
     * Subject: "New evaluation on your performance"
     * Content: "You received 4/5 stars for Task: Community Outreach"
     */
    public function toMail($notifiable): MailMessage
    {
        $task = $this->feedback->task;
        
        return (new MailMessage)
            ->subject('New evaluation on your performance')
            ->greeting('Hello ' . $notifiable->name)
            ->line('You have received a new evaluation on your performance.')
            ->line('Task: ' . $task->title)
            ->line('Evaluation: ' . $this->feedback->rating . ' / 5')
            ->line('Comment: ' . $this->feedback->comment)
            ->line('From: ' . $this->feedback->name_of_org)
            ->action('View Evaluation', url('/feedbacks/' . $this->feedback->id))
            ->line('Thank you for your effort and dedication!');
    }

    /**
     * Get the array representation for database storage.
     * 
     * Stores notification data in the database for in-app display.
     * Includes rating, organization name, and task information.
     * 
     * @param mixed $notifiable The entity receiving the notification
     * @return array<string, mixed> Database notification data
     * 
     * @example
     * [
     *     'title' => 'New Evaluation',
     *     'message' => 'You received a new evaluation on Task: Community Outreach',
     *     'feedback_id' => 456,
     *     'rating' => 4,
     *     'type' => 'new_feedback'
     * ]
     */
    public function toDatabase($notifiable): array
    {
        $task = $this->feedback->task;
        
        return [
            'title' => 'New Evaluation',
            'message' => 'You have received a new evaluation on the task: ' . $task->title,
            'feedback_id' => $this->feedback->id,
            'task_id' => $task->id,
            'rating' => $this->feedback->rating,
            'from' => $this->feedback->name_of_org,
            'type' => 'new_feedback',
            'icon' => 'fa-star',
            'color' => 'warning',
            'url' => '/feedbacks/' . $this->feedback->id,
        ];
    }
    
    /**
     * Get the array representation of the notification (optional).
     * 
     * Provides a simplified representation of the notification data.
     * Used for additional serialization if needed.
     * 
     * @param mixed $notifiable The entity receiving the notification
     * @return array<string, mixed> Array representation
     */
    public function toArray($notifiable): array
    {
        return [
            'feedback_id' => $this->feedback->id,
            'rating' => $this->feedback->rating,
            'task_title' => $this->feedback->task->title,
            'organization' => $this->feedback->name_of_org,
        ];
    }
    
    /**
     * Determine if the feedback is a performance evaluation.
     * 
     * Performance evaluations require organization and volunteer names,
     * while simple task reviews may not include this information.
     * 
     * @return bool True if this is a performance evaluation
     */
    public function isPerformanceEvaluation(): bool
    {
        return !empty($this->feedback->name_of_org) && !empty($this->feedback->name_of_vol);
    }
}