<?php

namespace Modules\Applications\Mail\ApplicationsMail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Applications\Models\Application;

class ApplicationStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $oldStatus;
    public $newStatus;
    public $statusMessages;
    public $viewData;

    public function __construct(Application $application, $oldStatus, $newStatus, array $viewData = [])
    {
        $this->application = $application;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        
        $this->statusMessages = [
            'pending' => 'Pending',
            'approved' => 'Accepted',
            'rejected' => 'Rejected',
        ];
        
        $this->viewData = array_merge([
            'subject' => 'Update Your Volunteer Request Status',
            'title' => 'Update Request Status',
            'recipientName' => $application->volunteer->name,
            'actionText' => 'View Request Details',
            'actionUrl' => url('/applications/' . $application->id),
            'showNextSteps' => true,
        ], $viewData);
    }

    public function build()
    {
        return $this->subject($this->viewData['subject'])
            ->view('applications::emails.application-status-changed')
            ->with([
                'application' => $this->application,
                'oldStatus' => $this->oldStatus,
                'newStatus' => $this->newStatus,
                'oldStatusText' => $this->statusMessages[$this->oldStatus] ?? $this->oldStatus,
                'newStatusText' => $this->statusMessages[$this->newStatus] ?? $this->newStatus,
                'data' => $this->viewData,
            ]);
    }
}