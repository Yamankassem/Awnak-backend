@extends('applications::emails.layout')

@section('title', $data['title'])

@section('content')
<div class="email-content">
    <h2>🔄 {{ $data['title'] }}</h2>
    
    <p>Dear Sir/Madam,<strong>{{ $data['recipientName'] }}</strong>,</p>
    
    <p>We would like to inform you that the status of your task on the volunteering platform has been updated.</p>
    
    <div class="info-box" style="background-color: {{ $newStatus == 'complete' ? '#d4edda' : '#fff3cd' }};">
        <h3>Update Details</h3>
        
        <div class="info-item">
            <span class="info-label">Task Title:</span>
            <span class="info-value">{{ $task->title }}</span>
        </div>
        
        <div class="info-item">
            <span class="info-label">Volunteering Opportunity:</span>
            <span class="info-value">{{ $task->application->opportunity->title }}</span>
        </div>
        
        <div class="info-item">
            <span class="info-label">Previous Status:</span>
            <span class="info-value">
                <span class="status-badge status-{{ $oldStatus }}">
                    {{ $oldStatusText }}
                </span>
            </span>
        </div>
        
        <div class="info-item">
            <span class="info-label">New Status:</span>
            <span class="info-value">
                <span class="status-badge status-{{ $newStatus }}">
                    {{ $newStatusText }}
                </span>
            </span>
        </div>
        
        <div class="info-item">
            <span class="info-label">Due Date:</span>
            <span class="info-value">{{ $task->due_date->format('Y-m-d') }}</span>
        </div>
        
        <div class="info-item">
            <span class="info-label">Update Date:</span>
            <span class="info-value">{{ $task->updated_at->format('Y-m-d H:i') }}</span>
        </div>
        
        @if($task->application->coordinator)
        <div class="info-item">
            <span class="info-label">Task Coordinator:</span>
            <span class="info-value">{{ $task->application->coordinator->name }}</span>
        </div>
        @endif
    </div>
    
    @if($newStatus == 'complete')
    <div style="background-color: #e7f3ff; padding: 20px; border-radius: 5px; margin: 20px 0; border-right: 4px solid #007bff;">
        <h3> Congratulations! The task has been completed</h3>
        
        @if($task->taskHours()->exists())
        <p><strong>Total Recorded Hours:</strong> {{ $task->taskHours()->sum('hours') }} hour</p>
        @endif
        
        @if($task->completed_at)
        <p><strong>Completion Date:</strong> {{ $task->completed_at->format('Y-m-d H:i') }}</p>
        @endif
        
        <p>We thank you for your efforts and for successfully completing this task. Your contribution helps make a positive impact in the community.</p>
    </div>
    @endif
    
    <div style="margin: 20px 0;">
        <h4>Task Description:</h4>
        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; border-right: 3px solid #4a6fa5;">
            {{ $task->description }}
        </div>
    </div>
    
    @if($data['showEncouragement'])
    <div style="background-color: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0; border-right: 4px solid #ffc107;">
        <p><strong> Next Steps:</strong></p>
        <ul style="margin-right: 20px;">
            <li>Make sure to record all working hours</li>
            <li>Provide feedback on your volunteering experience</li>
            <li>Share your achievement with your friends</li>
            <li>Explore new volunteering opportunities</li>
        </ul>
    </div>
    @endif
    
    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ $data['actionUrl'] }}" class="btn-primary">
            {{ $data['actionText'] }}
        </a>
        
        @if($newStatus == 'complete')
        <a href="{{ url('/feedbacks/create?task_id=' . $task->id) }}" class="btn-secondary">
           Give Your Feedback
        </a>
        @endif
        
        <a href="{{ url('/tasks') }}" class="btn-secondary">
            All My Tasks
        </a>
    </div>
    
    @if($newStatus == 'complete')
    <div style="margin-top: 25px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
        <h4>Recorded Working Hours</h4>
        
        @if($task->taskHours()->exists())
        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <thead>
                <tr style="background: #e9ecef;">
                    <th style="padding: 8px; text-align: right; border: 1px solid #dee2e6;">Date</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #dee2e6;">Hours</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #dee2e6;">Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($task->taskHours as $hour)
                <tr>
                    <td style="padding: 8px; border: 1px solid #dee2e6;">
                        {{ $hour->started_date->format('Y-m-d') }}
                        @if($hour->ended_date && $hour->ended_date != $hour->started_date)
                        To {{ $hour->ended_date->format('Y-m-d') }}
                        @endif
                    </td>
                    <td style="padding: 8px; border: 1px solid #dee2e6;">{{ $hour->hours }} hour</td>
                    <td style="padding: 8px; border: 1px solid #dee2e6;">{{ $hour->note }}</td>
                </tr>
                @endforeach
                <tr style="background: #f8f9fa; font-weight: bold;">
                    <td style="padding: 8px; border: 1px solid #dee2e6;" colspan="2">Total</td>
                    <td style="padding: 8px; border: 1px solid #dee2e6;">{{ $task->taskHours()->sum('hours') }} hour</td>
                </tr>
            </tbody>
        </table>
        @else
        <p style="color: #666; text-align: center;">No working hours have been recorded for this task.</p>
        @endif
    </div>
    @endif
    
    <div style="margin-top: 25px; padding: 15px; background: #e8f4fd; border-radius: 5px;">
        <h4>Contact Information</h4>
        <p>For any inquiries regarding the task, you can contact:</p>
        <div style="display: flex; justify-content: space-between; flex-wrap: wrap; margin-top: 10px;">
            <div>
                <strong>Platform Support:</strong><br>
                 support@tawaa.org<br>
                 800-123-4567
            </div>
            @if($task->application->coordinator)
            <div>
                <strong>Volunteering Coordinator:</strong><br>
                 {{ $task->application->coordinator->name }}<br>
                 {{ $task->application->coordinator->email }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection