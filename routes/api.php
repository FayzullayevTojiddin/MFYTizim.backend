<?php

use App\Http\Controllers\Meet\AceptMeetController;
use App\Http\Controllers\Meet\GetListMeetController;
use App\Http\Controllers\Task\GetListTaskController;
use App\Http\Controllers\Task\GetTaskController;
use App\Http\Controllers\Task\PostTaskController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\GetProfileController;
use App\Http\Controllers\Work\GetRankWorkerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\LogoutController;


Route::post('/auth/login', AuthController::class);


Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/logout', LogoutController::class);

    Route::get('/profile', GetProfileController::class);

    Route::get('/tasks', GetListTaskController::class);

    Route::get('/tasks/{task}', GetTaskController::class);

    Route::post('/tasks/{task}', PostTaskController::class);

    Route::get('/meets', GetListMeetController::class);

    Route::post('/meets/{meet}', AceptMeetController::class);

    Route::get('/rank', GetRankWorkerController::class);
});