<?php

use App\Http\Web\Controllers\ProfileController;
use App\Http\Web\Controllers\ProjectController;
use App\Http\Web\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::middleware(['auth', 'verified'])
    ->prefix('profile')
    ->group(function () {

    Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])
    ->prefix('projects')
    ->group(function () {

    Route::get('/', [ProjectController::class, 'list'])->name('projects.list');

    Route::get('/create', [ProjectController::class, 'create']);
    Route::post('/', [ProjectController::class, 'store']);
    
    Route::get('/{project}', [ProjectController::class, 'edit']);
    Route::put('/{project}', [ProjectController::class, 'update']);

    Route::delete('/{project}', [ProjectController::class, 'delete'])->name('projects.delete');
});

Route::middleware(['auth', 'verified'])
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

require __DIR__.'/auth.php';
