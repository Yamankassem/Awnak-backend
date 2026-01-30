@extends('applications::emails.layout')

@section('title', $data['title'])

@section('content')
<div class="email-content">
    <h2> {{ $data['title'] }}</h2>
    
    <p>مرحباً <strong>{{ $volunteer->name }}</strong>,</p>
    
    <p>تم تعيين مهمة جديدة لك ضمن برنامج التطوع الخاص بك. يرجى مراجعة تفاصيل المهمة أدناه.</p>
    
    <div class="info-box">
        <h3>تفاصيل المهمة</h3>
        
        <div class="info-item">
            <span class="info-label">عنوان المهمة:</span>
            <span class="info-value">{{ $task->title }}</span>
        </div>
        
        <div class="info-item">
            <span class="info-label">الفرصة التطوعية:</span>
            <span class="info-value">{{ $task->application->opportunity->title }}</span>
        </div>
        
        <div class="info-item">
            <span class="info-label">تاريخ الاستحقاق:</span>
            <span class="info-value">
                {{ $task->due_date->format('Y-m-d') }}
                @if($task->due_date->isPast())
                    <span style="color: #dc3545;">(متأخر)</span>
                @elseif($task->due_date->diffInDays(now()) <= 3)
                    <span style="color: #ffc107;">(قريب)</span>
                @endif
            </span>
        </div>
        
        <div class="info-item">
            <span class="info-label">الحالة:</span>
            <span class="info-value">
                <span class="status-badge status-{{ $task->status }}">
                    @if($task->status == 'active') نشط
                    @elseif($task->status == 'complete') مكتمل
                    @endif
                </span>
            </span>
        </div>
        
        <div class="info-item">
            <span class="info-label">منسق المهمة:</span>
            <span class="info-value">{{ $task->application->coordinator->name ?? 'غير محدد' }}</span>
        </div>
    </div>
    
    <div style="margin: 20px 0;">
        <h4>وصف المهمة:</h4>
        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; border-right: 3px solid #4a6fa5;">
            {!! nl2br(e($task->description)) !!}
        </div>
    </div>
    
    @if($data['showInstructions'])
    <div style="background-color: #fff3cd; padding: 20px; border-radius: 5px; margin: 20px 0; border-right: 4px solid #ffc107;">
        <h4> تعليمات المهمة</h4>
        <ol style="margin-right: 20px;">
            <li>قم بمراجعة تفاصيل المهمة بعناية</li>
            <li>ابدأ بتنفيذ المهمة في أقرب وقت ممكن</li>
            <li>سجل ساعات العمل عبر النظام</li>
            <li>حدّث حالة المهمة عند الانتهاء</li>
            <li>أضف أي ملاحظات أو استفسارات</li>
        </ol>
    </div>
    @endif
    
    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ $data['actionUrl'] }}" class="btn-primary">
            {{ $data['actionText'] }}
        </a>
        
        <a href="{{ url('/tasks') }}" class="btn-secondary">
            جميع مهامي
        </a>
        
        <a href="mailto:{{ $task->application->coordinator->email ?? 'support@tawaa.org' }}" class="btn-secondary">
            تواصل مع المنسق
        </a>
    </div>
    
    <div style="margin-top: 25px; padding: 15px; background: #e8f4fd; border-radius: 5px;">
        <h4> تذكير بالساعات</h4>
        <p>تذكر تسجيل ساعات العمل الخاصة بهذه المهمة:</p>
        <ul style="margin-right: 20px;">
            <li>سجل الساعات بعد كل جلسة عمل</li>
            <li>أضف ملاحظات عن العمل المنجز</li>
            <li>المجموع التراكمي للساعات سيظهر في تقريرك</li>
        </ul>
        <a href="{{ ul('/task-hours/create?task_id=' . $task->id) }}" class="btn-secondary" style="margin-top: 10px;">
            سجل ساعات العمل
        </a>
    </div>
</div>
@endsection