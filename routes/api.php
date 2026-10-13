<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LabController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/mobile/token', [AuthController::class, 'token'])->middleware('throttle:5,1');
    Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
        Route::get('/products', [LabController::class, 'products']);
        Route::post('/products', [LabController::class, 'saveProduct']);
        Route::put('/products/{product}', [LabController::class, 'saveProduct']);
        Route::get('/orders', [LabController::class, 'orders']);
        Route::post('/orders', [LabController::class, 'storeOrder']);
        Route::patch('/orders/{order}/status', [LabController::class, 'status']);
        Route::get('/summary', [LabController::class, 'summary']);
        Route::delete('/mobile/token', [AuthController::class, 'revoke']);
    });
});
