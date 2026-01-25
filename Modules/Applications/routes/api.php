<?php

use Illuminate\Support\Facades\Route;
use Modules\Applications\Http\Controllers\TasksController\TaskController;
use Modules\Applications\Http\Controllers\FeedbackController\FeedbackController;
use Modules\Applications\Http\Controllers\TaskHoursController\TaskHourController;
use Modules\Applications\Http\Controllers\ApplicationController\ApplicationController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {

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
});
