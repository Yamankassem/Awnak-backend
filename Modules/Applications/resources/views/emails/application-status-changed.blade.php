@extends('applications::emails.layout')

@section('title', $data['title'])

@section('content')
<div class="email-content">
    <h2>🔄 {{ $data['title'] }}</h2>
    
    <p>عزيزي/عزيزتي <strong>{{ $data['recipientName'] }}</strong>,</p>
    
    <p>نود إعلامك بأنه تم تحديث حالة طلبك التطوعي على منصتنا.</p>
    
    <div class="info-box" style="background-color: {{ $newStatus == 'approved' ? '#d4edda' : ($newStatus == 'rejected' ? '#f8d7da' : '#fff3cd') }};">
        <h3>تفاصيل التحديث</h3>
        
        <div class="info-item">
            <span class="info-label">الفرصة التطوعية:</span>
            <span class="info-value">{{ $application->opportunity->title }}</span>
        </div>
        
        <div class="info-item">
            <span class="info-label">المنظمة:</span>
            <span class="info-value">{{ $application->opportunity->organization->name ?? 'غير محدد' }}</span>
        </div>
        
        <div class="info-item">
            <span class="info-label">الحالة السابقة:</span>
            <span class="info-value">
                <span class="status-badge status-{{ $oldStatus }}">
                    {{ $oldStatusText }}
                </span>
            </span>
        </div>
        
        <div class="info-item">
            <span class="info-label">الحالة الجديدة:</span>
            <span class="info-value">
                <span class="status-badge status-{{ $newStatus }}">
                    {{ $newStatusText }}
                </span>
            </span>
        </div>
        
        <div class="info-item">
            <span class="info-label">تاريخ التحديث:</span>
            <span class="info-value">{{ $application->updated_at->format('Y-m-d H:i') }}</span>
        </div>
        
        @if($application->coordinator)
        <div class="info-item">
            <span class="info-label">منسق التطوع:</span>
            <span class="info-value">{{ $application->coordinator->name }}</span>
        </div>
        @endif
    </div>
    
    @if($newStatus == 'approved')
    <div style="background-color: #e7f3ff; padding: 20px; border-radius: 5px; margin: 20px 0; border-right: 4px solid #007bff;">
        <h3> تهانينا! تم قبول طلبك</h3>
        <p>نشكرك على رغبتك في التطوع ونود إعلامك بأن طلبك قد تم قبوله. إليك الخطوات التالية:</p>
        <ol style="margin-right: 20px;">
            <li>ستتواصل معك المنظمة قريباً لتحديد التفاصيل</li>
            <li>سيتم تعيين منسق متطوعين لك</li>
            <li>ستتلقى جدول المهام والتوقعات</li>
            <li>يمكنك الآن تسجيل الدخول ومشاهدة المهام الموكلة إليك</li>
        </ol>
    </div>
    @elseif($newStatus == 'rejected')
    <div style="background-color: #ffeaea; padding: 20px; border-radius: 5px; margin: 20px 0; border-right: 4px solid #dc3545;">
        <h3> ملاحظات مهمة</h3>
        <p>نشكرك على اهتمامك بالتطوع. للأسف، طلبك لم يتم قبوله للفرصة الحالية للأسباب التالية:</p>
        <ul style="margin-right: 20px;">
            <li>عدم توافق المهارات مع متطلبات الفرصة</li>
            <li>اكتفاء عدد المتطوعين المطلوب</li>
            <li>تغير ظروف الفرصة أو إلغائها</li>
        </ul>
        <p>نشجعك على التقديم لفرص أخرى مناسبة لمهاراتك.</p>
    </div>
    @endif
    
    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ $data['actionUrl'] }}" class="btn-primary">
            {{ $data['actionText'] }}
        </a>
        
        <a href="{{ url('/opportunities') }}" class="btn-secondary">
            تصفح فرص أخرى
        </a>
    </div>
    
    @if($newStatus == 'approved')
    <div style="margin-top: 25px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
        <h4> معلومات التواصل</h4>
        <p>لأي استفسار، يمكنك التواصل مع:</p>
        <div style="display: flex; justify-content: space-between; flex-wrap: wrap; margin-top: 10px;">
            <div>
                <strong>دعم المنصة:</strong><br>
                 support@tawaa.org<br>
                 800-123-4567
            </div>
            @if($application->coordinator)
            <div>
                <strong>منسق التطوع:</strong><br>
                 {{ $application->coordinator->name }}<br>
                 {{ $application->coordinator->email }}
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection