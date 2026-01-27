@extends('applications::emails.layout')

@section('title', $data['title'])

@section('content')
<div class="email-content">
    <h2>📋 {{ $data['title'] }}</h2>
    
    <p>مرحباً {{ $data['recipientRole'] == 'volunteer' ? 'عزيزي المتطوع' : 'عزيزي المسؤول' }},</p>
    
    @if($data['recipientRole'] == 'volunteer')
        <p>شكراً لتقديمك طلب التطوع في منصتنا. لقد استلمنا طلبك بنجاح وسيتم مراجعته من قبل فريقنا.</p>
    @else
        <p>تم تقديم طلب تطوع جديد على المنصة ويتطلب مراجعتك.</p>
    @endif
    
    <div class="info-box">
        <h3>تفاصيل الطلب</h3>
        
        <div class="info-item">
            <span class="info-label">رقم الطلب:</span>
            <span class="info-value">#{{ $application->id }}</span>
        </div>
        
        <div class="info-item">
            <span class="info-label">المتطوع:</span>
            <span class="info-value">{{ $volunteer->name }}</span>
        </div>
        
        <div class="info-item">
            <span class="info-label">البريد الإلكتروني:</span>
            <span class="info-value">{{ $volunteer->email }}</span>
        </div>
        
        <div class="info-item">
            <span class="info-label">الفرصة التطوعية:</span>
            <span class="info-value">{{ $opportunity->title }}</span>
        </div>
        
        <div class="info-item">
            <span class="info-label">المنظمة:</span>
            <span class="info-value">{{ $opportunity->organization->name ?? 'غير محدد' }}</span>
        </div>
        
        <div class="info-item">
            <span class="info-label">تاريخ التقديم:</span>
            <span class="info-value">{{ $application->created_at->format('Y-m-d H:i') }}</span>
        </div>
        
        <div class="info-item">
            <span class="info-label">الحالة:</span>
            <span class="info-value">
                <span class="status-badge status-{{ $application->status }}">
                    @if($application->status == 'pending') قيد الانتظار
                    @elseif($application->status == 'approved') مقبول
                    @elseif($application->status == 'rejected') مرفوض
                    @endif
                </span>
            </span>
        </div>
    </div>
    
    @if(!empty($application->description))
    <div style="margin: 20px 0;">
        <h4>رسالة المتطوع:</h4>
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
        <strong>ملاحظة:</strong> سيتم إعلامك بتحديث حالة طلبك عبر البريد الإلكتروني.
    </p>
    @else
    <p style="margin-top: 20px; color: #666; font-size: 14px;">
        <strong>إجراءات مقترحة:</strong>
        <ul style="margin-right: 20px; color: #555;">
            <li>مراجعة معلومات المتطوع</li>
            <li>التحقق من توافق المهارات مع متطلبات الفرصة</li>
            <li>تحديث حالة الطلب خلال 48 ساعة</li>
        </ul>
    </p>
    @endif
</div>
@endsection