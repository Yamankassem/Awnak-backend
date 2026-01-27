<?php

namespace Modules\Applications\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Applications\Http\Requests\NotificationRequest\IndexNotificationRequest;
use Modules\Applications\Http\Requests\NotificationRequest\SendTestNotificationRequest;

class NotificationController extends Controller
{
    public function index(IndexNotificationRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $perPage = $validated['per_page'] ?? 20;
        $type = $validated['type'] ?? null;
        $unreadOnly = $validated['unread_only'] ?? false;
        
        $query = Auth::user()->notifications();
        
        if ($type) {
            $query->where('data->type', $type);
        }
        
        if ($unreadOnly) {
            $query->whereNull('read_at');
        }
        
        $notifications = $query->orderBy('created_at', 'desc')->paginate($perPage);
        $unreadCount = Auth::user()->unreadNotifications()->count();
        
        return $this->paginated($notifications, 'messages.notifications_retrieved', 200, [
            'unread_count' => $unreadCount,
            'types' => $this->getNotificationTypes(),
        ]);
    }
    
    public function show(string $id): JsonResponse
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        
        if (!$notification->read_at) {
            $notification->markAsRead();
        }
        
        return $this->success([
            'notification' => $notification,
            'data' => $notification->data,
        ], 'messages.notification_retrieved');
    }
    
    public function markAsRead(string $id): JsonResponse
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return $this->success(null, 'messages.notification_marked_as_read');
    }
    
    public function markAllAsRead(): JsonResponse
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        $unreadCount = Auth::user()->unreadNotifications()->count();
        
        return $this->success([
            'unread_count' => $unreadCount,
        ], 'messages.all_notifications_marked_as_read');
    }
    
    public function destroy(string $id): JsonResponse
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();
        
        return $this->success(null, 'messages.notification_deleted');
    }
    
    public function destroyAll(): JsonResponse
    {
        Auth::user()->notifications()->delete();
        return $this->success(null, 'messages.all_notifications_deleted');
    }
    
    public function unreadCount(): JsonResponse
    {
        $count = Auth::user()->unreadNotifications()->count();
        return $this->success(['unread_count' => $count], 'messages.unread_count_retrieved');
    }
    
    public function sendTestNotification(SendTestNotificationRequest $request): JsonResponse
    {
        $user = Auth::user();
        $validated = $request->validated();
        $type = $validated['type'] ?? 'new_application';
        
        $notificationData = $this->getTestNotificationData($type);
        $user->notify(new \Modules\Applications\Notifications\TestNotification($notificationData));
        
        return $this->success([
            'type' => $type,
            'data' => $notificationData,
        ], 'messages.test_notification_sent');
    }
    
    private function getNotificationTypes(): array
    {
        return [
            'new_application' => 'طلب جديد',
            'application_status_changed' => 'تغيير حالة الطلب',
            'new_task' => 'مهمة جديدة',
            'task_status_changed' => 'تغيير حالة المهمة',
            'hours_logged' => 'تسجيل ساعات',
            'new_feedback' => 'تقييم جديد',
            'system' => 'إشعارات النظام',
            'reminder' => 'تذكير',
            'report' => 'تقرير',
        ];
    }
    
    private function getTestNotificationData(string $type): array
    {
        $data = [
            'title' => '',
            'message' => '',
            'type' => $type,
            'icon' => 'fa-bell',
            'color' => 'info',
            'url' => '/dashboard',
        ];
        
        switch ($type) {
            case 'new_application':
                $data['title'] = 'طلب تطوع جديد تجريبي';
                $data['message'] = 'قدم متطوع جديد طلباً للفرصة: فرصة تجريبية';
                $data['icon'] = 'fa-user-plus';
                $data['color'] = 'primary';
                break;
                
            case 'application_status_changed':
                $data['title'] = 'تغيير حالة الطلب تجريبي';
                $data['message'] = 'تم تغيير حالة طلبك إلى: مقبول';
                $data['icon'] = 'fa-sync-alt';
                $data['color'] = 'success';
                break;
                
            case 'new_task':
                $data['title'] = 'مهمة جديدة تجريبية';
                $data['message'] = 'تم تعيين مهمة جديدة لك: المهمة التجريبية';
                $data['icon'] = 'fa-tasks';
                $data['color'] = 'warning';
                break;
                
            case 'hours_logged':
                $data['title'] = 'تسجيل ساعات تجريبي';
                $data['message'] = 'تم تسجيل 5 ساعات للمهمة: المهمة التجريبية';
                $data['icon'] = 'fa-clock';
                $data['color'] = 'info';
                break;
        }
        
        return $data;
    }
    
    private function paginated(\Illuminate\Pagination\LengthAwarePaginator $paginator, string $message, int $status = 200, array $extraData = []): JsonResponse
    {
        $responseData = [
            'status' => 'success',
            'message' => trans($message),
            'data' => $paginator->items(),
            'pagination' => [
                'total' => $paginator->total(),
                'count' => $paginator->count(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'total_pages' => $paginator->lastPage(),
            ],
        ];

        if (!empty($extraData)) {
            $responseData = array_merge($responseData, $extraData);
        }
        
        return response()->json($responseData, $status);
    }
}