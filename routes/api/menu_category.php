<?php
use App\Http\Controllers\Api\Admin\MenuCategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'admin'])
    ->prefix('admin')
    ->group(function () {
        Route::apiResource('menu-categories', MenuCategoryController::class);
        Route::patch('menu-categories/{id}/restore', [MenuCategoryController::class, 'restore']);
        Route::delete('menu-categories/{id}/force-delete', [MenuCategoryController::class, 'forceDelete']);
});