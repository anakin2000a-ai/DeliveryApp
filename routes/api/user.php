<?php

use App\Http\Controllers\Api\User\UserController;
use Illuminate\Support\Facades\Route;

 Route::post('/register', [UserController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
     Route::put('/profile', [UserController::class, 'update']);
     
});