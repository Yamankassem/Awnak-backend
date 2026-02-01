<?php

namespace Modules\Applications\Notifications\TasksNotification;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Applications\Models\Task;

/**
 * Task Status Changed Notification
 * 
 * Sent when a task's status changes (e.g., from active to complete).
 * 
 * @package Modules\Applications\Notifications\TasksNotification
 * @author Your Name
 */
class TaskStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;
   
    /**
     * The task whose status changed.
     * 
     * @var Task
     */
    protected $task;
    /**
     * Previous task status.
     * 
     * @var string
     */
    protected $oldStatus;
    /**
     * New task status.
     * 
     * @var string
     */
    protected $newStatus;

    /**
     * Status translations for display.
     * 
     * @var array<string, string>
     */
    protected $statusTranslations = [
    'preparation' => 'Preparation',
    'active' => 'Active',
    'complete' => 'Complete',
    'cancelled' => 'Cancelled',
    ];

    /**
     * Create a new notification instance.
     * 
     * @param Task $task
     * @param string $oldStatus
     * @param string $newStatus
     */
    public function __construct(Task $task, $oldStatus, $newStatus)
    {
        $this->task = $task;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the notification's delivery channels.
     * 
     * @param mixed $notifiable
     * @return array<string>
     */
    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     * 
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        $oldStatusText = $this->statusTranslations[$this->oldStatus] ?? $this->oldStatus;
        $newStatusText = $this->statusTranslations[$this->newStatus] ?? $this->newStatus;
        $url = url('/tasks/' . $this->task->id);

        $mail = (new MailMessage())
            ->subject('Task Status Updated: ' . $this->task->title)
            ->greeting('Hello ' . $notifiable->name);

        if ($this->newStatus === 'complete') {
            $mail->line(' **Congratulations!** Your task has been marked as complete.')
                ->line('Task: **' . $this->task->title . '**')
                ->line('Your dedication is appreciated!');
        } elseif ($this->newStatus === 'cancelled') {
            $mail->line(' **Task Cancelled**')
                ->line('Task: **' . $this->task->title . '**')
                ->line('Status: ' . $oldStatusText . ' → ' . $newStatusText);
        } else {
            $mail->line('The status of your task has been updated.')
                ->line('Task: **' . $this->task->title . '**')
                ->line('Status: ' . $oldStatusText . ' → ' . $newStatusText);
        }

        return $mail->action('View Task Details', $url)
            ->line('Thank you for your contribution!');
    }

    /**
     * Get the array representation for database storage.
     * 
     * @param mixed $notifiable
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable): array
    {
        $oldStatusText = $this->statusTranslations[$this->oldStatus] ?? $this->oldStatus;
        $newStatusText = $this->statusTranslations[$this->newStatus] ?? $this->newStatus;

        $message = 'Task "' . $this->task->title . '" status changed from ';
        $message .= $oldStatusText . ' to ' . $newStatusText;

        $color = match($this->newStatus) {
            'complete' => 'success',
            'cancelled' => 'danger',
            'active' => 'warning',
            'preparation' => 'info',
            default => 'warning'
        };

        $icon = match($this->newStatus) {
            'complete' => 'fa-check-circle',
            'cancelled' => 'fa-times-circle',
            'active' => 'fa-play-circle',
            'preparation' => 'fa-clock',
            default => 'fa-sync-alt'
        };

        return [
            'title' => 'Task Status Updated',
            'message' => $message,
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'old_status_text' => $oldStatusText,
            'new_status_text' => $newStatusText,
            'type' => 'task_status_changed',
            'icon' => $icon,
            'color' => $color,
            'url' => '/tasks/' . $this->task->id,
        ];
    }

    public function toArray($notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
        ];
    }
}
