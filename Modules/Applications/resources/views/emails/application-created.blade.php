@extends('applications::emails.layout')

@section('title', $data['title'])

@section('content')
<div class="email-content">
    <h2> {{ $data['title'] }}</h2>
    
    <p>Hello {{ $data['recipientRole'] == 'volunteer' ? 'Dear Volunteer': 'Dear Official' }},</p>
    
    @if($data['recipientRole'] == 'volunteer')
        <p>Thank you for submitting your volunteer application to our platform. We have successfully received your application and it will be reviewed by our team.</p>
    @else
        <p>A new volunteer application has been submitted on the platform and requires your review.</p>
    @endif
    
    <div class="info-box">
        <h3>Application Details</h3>
        
        <div class="info-item">
            <span class="info-label">Application Number:</span>
            <span class="info-value">#{{ $application->id }}</span>
        </div>
        
        <div class="info-item">
            <span class="info-label">Volunteer:</span>
            <span class="info-value">{{ $volunteer->name }}</span>
        </div>
        
        <div class="info-item">
            <span class="info-label">Email:</span>
            <span class="info-value">{{ $volunteer->email }}</span>
        </div>
        
        <div class="info-item">
            <span class="info-label">Volunteer Opportunity:</span>
            <span class="info-value">{{ $opportunity->title }}</span>
        </div>
        
        <div class="info-item">
            <span class="info-label">Organization:</span>
            <span class="info-value">{{ $opportunity->organization->name ?? 'Not specified' }}</span>
        </div>
        
        <div class="info-item">
            <span class="info-label">Submission Date:</span>
            <span class="info-value">{{ $application->created_at->format('Y-m-d H:i') }}</span>
        </div>
        
        <div class="info-item">
            <span class="info-label">Status:</span>
            <span class="info-value">
                <span class="status-badge status-{{ $application->status }}">
                    @if($application->status == 'pending') Pending
                    @elseif($application->status == 'approved') Accepted
                    @elseif($application->status == 'rejected') Rejected
                    @endif
                </span>
            </span>
        </div>
    </div>
    
    @if(!empty($application->description))
    <div style="margin: 20px 0;">
        <h4>Volunteer Message:</h4>
        <p style="background: #f8f9fa; padding: 15px; border-radius: 5px; border-right: 3px solid #4a6fa5;">
            {{ $application->description }}
        </p>
    </div>
    @endif
    
    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ $data['actionUrl'] }}" class="btn-primary">
            {{ $data['actionText'] }}
        </a>
    </div>
    
    @if($data['recipientRole'] == 'volunteer')
    <p style="margin-top: 20px; color: #666; font-size: 14px;">
        <strong>Note:</strong>You will be notified of any updates to your application status via email.
    </p>
    @else
    <p style="margin-top: 20px; color: #666; font-size: 14px;">
        <strong>Suggested Actions:</strong>
        <ul style="margin-right: 20px; color: #555;">
            <li>Review the volunteer's information</li>
            <li>Verify the alignment of skills with the opportunity requirements</li>
            <li>Update the application status within 48 hours</li>
        </ul>
    </p>
    @endif
</div>
@endsection