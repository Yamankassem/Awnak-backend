<?php

namespace Modules\Applications\Notifications\TasksNotification;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Applications\Models\Task;

/**
 * New Task Notification
 * 
 * Notifies volunteers when a new task is assigned.
 * Sent via database and email.
 * 
 * @package Modules\Applications\Notifications\TasksNotification
 * @author Your Name
 */
class NewTaskNotification extends Notification implements ShouldQueue
{
    use Queueable;
    
    /** @var Task The newly assigned task */
    protected $task;

    /**
     * Create a new notification instance.
     * 
     * @param Task $task
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

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
        $dueDate = $this->task->due_date->format('Y-m-d');
        $url = url('/tasks/' . $this->task->id);
        
        return (new MailMessage)
            ->subject('A new task has been assigned to you')
            ->greeting('Hello' . $notifiable->name)
            ->line('A new task has been assigned to you.')
            ->line('Task Title: ' . $this->task->title)
            ->line('Description: ' . substr($this->task->description, 0, 100) . '...')
            ->line('Due Date: ' . $dueDate)
            ->line('Opportunity: ' . $this->task->application->opportunity->title)
            ->action('Task Overview', $url)
            ->line('Please complete the task before the due date.');
    }

    /**
     * Get the array representation for database storage.
     * 
     * @param mixed $notifiable
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'New Task',
            'message' => 'A new task has been assigned to you: ' . $this->task->title,
            'task_id' => $this->task->id,
            'application_id' => $this->task->application_id,
            'due_date' => $this->task->due_date->format('Y-m-d'),
            'type' => 'new_task',
            'icon' => 'fa-tasks',
            'color' => 'primary',
            'url' => '/tasks/' . $this->task->id,
        ];
    }
}