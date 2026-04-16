<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;

// ================= PUBLIC =================
Route::get('/', function () {
    return view('dashboard.index');
})->name('home');

// ================= AUTH =================
Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
});

// ================= PROTECTED =================
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ================= PROJECT =================
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');

    // 🔥 TRUY CẬP PROJECT BẰNG MÃ
    Route::get('/projects/access', [ProjectController::class, 'access'])->name('projects.access');

    // 🔥 CHI TIẾT PROJECT (QUAN TRỌNG)
    Route::get('/projects/{id}', [ProjectController::class, 'show'])->name('projects.show');

    // 🔥 ADD MEMBER
    Route::post('/projects/{id}/add-member', [ProjectController::class, 'addMember'])->name('projects.addMember');

    // (sắp tới dùng)
     Route::post('/projects/{id}/tasks', [ProjectController::class, 'addTask'])->name('projects.addTask');

    // 🔥 Kanban Board
    Route::get('/projects/{id}/kanban', [ProjectController::class, 'kanban'])->name('projects.kanban');

    // 🔥 Lịch trình công việc
    Route::get('/projects/{id}/schedule', [ProjectController::class, 'schedule'])->name('projects.schedule');

    // 🔥 Thành viên
    Route::get('/projects/{id}/members', [ProjectController::class, 'members'])->name('projects.members');

    // 🔥 Cập nhật status task
    Route::put('/tasks/{id}/status', [ProjectController::class, 'updateTaskStatus'])->name('tasks.updateStatus');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::delete('/tasks/{id}', [ProjectController::class, 'deleteTask'])->name('tasks.delete');
Route::get('/projects/{id}/trash', [ProjectController::class, 'trash'])
    ->name('projects.trash');



    Route::post('/tasks/{id}/restore', [ProjectController::class, 'restoreTask'])
    ->name('tasks.restore');

Route::delete('/tasks/{id}/force-delete', [ProjectController::class, 'forceDeleteTask'])
    ->name('tasks.forceDelete');