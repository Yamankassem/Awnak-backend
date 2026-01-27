<?php

namespace Modules\Applications\Notifications\ApplicationsNotification;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Applications\Models\Application;

class ApplicationStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $application;
    protected $oldStatus;
    protected $newStatus;
    
   
    protected $statusTranslations = [
        'pending' => 'قيد الانتظار',
        'approved' => 'مقبول',
        'rejected' => 'مرفوض',
    ];

    public function __construct(Application $application, $oldStatus, $newStatus)
    {
        $this->application = $application;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $statusText = $this->statusTranslations[$this->newStatus] ?? $this->newStatus;
        $url = url('/applications/' . $this->application->id);
        
        return (new MailMessage)
            ->subject('تحديث حالة طلبك التطوعي')
            ->greeting('عزيزي ' . $notifiable->name)
            ->line('تم تحديث حالة طلبك التطوعي.')
            ->line('الفرصة: ' . $this->application->opportunity->title)
            ->line('الحالة السابقة: ' . ($this->statusTranslations[$this->oldStatus] ?? $this->oldStatus))
            ->line('الحالة الجديدة: ' . $statusText)
            ->line('التاريخ: ' . now()->format('Y-m-d H:i'))
            ->action('عرض التفاصيل', $url)
            ->line('شكراً لتفهمك وتعاونك.');
    }

    public function toDatabase($notifiable): array
    {
        $statusText = $this->statusTranslations[$this->newStatus] ?? $this->newStatus;
        
        return [
            'title' => 'تغيير حالة الطلب',
            'message' => 'تم تغيير حالة طلبك للفرصة "' . $this->application->opportunity->title . '" إلى: ' . $statusText,
            'application_id' => $this->application->id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'type' => 'application_status_changed',
            'icon' => 'fa-sync-alt',
            'color' => 'warning',
            'url' => '/applications/' . $this->application->id,
        ];
    }
}