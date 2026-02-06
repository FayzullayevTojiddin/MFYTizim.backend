<?php

use App\Http\Controllers\Task\GetListTaskController;
use App\Http\Controllers\Task\GetTaskController;
use App\Http\Controllers\Task\PostTaskController;
use App\Http\Controllers\User\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', AuthController::class);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tasks', GetListTaskController::class);

    Route::get('/tasks/{task}', GetTaskController::class);

    Route::post('/tasks/{task}', PostTaskController::class);
});