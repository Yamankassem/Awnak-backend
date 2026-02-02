<?php

use Illuminate\Support\Facades\Route;
use Modules\Applications\Http\Controllers\TaskController;
use Modules\Applications\Http\Controllers\FeedbackController;
use Modules\Applications\Http\Controllers\TaskHourController;
use Modules\Applications\Http\Controllers\ApplicationController;
use Modules\Applications\Http\Controllers\NotificationController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {

    // Application
    Route::apiResource('applications', ApplicationController::class)->names('applications');
    Route::patch('/applications/{application}/status', [ApplicationController::class, 'updateStatus']);
    Route::get('/pending', [ApplicationController::class, 'pending']);
    Route::get('/waiting-list', [ApplicationController::class, 'waitingList']);
    Route::get('/approved', [ApplicationController::class, 'approved']);
    Route::get('/search', [ApplicationController::class, 'search']);


    //  Task
    Route::apiResource('tasks', TaskController::class)->names('tasks');
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus']);

    // TaskHour
    Route::apiResource('task-hours', TaskHourController::class)->names('taskHours');


    // Feedback
    Route::apiResource('feedbacks', FeedbackController::class)->names('feedbacks');


    // Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::get('/unread-count', [NotificationController::class, 'unreadCount']);
        Route::get('/{notification}', [NotificationController::class, 'show']);
        Route::post('/{notification}/read', [NotificationController::class, 'markAsRead']);
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead']);
        Route::delete('/{notification}', [NotificationController::class, 'destroy']);
        Route::delete('/', [NotificationController::class, 'destroyAll']);
        Route::post('/send-test', [NotificationController::class, 'sendTestNotification'])->middleware('can:super_admin');
    });

});
