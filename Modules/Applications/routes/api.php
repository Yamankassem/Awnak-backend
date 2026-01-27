<?php

use Illuminate\Support\Facades\Route;
use Modules\Applications\Http\Controllers\CalendarController;
use Modules\Applications\Http\Controllers\NotificationController;
use Modules\Applications\Http\Controllers\TasksController\TaskController;
use Modules\Applications\Http\Controllers\FeedbackController\FeedbackController;
use Modules\Applications\Http\Controllers\TaskHoursController\TaskHourController;
use Modules\Applications\Http\Controllers\ApplicationsController\ApplicationController;

Route::middleware(['auth:sanctum', 'throttle:api'])->prefix('v1')->group(function () {

    // Application
    Route::apiResource('applications', ApplicationController::class)->names('applications');
    Route::patch('/applications/{id}/status', [ApplicationController::class, 'updateStatus']);

    //  Task
    Route::apiResource('tasks', TaskController::class)->names('tasks');
    Route::patch('/tasks/{id}/status', [TaskController::class, 'updateStatus']);

    // TaskHour
    Route::apiResource('task-hours', TaskHourController::class)->names('taskHours');


    // Feedback
    Route::apiResource('feedbacks', FeedbackController::class)->names('feedbacks');


    // Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::get('/unread-count', [NotificationController::class, 'unreadCount']);
        Route::get('/{id}', [NotificationController::class, 'show']);
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead']);
        Route::delete('/{id}', [NotificationController::class, 'destroy']);
        Route::delete('/', [NotificationController::class, 'destroyAll']);
       
        Route::post('/send-test', [NotificationController::class, 'sendTestNotification'])->middleware('can:admin');
    });

    // Application Trash 
    Route::prefix('applications')->group(function () {
        Route::get('/trashed', [ApplicationController::class, 'trashed'])->middleware('can:viewTrashed,App\Models\Application');
        Route::post('/{id}/restore', [ApplicationController::class, 'restore'])->middleware('can:restore,App\Models\Application');
        Route::delete('/{id}/force', [ApplicationController::class, 'forceDelete'])->middleware('can:forceDelete,App\Models\Application');
    });

    // Calendar

    Route::prefix('calendar')->group(function () {
    Route::get('/', [CalendarController::class, 'index']);
    Route::get('/upcoming', [CalendarController::class, 'upcoming']);
    Route::get('/reminders', [CalendarController::class, 'reminders']);
    Route::get('/search', [CalendarController::class, 'search']);
    Route::get('/statistics', [CalendarController::class, 'statistics']);
    Route::post('/', [CalendarController::class, 'store']);
    Route::get('/{id}', [CalendarController::class, 'show']);
    Route::put('/{id}', [CalendarController::class, 'update']);
    Route::delete('/{id}', [CalendarController::class, 'destroy']);
    Route::patch('/{id}/status', [CalendarController::class, 'updateStatus']);
});
});
