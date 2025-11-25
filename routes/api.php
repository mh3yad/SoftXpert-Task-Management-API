<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

require __DIR__ .'/auth.php';
Route::middleware('auth:sanctum')->group(function () {
   Route::apiResource('tasks', TaskController::class);
});