<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\RoomController;
use App\Http\Controllers\Api\V1\ReserveController;
use App\Http\Controllers\Api\V1\AuthController;

Route::apiResource('rooms', RoomController::class);
Route::post('/reserves/{id}/guest', [ReserveController::class, 'storeGuest']);
Route::post('/reserves/{id}/payment', [ReserveController::class, 'storePayment']);
Route::post('/reserves/{id}/daily', [ReserveController::class, 'storeDaily']);
Route::post('reserves', [ReserveController::class, 'store']);
Route::get('reserves', [ReserveController::class, 'index']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
