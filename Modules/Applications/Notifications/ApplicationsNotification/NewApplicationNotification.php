<?php

namespace Modules\Applications\Notifications\ApplicationsNotification;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Modules\Applications\Models\Application;

/**
 * New Application Notification
 * 
 * Notifies volunteers when a new application is assigned.
 * Sent via database and email.
 * 
 * @package Modules\Applications\Notifications\ApplicationsNotification
 * @author Your Name
 */
class NewApplicationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /** @var Application The newly assigned application */
    protected $application;
    
    /**
     * Create a new notification instance.
     * 
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
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
        $url = url('/admin/applications/' . $this->application->id);
        
        return (new MailMessage)
            ->subject('New Volunteer Request - ' . config('app.name'))
            ->greeting('Hello' . $notifiable->name)
            ->line('A new volunteer application has been submitted.')
            ->line('Volunteer: ' . $this->application->volunteer->name)
            ->line('Opportunity: ' . $this->application->opportunity->title)
            ->action('Offer Order', $url)
            ->line('Thank you for using our platform!');
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
            'title' => 'New Volunteer Request',
            'message' => 'Step'. $this->application->volunteer->name . 'For the opportunity: ' . $this->application->opportunity->title,
            'application_id' => $this->application->id,
            'volunteer_profile_id' => $this->application->volunteer_profile_id,
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
            'message' => 'New Volunteer Request',
        ];
    }
}