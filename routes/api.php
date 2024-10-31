<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\RoomController;
use App\Http\Controllers\Api\V1\ReserveController;

Route::apiResource('rooms', RoomController::class);
Route::post('reserves', [ReserveController::class, 'store']);


