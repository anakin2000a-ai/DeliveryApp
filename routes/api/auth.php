<?php

use App\Http\Controllers\Api\AuthController;
 use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
 
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
     Route::get('/me', function () {
        return response()->json([
            'user' => auth()->user()->load('addresses'),
        ]);
    });
});