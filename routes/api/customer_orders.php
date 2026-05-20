<?php 
use App\Http\Controllers\Api\User\OrderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'customer'])->prefix('customer')->group(function () {
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel']);
    Route::put('/orders/{order}/', [OrderController::class, 'update']);
});
Route::middleware(['auth:sanctum','customer'])->get('/notifications', [NotificationController::class,'index']);