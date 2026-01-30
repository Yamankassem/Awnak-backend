@extends('applications::emails.layout')

@section('title', $data['title'])

@section('content')
<div class="email-content">
    <h2> {{ $data['title'] }}</h2>
    
    <p>Hello<strong>{{ $volunteer->name }}</strong>,</p>
    
    <p>A new task has been assigned to you within your volunteer program. Please review the task details below.</p>
    
    <div class="info-box">
        <h3>Task Details</h3>
        
        <div class="info-item">
            <span class="info-label">Task Title:</span>
            <span class="info-value">{{ $task->title }}</span>
        </div>
        
        <div class="info-item">
            <span class="info-label">Volunteer Opportunity:</span>
            <span class="info-value">{{ $task->application->opportunity->title }}</span>
        </div>
        
        <div class="info-item">
            <span class="info-label">Due Date:</span>
            <span class="info-value">
                {{ $task->due_date->format('Y-m-d') }}
                @if($task->due_date->isPast())
                    <span style="color: #dc3545;">(Overdue)</span>
                @elseif($task->due_date->diffInDays(now()) <= 3)
                    <span style="color: #ffc107;">(Upcoming)</span>
                @endif
            </span>
        </div>
        
        <div class="info-item">
            <span class="info-label">Status:</span>
            <span class="info-value">
                <span class="status-badge status-{{ $task->status }}">
                    @if($task->status == 'preparation') Preparation
                    @elseif($task->status == 'active') Active
                    @elseif($task->status == 'complete') Completed
                    @elseif($task->status == 'cancelled') Cancelled
                    @endif
                </span>
            </span>
        </div>
        
        <div class="info-item">
            <span class="info-label">Task Coordinator:</span>
            <span class="info-value">{{ $task->application->coordinator->name ?? 'Not Specified' }}</span>
        </div>
    </div>
    
    <div style="margin: 20px 0;">
        <h4>Task Description:</h4>
        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; border-right: 3px solid #4a6fa5;">
            {!! nl2br(e($task->description)) !!}
        </div>
    </div>
    
    @if($data['showInstructions'])
    <div style="background-color: #fff3cd; padding: 20px; border-radius: 5px; margin: 20px 0; border-right: 4px solid #ffc107;">
        <h4>Task Instructions</h4>
        <ol style="margin-right: 20px;">
            <li>Carefully review the task details</li>
            <li>Start the task as soon as possible</li>
            <li>Log your work hours through the system</li>
            <li>Update the task status when completed</li>
            <li>Add any notes or questions</li>
        </ol>
    </div>
    @endif
    
    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ $data['actionUrl'] }}" class="btn-primary">
            {{ $data['actionText'] }}
        </a>
        
        <a href="{{ url('/tasks') }}" class="btn-secondary">
            All My Tasks
        </a>
        
        <a href="mailto:{{ $task->application->coordinator->email ?? 'support@tawaa.org' }}" class="btn-secondary">
           Contact the Coordinator
        </a>
    </div>
    
    <div style="margin-top: 25px; padding: 15px; background: #e8f4fd; border-radius: 5px;">
        <h4>Hours Reminder</h4>
        <p>Remember to log your work hours for this task:</p>
        <ul style="margin-right: 20px;">
            <li>Log hours after each work session</li>
            <li>Add notes on the work done</li>
            <li>The cumulative total of hours will appear in your report</li>
        </ul>
        <a href="{{ url('/task-hours/create?task_id=' . $task->id) }}" class="btn-secondary">
            Log Work Hours
        </a>
    </div>
</div>
@endsection