<?php

use Illuminate\Support\Facades\Route;
use Modules\Applications\Http\Controllers\Api\CalendarController;
use Modules\Applications\Http\Controllers\Api\NotificationController;
use Modules\Applications\Http\Controllers\Api\TasksController\TaskController;
use Modules\Applications\Http\Controllers\Api\FeedbackController\FeedbackController;
use Modules\Applications\Http\Controllers\Api\TaskHoursController\TaskHourController;
use Modules\Applications\Http\Controllers\Api\ApplicationsController\ApplicationController;

Route::middleware(['auth:sanctum', 'throttle:api'])->prefix('v1')->group(function () {

    // Application
    Route::apiResource('applications', ApplicationController::class)->names('applications');
    Route::patch('/applications/{id}/status', [ApplicationController::class, 'updateStatus']);
    Route::get('/pending', [ApplicationController::class, 'pending']);
    Route::get('/waiting-list', [ApplicationController::class, 'waitingList']);
    Route::get('/approved', [ApplicationController::class, 'approved']);
    Route::get('/search', [ApplicationController::class, 'search']);


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

});
