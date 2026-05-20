<?php

 use Illuminate\Support\Facades\Route;

  
use App\Http\Controllers\Api\Admin\AdminOrderController;

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::patch('/orders/{order}/approve', [AdminOrderController::class, 'approve']);
    Route::patch('/orders/{order}/reject', [AdminOrderController::class, 'reject']);
    Route::patch('/orders/{order}/request', [AdminOrderController::class, 'request']);
    Route::patch('/orders/{order}/payment', [AdminOrderController::class, 'updatePayment']);

    Route::get('/orders', [AdminOrderController::class,'index']);
    Route::get('/orders/{order}', [AdminOrderController::class,'show']);


    // Delete a single order if it is pending, cancelled, rejected, or auto_rejected
    Route::delete('/orders/{order}', [AdminOrderController::class,'destroy']);

    // Delete all orders with a specific status
    Route::delete('/orders/remove-all/{status}', [AdminOrderController::class,'destroyAll']);


    Route::get('/orders/payments/all', [AdminOrderController::class,'payments']);
    Route::get('/orders/losses/all', [AdminOrderController::class,'losses']);

});