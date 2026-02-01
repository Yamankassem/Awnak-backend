<?php

namespace Modules\Applications\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Applications\Models\TaskHour;

class HoursLoggedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $taskHour;
    public $task;
    public $viewData;

    public function __construct(TaskHour $taskHour, array $viewData = [])
    {
        $this->taskHour = $taskHour;
        $this->task = $taskHour->task;
        
        $this->viewData = array_merge([
            'subject' => 'New work hours recorded',
            'title' => 'Record work hours',
            'recipientRole' => 'Coordinator', 
            'actionText' => 'View details',
            'actionUrl' => url('/task-hours/' . $taskHour->id),
        ], $viewData);
    }

    public function build()
    {
        return $this->subject($this->viewData['subject'])
            ->view('applications::emails.hours-logged')
            ->with([
                'taskHour' => $this->taskHour,
                'task' => $this->task,
                'data' => $this->viewData,
            ]);
    }
}