<?php

use App\Http\Api\Controllers\ProfileController;
use App\Http\Api\Controllers\ProjectController;
use App\Http\Api\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// adicionar rotas de autenticação com sanctum

Route::middleware('auth:sanctum')
    ->prefix('profile')
    ->group(function () {

    Route::patch('/', [ProfileController::class, 'update']);
    Route::delete('/', [ProfileController::class, 'destroy']);
});

Route::middleware('auth:sanctum')
    ->prefix('projects')
    ->group(function () {

    Route::get('/', [ProjectController::class, 'list'])->name('projects.list');
    Route::get('/show/{project}', [ProjectController::class, 'show']);

    Route::post('/', [ProjectController::class, 'store']);
    Route::patch('/{project}', [ProjectController::class, 'update']);
    Route::delete('/{project}', [ProjectController::class, 'delete']);
});

Route::middleware('auth:sanctum')
    ->prefix('tasks')
    ->group(function () {

    Route::post('/', [TaskController::class, 'store']);

    Route::get('/create/{project}', [TaskController::class, 'create']);
    Route::get('list/{project}', [TaskController::class, 'list'])->name('tasks.list');
    Route::get('/edit/{task}', [TaskController::class, 'edit']);

    Route::put('/{task}', [TaskController::class, 'update']);
    Route::put('/status/{task}', [TaskController::class, 'updateStatus']);

    Route::delete('/{task}', [TaskController::class, 'delete'])->name('tasks.delete');
});
