<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

require __DIR__ .'/auth.php';
Route::middleware('auth:sanctum')->group(function () {
   Route::apiResource('tasks', TaskController::class);
   Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus']);
   Route::post('/tasks/{task}/dependencies', [TaskController::class, 'addDependency']);
   Route::delete('/tasks/{task}/dependencies/{dependency}', [TaskController::class, 'removeDependency']);
});