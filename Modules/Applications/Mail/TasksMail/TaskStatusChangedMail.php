<?php

namespace Modules\Applications\Mail\TasksMail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Applications\Models\Task;

class TaskStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $task;
    public $oldStatus;
    public $newStatus;
    public $statusTranslations;
    public $viewData;

    public function __construct(Task $task, $oldStatus, $newStatus, array $viewData = [])
    {
        $this->task = $task;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        
        $this->statusTranslations = [
            'preparation' => 'Preparation',
            'active' => 'Active',
            'complete' => 'Complete',
            'cancelled' => 'Cancelled',
        ];
        
        $this->viewData = array_merge([
            'subject' => 'Change Task Status',
            'title' => 'Change Task Status',
            'recipientName' => $task->application->volunteer->name,
            'actionText' => 'View Task Details',
            'actionUrl' => url('/tasks/' . $task->id),
            'showCompletionMessage' => $newStatus === 'complete',
            'showEncouragement' => $newStatus === 'complete',
        ], $viewData);
    }

    public function build()
    {
        return $this->subject($this->viewData['subject'])
            ->view('applications::emails.task-status-changed')
            ->with([
                'task' => $this->task,
                'oldStatus' => $this->oldStatus,
                'newStatus' => $this->newStatus,
                'oldStatusText' => $this->statusTranslations[$this->oldStatus] ?? $this->oldStatus,
                'newStatusText' => $this->statusTranslations[$this->newStatus] ?? $this->newStatus,
                'data' => $this->viewData,
            ]);
    }
}