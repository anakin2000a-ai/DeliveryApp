<?php

use App\Http\Controllers\Api\Admin\UserAdminController;
use Illuminate\Support\Facades\Route;

  
Route::middleware('auth:sanctum','admin')->group(function () {
    Route::get('/admin/users', [UserAdminController::class, 'index']);
    Route::put('/admin/users/{user}', [UserAdminController::class, 'update']);
     
});