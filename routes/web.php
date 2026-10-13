<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LabController;
use Illuminate\Support\Facades\Route;

Route::view('/login', 'login')->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/', [LabController::class, 'panel'])->name('aurea.inicio');
    Route::get('/nuevo-pedido', [LabController::class, 'newOrderPage'])->name('aurea.nuevo');
    Route::get('/pedidos', [LabController::class, 'ordersPage'])->name('aurea.pedidos');
    Route::get('/productos', [LabController::class, 'productsPage'])->name('aurea.productos');
    Route::get('/resumen', [LabController::class, 'summaryPage'])->name('aurea.resumen');
});
