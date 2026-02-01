@extends('applications::emails.layout')

@section('title', $data['title'])

@section('content')
<div class="email-content">
    <h2> {{ $data['title'] }}</h2>
    
    <p>Dear Sir/Madam,<strong>{{ $data['recipientName'] }}</strong>,</p>
    
    <p>We would like to inform you that the status of your volunteer application on our platform has been updated.</p>
    
    <div class="info-box" style="background-color: {{ $newStatus == 'approved' ? '#d4edda' : ($newStatus == 'rejected' ? '#f8d7da' : '#fff3cd') }};">
        <h3>Update Details</h3>
        
        <div class="info-item">
            <span class="info-label">Volunteer Opportunity:</span>
            <span class="info-value">{{ $application->opportunity->title }}</span>
        </div>
        
        <div class="info-item">
            <span class="info-label">Organization:</span>
            <span class="info-value">{{ $application->opportunity->organization->name ?? 'Not specified' }}</span>
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
            <span class="info-label">Update Date:</span>
            <span class="info-value">{{ $application->updated_at->format('Y-m-d H:i') }}</span>
        </div>
        
        @if($application->coordinator)
        <div class="info-item">
            <span class="info-label">Volunteer Coordinator:</span>
            <span class="info-value">{{ $application->coordinator->name }}</span>
        </div>
        @endif
    </div>
    
    @if($newStatus == 'approved')
    <div style="background-color: #e7f3ff; padding: 20px; border-radius: 5px; margin: 20px 0; border-right: 4px solid #007bff;">
        <h3> Congratulations! Your application has been accepted.</h3>
        <p>Thank you for your willingness to volunteer. We would like to inform you that your application has been accepted. Here are the next steps:</p>
        <ol style="margin-right: 20px;">
            <li>The organization will contact you soon to provide further details.</li>
            <li>A volunteer coordinator will be assigned to you.</li>
            <li>You will receive a schedule of tasks and expectations.</li>
            <li>You can now log in and view the tasks assigned to you.</li>
        </ol>
    </div>
    @elseif($newStatus == 'rejected')
    <div style="background-color: #ffeaea; padding: 20px; border-radius: 5px; margin: 20px 0; border-right: 4px solid #dc3545;">
        <h3> >Important Notes</h3>
        <p>Thank you for your interest in volunteering. Unfortunately, your application was not accepted for the current opportunity for the following reasons:</p>
        <ul style="margin-right: 20px;">
            <li>Your skills do not match the opportunity's requirements.</li>
            <li>The required number of volunteers has already been reached.</li>
            <li>Changes in the opportunity circumstances or its cancellation.</li>
        </ul>
        <p>We encourage you to apply for other opportunities that match your skills.</p>
    </div>
    @endif
    
    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ $data['actionUrl'] }}" class="btn-primary">
            {{ $data['actionText'] }}
        </a>
        
        <a href="{{ url('/opportunities') }}" class="btn-secondary">
           >Browse Other Opportunities
        </a>
    </div>
    
    @if($newStatus == 'approved')
    <div style="margin-top: 25px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
        <h4>>Contact Information</h4>
        <p>For any inquiries, you can contact:</p>
        <div style="display: flex; justify-content: space-between; flex-wrap: wrap; margin-top: 10px;">
            <div>
                <strong>Platform Support:</strong><br>
                 support@tawaa.org<br>
                 800-123-4567
            </div>
            @if($application->coordinator)
            <div>
                <strong>Volunteer Coordinator:</strong><br>
                 {{ $application->coordinator->name }}<br>
                 {{ $application->coordinator->email }}
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection