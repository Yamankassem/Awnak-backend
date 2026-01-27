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
            'subject' => 'تقييم جديد على أدائك',
            'title' => 'تقييم الأداء',
            'actionText' => 'عرض التقييم',
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