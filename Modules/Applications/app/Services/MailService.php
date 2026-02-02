<?php

namespace Modules\Applications\Services;

use Log;
use Exception;
use Illuminate\Support\Facades\Mail;
use Modules\Applications\Models\Task;
use Modules\Applications\Models\Feedback;
use Modules\Applications\Models\TaskHour;
use Modules\Applications\Models\Application;
use Modules\Applications\Mail\HoursLoggedMail;
use Modules\Applications\Mail\TaskAssignedMail;
use Modules\Applications\Mail\FeedbackReceivedMail;
use Modules\Applications\Mail\ApplicationCreatedMail;
use Modules\Applications\Mail\ApplicationStatusChangedMail;

class MailService
{
    public function sendApplicationCreatedMail(Application $application, $recipient = null)
    {
        try {
            if (!$recipient) {
                Mail::to($application->volunteer->email)
                    ->queue(new ApplicationCreatedMail($application, [
                        'subject' => 'Your volunteer application has been received',
                        'title' => 'Your application has been received',
                        'recipientRole' => 'volunteer',
                        'actionText' => 'Follow your application status',
                    ]));
                
                if ($application->coordinator) {
                    Mail::to($application->coordinator->email)
                        ->queue(new ApplicationCreatedMail($application, [
                            'subject' => 'New volunteer application - requires your review',
                            'title' => 'New volunteer application',
                            'recipientRole' => 'coordinator',
                            'actionText' => 'Review application',
                        ]));
                }
                
                if ($application->opportunity && $application->opportunity->createdBy) {
                    Mail::to($application->opportunity->createdBy->email)
                        ->queue(new ApplicationCreatedMail($application, [
                            'subject' => 'New request for your volunteer opportunity',
                            'title' => 'New request for your opportunity',
                            'recipientRole' => 'opportunity_manager',
                            'actionText' => 'View applications',
                        ]));
                }
            } else {
                Mail::to($recipient)
                    ->queue(new ApplicationCreatedMail($application));
            }
            
            Log::info('Application created mail sent', ['application_id' => $application->id]);
            return true;
            
        } catch (Exception $e) {
            Log::error('Failed to send application created mail', [
                'application_id' => $application->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
    
    
    public function sendApplicationStatusChangedMail(Application $application, $oldStatus, $newStatus)
    {
        try {
            Mail::to($application->volunteer->email)
                ->queue(new ApplicationStatusChangedMail($application, $oldStatus, $newStatus, [
                    'showNextSteps' => true,
                ]));
            
            if ($application->coordinator) {
                Mail::to($application->coordinator->email)
                    ->queue(new ApplicationStatusChangedMail($application, $oldStatus, $newStatus, [
                        'subject' => 'Volunteer application status updated',
                        'title' => 'Update application status',
                        'recipientName' => $application->coordinator->name,
                        'showNextSteps' => false,
                    ]));
            }
            
            Log::info('Application status changed mail sent', [
                'application_id' => $application->id,
                'from' => $oldStatus,
                'to' => $newStatus,
            ]);
            return true;
            
        } catch (Exception $e) {
            Log::error('Failed to send status changed mail', [
                'application_id' => $application->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
    
    public function sendTaskAssignedMail(Task $task)
    {
        try {
            $volunteer = $task->application->volunteer;
            
            Mail::to($volunteer->email)
                ->queue(new TaskAssignedMail($task, [
                    'showInstructions' => true,
                ]));
            
            if ($task->application->coordinator) {
                Mail::to($task->application->coordinator->email)
                    ->later(now()->addMinutes(5), new TaskAssignedMail($task, [
                        'subject' => 'A task has been assigned to a volunteer',
                        'title' => 'Assign task',
                        'showInstructions' => false,
                    ]));
            }
            
            Log::info('Task assigned mail sent', ['task_id' => $task->id]);
            return true;
            
        } catch (Exception $e) {
            Log::error('Failed to send task assigned mail', [
                'task_id' => $task->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}