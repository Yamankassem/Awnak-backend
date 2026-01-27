<?php

namespace Modules\Applications\Mail;

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
            'active' => 'نشط',
            'complete' => 'مكتمل',
        ];
        
        $this->viewData = array_merge([
            'subject' => 'تغيير حالة المهمة',
            'title' => 'تغيير حالة المهمة',
            'recipientName' => $task->application->volunteer->name,
            'actionText' => 'عرض تفاصيل المهمة',
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