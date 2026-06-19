<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AdminController;

// Redirect beranda ke dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Rute Otentikasi (Mock tampilan guest)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard & Fitur Umum
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/kelas', [CourseController::class, 'index'])->name('courses');
Route::get('/kelas/{id}', [CourseController::class, 'show'])->name('courses.show');

// Mock Aksi Kelas (Buat, Gabung, Hapus, etc.)
Route::post('/kelas/create', [CourseController::class, 'create'])->name('courses.create');
Route::post('/kelas/join', [CourseController::class, 'join'])->name('courses.join');
Route::delete('/kelas/{id}', [CourseController::class, 'destroy'])->name('courses.destroy');
Route::post('/kelas/{id}/materials', [MaterialController::class, 'store'])->name('materials.store');
Route::post('/kelas/{id}/tasks', [TaskController::class, 'store'])->name('tasks.store');

// Detail Materi & Tugas
Route::get('/kelas/{course_id}/materials/{material_id}', [MaterialController::class, 'show'])->name('materials.show');
Route::delete('/kelas/{course_id}/materials/{material_id}', [MaterialController::class, 'destroy'])->name('materials.delete');
Route::get('/kelas/{course_id}/tasks/{task_id}', [TaskController::class, 'show'])->name('tasks.show');
Route::get('/kelas/{course_id}/tasks/{task_id}/download', [TaskController::class, 'download'])->name('tasks.download');
Route::delete('/kelas/{course_id}/tasks/{task_id}', [TaskController::class, 'destroy'])->name('tasks.delete');
Route::post('/kelas/{course_id}/tasks/{task_id}/submit', [TaskController::class, 'submit'])->name('tasks.submit');
Route::delete('/kelas/{course_id}/tasks/{task_id}/submission', [TaskController::class, 'cancelSubmission'])->name('tasks.cancelSubmission');
Route::get('/kelas/{course_id}/tasks/{task_id}/submissions', [TaskController::class, 'showSubmissions'])->name('tasks.showSubmissions');
Route::post('/kelas/{course_id}/tasks/{task_id}/grade/{student_id}', [TaskController::class, 'grade'])->name('tasks.grade');

// Interaksi Komentar & Member
Route::post('/kelas/{course_id}/{type}/{item_id}/comments', [CourseController::class, 'storeComment'])->name('comments.store');
Route::post('/kelas/{course_id}/kick/{user_id}', [CourseController::class, 'kick'])->name('courses.kick');
Route::post('/kelas/{course_id}/leave', [CourseController::class, 'leave'])->name('courses.leave');

// Daftar Tugas, Pengaturan, & Laporan
Route::get('/tasks', [TaskController::class, 'index'])->name('tasks');
Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
Route::post('/settings', [DashboardController::class, 'updateSettings'])->name('settings.update');
Route::post('/settings/password', [DashboardController::class, 'updatePassword'])->name('settings.password');
Route::get('/report', [DashboardController::class, 'report'])->name('report');

// Admin Control Panel
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::post('/users/{id}/toggle-role', [AdminController::class, 'toggleRole'])->name('admin.users.toggle-role');
    Route::get('/kelas', [AdminController::class, 'courses'])->name('admin.courses');
    Route::delete('/kelas/{id}', [AdminController::class, 'deleteCourse'])->name('admin.courses.delete');
    Route::delete('/kelas/{course_id}/kick/{user_id}', [AdminController::class, 'kickMember'])->name('admin.courses.kick');
});
Route::post('/kelas', [CourseController::class, 'store'])->name('courses.store');
Route::get('/register', function () {
    return view('auth/register');
})->name('register');
Route::post('/register', [AuthController::class, 'register']);