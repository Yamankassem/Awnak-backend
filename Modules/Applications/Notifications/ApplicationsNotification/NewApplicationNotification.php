<?php

namespace Modules\Applications\Notifications\ApplicationsNotification;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Modules\Applications\Models\Application;

class NewApplicationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function via($notifiable): array
    {
        
        return ['database', 'mail']; 
    }

    public function toMail($notifiable): MailMessage
    {
        $url = url('/admin/applications/' . $this->application->id);
        
        return (new MailMessage)
            ->subject('طلب تطوع جديد - ' . config('app.name'))
            ->greeting('مرحباً ' . $notifiable->name)
            ->line('تم تقديم طلب تطوع جديد.')
            ->line('المتطوع: ' . $this->application->volunteer->name)
            ->line('الفرصة: ' . $this->application->opportunity->title)
            ->action('عرض الطلب', $url)
            ->line('شكراً لاستخدامك منصتنا!');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'طلب تطوع جديد',
            'message' => 'قدم ' . $this->application->volunteer->name . ' طلباً للفرصة: ' . $this->application->opportunity->title,
            'application_id' => $this->application->id,
            'volunteer_id' => $this->application->volunteer_id,
            'opportunity_id' => $this->application->opportunity_id,
            'type' => 'new_application',
            'icon' => 'fa-user-plus',
            'color' => 'info',
            'url' => '/applications/' . $this->application->id,
        ];
    }

    public function toArray($notifiable): array
    {
        return [
            'application_id' => $this->application->id,
            'message' => 'طلب تطوع جديد',
        ];
    }
}