<?php

use App\Http\Controllers\Api\Admin\RestaurantController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('restaurants', RestaurantController::class);
    Route::get('restaurants/{restaurant}/menu-categories', [RestaurantController::class, 'getByRestaurant']);
});