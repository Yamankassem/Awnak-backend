<?php

namespace Modules\Applications\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Applications\Models\TaskHour;

/**
 * Hours Logged Notification
 * 
 * Notifies coordinators and administrators when a volunteer logs new work hours.
 * This notification is triggered automatically when task hours are recorded
 * and provides details about the hours worked, task information, and volunteer.
 * 
 * @package Modules\Applications\Notifications
 * @author Your Name
 * @since 1.0.0
 */
class HoursLoggedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The task hour record that was logged.
     * 
     * @var TaskHour
     */
    protected TaskHour $taskHour;

    /**
     * Create a new notification instance.
     * 
     * @param TaskHour $taskHour The task hour record that triggered the notification
     */
    public function __construct(TaskHour $taskHour)
    {
        $this->taskHour = $taskHour;
    }

    /**
     * Get the notification's delivery channels.
     * 
     * This notification is delivered both to the database (for in-app notifications)
     * and via email for important alerts.
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
     * Creates an email notification with details about the logged hours,
     * including volunteer information, task details, hours worked, and period.
     * 
     * @param mixed $notifiable The entity receiving the notification
     * @return MailMessage Formatted email message
     * 
     * @example
     * Subject: "New work hours have been recorded"
     * Content: "The volunteer John Doe has recorded 5 hours for Task: Event Setup"
     */
    public function toMail($notifiable): MailMessage
    {
        $task = $this->taskHour->task;
        $volunteer = $task->application->volunteer;
        
        return (new MailMessage)
            ->subject('New work hours have been recorded')
            ->greeting('Hello ' . $notifiable->name)
            ->line('The volunteer ' . $volunteer->name . ' has recorded new work hours.')
            ->line('Task: ' . $task->title)
            ->line('Number of hours: ' . $this->taskHour->hours)
            ->line('Period: from ' . $this->taskHour->started_date->format('Y-m-d') . 
                   ' to ' . $this->taskHour->ended_date->format('Y-m-d'))
            ->line('Notes: ' . $this->taskHour->note)
            ->action('Show details', url('/task-hours/' . $this->taskHour->id))
            ->line('Please review the recorded hours.');
    }

    /**
     * Get the array representation for database storage.
     * 
     * Stores notification data in the database for in-app display.
     * Includes metadata like icon, color, and URL for quick access.
     * 
     * @param mixed $notifiable The entity receiving the notification
     * @return array<string, mixed> Database notification data
     * 
     * @example
     * [
     *     'title' => 'Record work hours',
     *     'message' => 'Recorded 5 hour(s) for the task: Event Setup',
     *     'task_hour_id' => 123,
     *     'type' => 'hours_logged'
     * ]
     */
    public function toDatabase($notifiable): array
    {
        $task = $this->taskHour->task;
        
        return [
            'title' => 'Record work hours',
            'message' => 'Recorded ' . $this->taskHour->hours . ' hour(s) for the task: ' . $task->title,
            'task_hour_id' => $this->taskHour->id,
            'task_id' => $task->id,
            'hours' => $this->taskHour->hours,
            'volunteer_name' => $task->application->volunteer->name,
            'type' => 'hours_logged',
            'icon' => 'fa-clock',
            'color' => 'success',
            'url' => '/task-hours/' . $this->taskHour->id,
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
            'task_hour_id' => $this->taskHour->id,
            'hours' => $this->taskHour->hours,
            'task_title' => $this->taskHour->task->title,
            'volunteer_name' => $this->taskHour->task->application->volunteer->name,
        ];
    }
}