<?php

 use Illuminate\Support\Facades\Route;

  
use App\Http\Controllers\Api\Admin\AdminOrderController;

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::patch('/orders/{order}/approve', [AdminOrderController::class, 'approve']);
    Route::patch('/orders/{order}/reject', [AdminOrderController::class, 'reject']);
});