<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TaskPriorityController;
use App\Http\Controllers\Api\TaskSubmissionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/me', [AuthController::class, 'me']);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function (): void {
    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('statuses', [StatusController::class, 'index']);
    Route::get('priorities', [TaskPriorityController::class, 'index']);
    Route::get('deadlines', [TaskController::class, 'deadlines']);

    Route::apiResource('courses', CourseController::class);
    Route::apiResource('tasks', TaskController::class);
    Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus']);

    Route::get('tasks/{task}/submission', [TaskSubmissionController::class, 'show']);
    Route::post('tasks/{task}/submission', [TaskSubmissionController::class, 'store']);
    Route::match(['put', 'patch'], 'tasks/{task}/submission', [TaskSubmissionController::class, 'update']);
    Route::delete('tasks/{task}/submission', [TaskSubmissionController::class, 'destroy']);
});
