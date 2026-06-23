<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\CourseApiController;
use App\Http\Controllers\Api\MaterialApiController;
use App\Http\Controllers\Api\TaskApiController;
use App\Http\Controllers\Api\AdminApiController;

// PUBLIC ROUTES
Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/register', [AuthApiController::class, 'register']);

// PROTECTED ROUTES
Route::middleware('auth:sanctum')->group(function () {

    // Authentication
    Route::post('/logout', [AuthApiController::class, 'logout']);

    // Course aaaa
    Route::get('/courses', [CourseApiController::class, 'index']);
    Route::get('/courses/{id}', [CourseApiController::class, 'show']);
    Route::post('/courses', [CourseApiController::class, 'store']);
    Route::delete('/courses/{id}', [CourseApiController::class, 'destroy']);

    Route::post('/courses/join', [CourseApiController::class, 'join']);
    Route::post('/courses/{id}/leave', [CourseApiController::class, 'leave']);
    Route::post('/courses/{course_id}/kick/{user_id}', [CourseApiController::class, 'kick']);

    Route::post('/courses/{course_id}/{type}/{item_id}/comments', [CourseApiController::class, 'storeComment']);

    // Material
    Route::post('/courses/{id}/materials', [MaterialApiController::class, 'store']);
    Route::get('/courses/{course_id}/materials/{material_id}', [MaterialApiController::class, 'show']);
    Route::delete('/courses/{course_id}/materials/{material_id}', [MaterialApiController::class, 'destroy']);

    // Task
    Route::get('/tasks', [TaskApiController::class, 'index']);
    Route::post('/courses/{id}/tasks', [TaskApiController::class, 'store']);
    Route::get('/courses/{course_id}/tasks/{task_id}', [TaskApiController::class, 'show']);
    Route::get('/courses/{course_id}/tasks/{task_id}/download', [TaskApiController::class, 'download']);
    Route::delete('/courses/{course_id}/tasks/{task_id}', [TaskApiController::class, 'destroy']);
    Route::post('/courses/{course_id}/tasks/{task_id}/submit', [TaskApiController::class, 'submit']);
    Route::delete('/courses/{course_id}/tasks/{task_id}/submission', [TaskApiController::class, 'cancelSubmission']);
    Route::get('/courses/{course_id}/tasks/{task_id}/submissions', [TaskApiController::class, 'showSubmissions']);
    Route::post('/courses/{course_id}/tasks/{task_id}/grade/{student_id}', [TaskApiController::class, 'grade']);

    // Admin
    Route::prefix('admin')->group(function () {

        Route::get('/dashboard', [AdminApiController::class, 'dashboard']);

        Route::get('/users', [AdminApiController::class, 'users']);

        Route::delete('/users/{id}', [AdminApiController::class, 'deleteUser']);

        Route::post('/users/{id}/toggle-role', [AdminApiController::class, 'toggleRole']);

        Route::get('/courses', [AdminApiController::class, 'courses']);

        Route::delete('/courses/{id}', [AdminApiController::class, 'deleteCourse']);

        Route::delete('/courses/{course_id}/kick/{user_id}', [AdminApiController::class, 'kickMember']);
    });

});