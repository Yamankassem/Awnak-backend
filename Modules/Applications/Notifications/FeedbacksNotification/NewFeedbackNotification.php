<?php

namespace Modules\Applications\Notifications\FeedbacksNotification;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Applications\Models\Feedback;

class NewFeedbackNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $feedback;

    public function __construct(Feedback $feedback)
    {
        $this->feedback = $feedback;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $task = $this->feedback->task;
        
        return (new MailMessage)
            ->subject('تقييم جديد على أدائك')
            ->greeting('مرحباً ' . $notifiable->name)
            ->line('تلقيت تقييماً جديداً على أدائك.')
            ->line('المهمة: ' . $task->title)
            ->line('التقييم: ' . $this->feedback->rating . ' / 5 ⭐')
            ->line('التعليق: ' . $this->feedback->comment)
            ->line('من: ' . $this->feedback->name_of_org)
            ->action('عرض التقييم', url('/feedbacks/' . $this->feedback->id))
            ->line('نشكرك على جهودك وتفانيك!');
    }

    public function toDatabase($notifiable): array
    {
        $task = $this->feedback->task;
        
        return [
            'title' => 'تقييم جديد',
            'message' => 'تلقيت تقييماً جديداً على المهمة: ' . $task->title,
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
}