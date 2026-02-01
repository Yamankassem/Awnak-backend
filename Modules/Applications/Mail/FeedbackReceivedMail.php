<?php

namespace Modules\Applications\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Applications\Models\Feedback;

class FeedbackReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $feedback;
    public $task;
    public $viewData;

    public function __construct(Feedback $feedback, array $viewData = [])
    {
        $this->feedback = $feedback;
        $this->task = $feedback->task;
        
        $this->viewData = array_merge([
            'subject' => 'New evaluation of your performance',
            'title' => 'Performance evaluation',
            'actionText' => 'View evaluation',
            'actionUrl' => url('/feedbacks/' . $feedback->id),
            'showRatingStars' => true,
        ], $viewData);
    }

    public function build()
    {
        return $this->subject($this->viewData['subject'])
            ->view('applications::emails.feedback-received')
            ->with([
                'feedback' => $this->feedback,
                'task' => $this->task,
                'data' => $this->viewData,
            ]);
    }
}