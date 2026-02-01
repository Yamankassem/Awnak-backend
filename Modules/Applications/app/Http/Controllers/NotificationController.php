<?php

namespace Modules\Applications\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Applications\Notifications\TestNotification;
use Modules\Applications\Http\Requests\NotificationRequest\IndexNotificationRequest;
use Modules\Applications\Http\Requests\NotificationRequest\SendTestNotificationRequest;

/**
 * Notification Controller
 * 
 * Handles user notification operations including listing,
 * marking as read, deletion, and test notifications.
 * 
 * @package Modules\Applications\Http\Controllers
 * @author Your Name
 */
class NotificationController extends Controller
{
    /**
     * Display a listing of notifications.
     * 
     * @param IndexNotificationRequest $request
     * @return JsonResponse
     * 
     * @api GET /api/notifications
     * 
     * @example
     * GET /api/notifications?type=new_application&unread_only=true&per_page=20
     */
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
    
    /**
     * Display the specified notification.
     * 
     * @param string $id Notification ID
     * @return JsonResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * 
     * @api GET /api/notifications/{id}
     */
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
    
    /**
     * Mark a notification as read.
     * 
     * @param string $id Notification ID
     * @return JsonResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * 
     * @api POST /api/notifications/{id}/read
     */
    public function markAsRead(string $id): JsonResponse
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return $this->success(null, 'messages.notification_marked_as_read');
    }
    
    /**
     * Mark all notifications as read.
     * 
     * @return JsonResponse
     * 
     * @api POST /api/notifications/read-all
     */
    public function markAllAsRead(): JsonResponse
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        $unreadCount = Auth::user()->unreadNotifications()->count();
        
        return $this->success([
            'unread_count' => $unreadCount,
        ], 'messages.all_notifications_marked_as_read');
    }
    
    /**
     * Delete a specific notification.
     * 
     * @param string $id Notification ID
     * @return JsonResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * 
     * @api DELETE /api/notifications/{id}
     */
    public function destroy(string $id): JsonResponse
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();
        
        return $this->success(null, 'messages.notification_deleted');
    }
    
    /**
     * Delete all notifications for the current user.
     * 
     * @return JsonResponse
     * 
     * @api DELETE /api/notifications
     */
    public function destroyAll(): JsonResponse
    {
        Auth::user()->notifications()->delete();
        return $this->success(null, 'messages.all_notifications_deleted');
    }
    
    /**
     * Get count of unread notifications.
     * 
     * @return JsonResponse
     * 
     * @api GET /api/notifications/unread-count
     */
    public function unreadCount(): JsonResponse
    {
        $count = Auth::user()->unreadNotifications()->count();
        return $this->success(['unread_count' => $count], 'messages.unread_count_retrieved');
    }
    
    /**
     * Send a test notification to the current user.
     * 
     * @param SendTestNotificationRequest $request
     * @return JsonResponse
     * 
     * @api POST /api/notifications/send-test
     * @permission admin
     */
    public function sendTestNotification(SendTestNotificationRequest $request): JsonResponse
    {
        $user = Auth::user();
        $validated = $request->validated();
        $type = $validated['type'] ?? 'new_application';
        
        $notificationData = $this->getTestNotificationData($type);
        $user->notify(new TestNotification($notificationData));
        
        return $this->success([
            'type' => $type,
            'data' => $notificationData,
        ], 'messages.test_notification_sent');
    }
    
    /**
     * Get available notification types with translations.
     * 
     * @return array<string, string>
     */
    private function getNotificationTypes(): array
    {
        return [
            'new_application' => 'New Request',
            'application_status_changed' => 'Change Request Status',
            'new_task' => 'New Task',
            'task_status_changed' => 'Change Task Status',
            'hours_logged' => 'Log Hours',
            'new_feedback' => 'New Review',
            'system' => 'System Notifications',
            'reminder' => 'Reminder',
            'report' => 'Report',
        ];
    }
    
    /**
     * Generate test notification data based on type.
     * 
     * @param string $type Notification type
     * @return array<string, mixed>
     */
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
                $data['title'] = 'New Experimental Volunteer Request';
                $data['message'] = 'A new volunteer submitted a request for the opportunity: Experimental Opportunity';
                $data['icon'] = 'fa-user-plus';
                $data['color'] = 'primary';
                break;
                
            case 'application_status_changed':
                $data['title'] = 'Change Request Status - Experimental';
                $data['message'] = 'Your request status has been changed to: Accepted';
                $data['icon'] = 'fa-sync-alt';
                $data['color'] = 'success';
                break;
                
            case 'new_task':
                $data['title'] = 'New Experimental Task';
                $data['message'] = 'A new task has been assigned to you: Experimental Task';
                $data['icon'] = 'fa-tasks';
                $data['color'] = 'warning';
                break;
                
            case 'hours_logged':
                $data['title'] = 'Hours Logging - Experimental';
                $data['message'] = '5 hours have been logged for the task: Experimental Task';
                $data['icon'] = 'fa-clock';
                $data['color'] = 'info';
                break;
        }
        
        return $data;
    }
    
    /**
     * Return paginatedNotifications JSON response with additional data.
     * 
     * @param LengthAwarePaginator $paginator
     * @param string $message Translation key
     * @param int $status HTTP status code
     * @param array $extraData Additional data to include
     * @return JsonResponse
     */
    private function paginatedNotifications(LengthAwarePaginator $paginator, string $message, int $status = 200, array $extraData = []): JsonResponse
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
        
        return self::paginated($paginator, 'notifications.fetched');
    }
}