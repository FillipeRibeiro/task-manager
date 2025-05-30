<?php

use App\Http\Api\Controllers\AuthController;
use App\Http\Api\Controllers\ProfileController;
use App\Http\Api\Controllers\ProjectController;
use App\Http\Api\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')
    ->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
});

Route::middleware('auth:sanctum')
    ->prefix('profile')
    ->group(function () {

    Route::patch('/', [ProfileController::class, 'update']);
    Route::delete('/', [ProfileController::class, 'destroy']);
});

Route::middleware('auth:sanctum')
    ->prefix('projects')
    ->group(function () {

    Route::get('/', [ProjectController::class, 'list']);
    Route::get('/show/{project}', [ProjectController::class, 'show']);

    Route::post('/', [ProjectController::class, 'store']);
    Route::patch('/{project}', [ProjectController::class, 'update']);
    Route::delete('/{project}', [ProjectController::class, 'delete']);
});

Route::middleware('auth:sanctum')
    ->prefix('tasks')
    ->group(function () {

    Route::post('/', [TaskController::class, 'store']);

    Route::get('/show/{task}', [TaskController::class, 'show']);
    Route::get('list/{project}', [TaskController::class, 'list']);

    Route::put('/{task}', [TaskController::class, 'update']);
    Route::put('/status/{task}', [TaskController::class, 'updateStatus']);

    Route::delete('/{task}', [TaskController::class, 'delete']);
});
