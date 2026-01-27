<?php

namespace Modules\Applications\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Applications\Models\Task;

class TaskAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $task;
    public $volunteer;
    public $viewData;

    public function __construct(Task $task, array $viewData = [])
    {
        $this->task = $task;
        $this->volunteer = $task->application->volunteer;
        
        $this->viewData = array_merge([
            'subject' => 'مهمة جديدة مخصصة لك',
            'title' => 'مهمة جديدة',
            'actionText' => 'عرض المهمة',
            'actionUrl' => url('/tasks/' . $task->id),
            'showInstructions' => true,
        ], $viewData);
    }

    public function build()
    {
        return $this->subject($this->viewData['subject'])
            ->view('applications::emails.task-assigned')
            ->with([
                'task' => $this->task,
                'volunteer' => $this->volunteer,
                'data' => $this->viewData,
            ]);
    }
}