<?php

namespace Modules\Applications\Notifications\TasksNotification;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Applications\Models\Task;

class NewTaskNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $dueDate = $this->task->due_date->format('Y-m-d');
        $url = url('/tasks/' . $this->task->id);
        
        return (new MailMessage)
            ->subject('مهمة جديدة مخصصة لك')
            ->greeting('مرحباً ' . $notifiable->name)
            ->line('تم تعيين مهمة جديدة لك.')
            ->line('عنوان المهمة: ' . $this->task->title)
            ->line('الوصف: ' . substr($this->task->description, 0, 100) . '...')
            ->line('تاريخ الاستحقاق: ' . $dueDate)
            ->line('الفرصة: ' . $this->task->application->opportunity->title)
            ->action('عرض المهمة', $url)
            ->line('يرجى إكمال المهمة قبل تاريخ الاستحقاق.');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'مهمة جديدة',
            'message' => 'تم تعيين مهمة جديدة لك: ' . $this->task->title,
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