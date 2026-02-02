<?php

namespace Modules\Applications\Notifications\ApplicationsNotification;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Applications\Models\Application;

/**
 * Application Status Changed Notification
 *
 * Sent when a application's status changes (e.g., from active to complete).
 *
 * @package Modules\Applications\Notifications\ApplicationsNotification
 * @author Your Name
 */
class ApplicationStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    /**
         * The application whose status changed.
         *
         * @var Application
         */
    protected $application;

    /**
     * Previous application status.
     *
     * @var string
     */protected $oldStatus;

    
     /**
     * New application status.
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
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
    ];

    /**
         * Create a new notification instance.
         *
         * @param Application $application
         * @param string $oldStatus
         * @param string $newStatus
         */
    public function __construct(Application $application, $oldStatus, $newStatus)
    {
        $this->application = $application;
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
        $statusText = $this->statusTranslations[$this->newStatus] ?? $this->newStatus;
        $url = url('/applications/' . $this->application->id);

        return (new MailMessage())
            ->subject('Updating the status of your volunteer application')
            ->greeting('Honey' . $notifiable->name)
            ->line('The status of your volunteer application has been updated.')
            ->line('Opportunity: ' . $this->application->opportunity->title)
            ->line('Previous Case:' . ($this->statusTranslations[$this->oldStatus] ?? $this->oldStatus))
            ->line('New Status:' . $statusText)
            ->line('Date: ' . now()->format('Y-m-d H:i'))
            ->action('View Details', $url)
            ->line('Thank you for your understanding and cooperation.');
    }

    /**
         * Get the array representation for database storage.
         *
         * @param mixed $notifiable
         * @return array<string, mixed>
         */
    public function toDatabase($notifiable): array
    {
        $statusText = $this->statusTranslations[$this->newStatus] ?? $this->newStatus;

        return [
            'title' => 'Change of Order Status',
            'message' => 'Your application status has been changed for opportunity'. $this->application->opportunity->title . '" To: ' . $statusText,
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
