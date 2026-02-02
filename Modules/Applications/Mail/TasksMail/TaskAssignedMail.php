<?php

namespace Modules\Applications\Mail\TasksMail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Applications\Models\Task;

/**
 * Task Assigned Mail
 * 
 * Sent to volunteers when a new task is assigned to them.
 * 
 * @package Modules\Applications\Mail\TasksMail
 * @author Your Name
 */
class TaskAssignedMail extends Mailable
{
    use Queueable, SerializesModels;
    
    /** @var Task The assigned task */
    public $task;
    
    /** @var mixed The volunteer receiving the task */
    public $volunteer;
    
    /** @var array View data for customization */
    public $viewData;

    /**
     * Create a new message instance.
     * 
     * @param Task $task
     * @param array $viewData Additional view data
     */
    public function __construct(Task $task, array $viewData = [])
    {
        $this->task = $task;
        $this->volunteer = $task->application->volunteer;
        
        $this->viewData = array_merge([
            'subject' => 'A new task assigned to you',
            'title' => 'New task',
            'actionText' => 'View task',
            'actionUrl' => url('/tasks/' . $task->id),
            'showInstructions' => true,
        ], $viewData);
    }

    /**
     * Build the message.
     * 
     * @return $this
     */
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