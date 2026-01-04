<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskCategoryController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\MeetController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/auth', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/update-profile', [UserController::class, 'update'])->middleware('auth:sanctum');

Route::get('/task-categories', [TaskCategoryController::class, 'index'])->middleware('auth:sanctum');
Route::get('/tasks', [TaskController::class, 'index'])->middleware('auth:sanctum');
Route::post('/tasks', [TaskController::class, 'store'])->middleware('auth:sanctum');

Route::get('/meets', [MeetController::class, 'index'])->middleware('auth:sanctum');
Route::get('/profile', [UserController::class, 'profile'])->middleware('auth:sanctum');