<?php

namespace Modules\Applications\Mail\ApplicationsMail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Applications\Models\Application;

class ApplicationCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $volunteer;
    public $opportunity;
    public $viewData;

    public function __construct(Application $application, array $viewData = [])
    {
        $this->application = $application;
        $this->volunteer = $application->volunteer;
        $this->opportunity = $application->opportunity;
        $this->viewData = array_merge([
            'subject' => 'New Volunteer Request - Volunteer Platform',
            'title' => 'New Volunteer Request',
            'actionText' => 'View Request',
            'actionUrl' => url('/admin/applications/' . $application->id),
            'recipientRole' => 'admin', 
        ], $viewData);
    }

    public function build()
    {
        return $this->subject($this->viewData['subject'])
            ->view('applications::emails.application-created')
            ->with([
                'application' => $this->application,
                'volunteer' => $this->volunteer,
                'opportunity' => $this->opportunity,
                'data' => $this->viewData,
            ]);
    }
}