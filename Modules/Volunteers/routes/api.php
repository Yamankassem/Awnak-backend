<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {

    require __DIR__.'/api/admin-meta.php';
    require __DIR__.'/api/admin-volunteers.php';
    require __DIR__.'/api/volunteer-meta.php';

    require __DIR__.'/api/volunteer-profile.php';
    require __DIR__.'/api/volunteer.php';

});