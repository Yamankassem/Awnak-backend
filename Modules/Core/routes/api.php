<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    require __DIR__ . '/api/v1/users.php';
    require __DIR__ . '/api/v1/roles.php';

});