<?php

namespace Modules\Applications\Notifications\TaskHoursNotification;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Applications\Models\TaskHour;

class HoursLoggedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $taskHour;

    public function __construct(TaskHour $taskHour)
    {
        $this->taskHour = $taskHour;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $task = $this->taskHour->task;
        $volunteer = $task->application->volunteer;
        
        return (new MailMessage)
            ->subject('تم تسجيل ساعات عمل جديدة')
            ->greeting('مرحباً ' . $notifiable->name)
            ->line('قام المتطوع ' . $volunteer->name . ' بتسجيل ساعات عمل جديدة.')
            ->line('المهمة: ' . $task->title)
            ->line('عدد الساعات: ' . $this->taskHour->hours)
            ->line('الفترة: من ' . $this->taskHour->started_date->format('Y-m-d') . 
                   ' إلى ' . $this->taskHour->ended_date->format('Y-m-d'))
            ->line('الملاحظات: ' . $this->taskHour->note)
            ->action('عرض التفاصيل', url('/task-hours/' . $this->taskHour->id))
            ->line('يرجى مراجعة الساعات المسجلة.');
    }

    public function toDatabase($notifiable): array
    {
        $task = $this->taskHour->task;
        
        return [
            'title' => 'تسجيل ساعات عمل',
            'message' => 'تم تسجيل ' . $this->taskHour->hours . ' ساعة للمهمة: ' . $task->title,
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
}