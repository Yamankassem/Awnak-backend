@extends('applications::emails.layout')

@section('title', $data['title'])

@section('content')
<div class="email-content">
    <h2>🔄 {{ $data['title'] }}</h2>
    
    <p>عزيزي/عزيزتي <strong>{{ $data['recipientName'] }}</strong>,</p>
    
    <p>نود إعلامك بأنه تم تحديث حالة مهمتك على منصة التطوع.</p>
    
    <div class="info-box" style="background-color: {{ $newStatus == 'complete' ? '#d4edda' : '#fff3cd' }};">
        <h3>تفاصيل التحديث</h3>
        
        <div class="info-item">
            <span class="info-label">عنوان المهمة:</span>
            <span class="info-value">{{ $task->title }}</span>
        </div>
        
        <div class="info-item">
            <span class="info-label">الفرصة التطوعية:</span>
            <span class="info-value">{{ $task->application->opportunity->title }}</span>
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
            <span class="info-label">تاريخ الاستحقاق:</span>
            <span class="info-value">{{ $task->due_date->format('Y-m-d') }}</span>
        </div>
        
        <div class="info-item">
            <span class="info-label">تاريخ التحديث:</span>
            <span class="info-value">{{ $task->updated_at->format('Y-m-d H:i') }}</span>
        </div>
        
        @if($task->application->coordinator)
        <div class="info-item">
            <span class="info-label">منسق المهمة:</span>
            <span class="info-value">{{ $task->application->coordinator->name }}</span>
        </div>
        @endif
    </div>
    
    @if($newStatus == 'complete')
    <div style="background-color: #e7f3ff; padding: 20px; border-radius: 5px; margin: 20px 0; border-right: 4px solid #007bff;">
        <h3>🎉 تهانينا! تم إكمال المهمة</h3>
        
        @if($task->taskHours()->exists())
        <p><strong>إجمالي الساعات المسجلة:</strong> {{ $task->taskHours()->sum('hours') }} ساعة</p>
        @endif
        
        @if($task->completed_at)
        <p><strong>تاريخ الإكمال:</strong> {{ $task->completed_at->format('Y-m-d H:i') }}</p>
        @endif
        
        <p>نشكرك على جهودك وإنجاز هذه المهمة بنجاح. مساهمتك تساعد في إحداث تغيير إيجابي في المجتمع.</p>
    </div>
    @endif
    
    <div style="margin: 20px 0;">
        <h4>وصف المهمة:</h4>
        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; border-right: 3px solid #4a6fa5;">
            {{ $task->description }}
        </div>
    </div>
    
    @if($data['showEncouragement'])
    <div style="background-color: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0; border-right: 4px solid #ffc107;">
        <p><strong> الخطوة التالية:</strong></p>
        <ul style="margin-right: 20px;">
            <li>تأكد من تسجيل جميع ساعات العمل</li>
            <li>قدم ملاحظاتك حول تجربة التطوع</li>
            <li>شارك إنجازك مع أصدقائك</li>
            <li>تطلع على فرص تطوعية جديدة</li>
        </ul>
    </div>
    @endif
    
    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ $data['actionUrl'] }}" class="btn-primary">
            {{ $data['actionText'] }}
        </a>
        
        @if($newStatus == 'complete')
        <a href="{{ url('/feedbacks/create?task_id=' . $task->id) }}" class="btn-secondary">
            قدم ملاحظاتك
        </a>
        @endif
        
        <a href="{{ url('/tasks') }}" class="btn-secondary">
            جميع مهامي
        </a>
    </div>
    
    @if($newStatus == 'complete')
    <div style="margin-top: 25px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
        <h4> ساعات العمل المسجلة</h4>
        
        @if($task->taskHours()->exists())
        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <thead>
                <tr style="background: #e9ecef;">
                    <th style="padding: 8px; text-align: right; border: 1px solid #dee2e6;">التاريخ</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #dee2e6;">الساعات</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #dee2e6;">الملاحظات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($task->taskHours as $hour)
                <tr>
                    <td style="padding: 8px; border: 1px solid #dee2e6;">
                        {{ $hour->started_date->format('Y-m-d') }}
                        @if($hour->ended_date && $hour->ended_date != $hour->started_date)
                        إلى {{ $hour->ended_date->format('Y-m-d') }}
                        @endif
                    </td>
                    <td style="padding: 8px; border: 1px solid #dee2e6;">{{ $hour->hours }} ساعة</td>
                    <td style="padding: 8px; border: 1px solid #dee2e6;">{{ $hour->note }}</td>
                </tr>
                @endforeach
                <tr style="background: #f8f9fa; font-weight: bold;">
                    <td style="padding: 8px; border: 1px solid #dee2e6;" colspan="2">الإجمالي</td>
                    <td style="padding: 8px; border: 1px solid #dee2e6;">{{ $task->taskHours()->sum('hours') }} ساعة</td>
                </tr>
            </tbody>
        </table>
        @else
        <p style="color: #666; text-align: center;">لم يتم تسجيل أي ساعات عمل لهذه المهمة.</p>
        @endif
    </div>
    @endif
    
    <div style="margin-top: 25px; padding: 15px; background: #e8f4fd; border-radius: 5px;">
        <h4> معلومات التواصل</h4>
        <p>لأي استفسار بخصوص المهمة، يمكنك التواصل مع:</p>
        <div style="display: flex; justify-content: space-between; flex-wrap: wrap; margin-top: 10px;">
            <div>
                <strong>دعم المنصة:</strong><br>
                 support@tawaa.org<br>
                 800-123-4567
            </div>
            @if($task->application->coordinator)
            <div>
                <strong>منسق التطوع:</strong><br>
                 {{ $task->application->coordinator->name }}<br>
                 {{ $task->application->coordinator->email }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection