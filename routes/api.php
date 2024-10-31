<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\RoomController;

Route::apiResource('rooms', RoomController::class);

