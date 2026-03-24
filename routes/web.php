<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PendingApprovalController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Pending approval page
Route::get('pending-approval', [PendingApprovalController::class, 'index'])->name('pending.approval');

/*
|--------------------------------------------------------------------------
| Authenticated & Approved Users
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'approved'])->group(function () {
    // Task management
    Route::resource('tasks', TaskController::class)->except(['destroy']);
    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy'); // soft delete
    Route::post('tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
    Route::patch('tasks/{id}/restore', [TaskController::class, 'restore'])->name('tasks.restore');
    Route::delete('tasks/{id}/force-delete', [TaskController::class, 'forceDelete'])->name('tasks.forceDelete');

    // Admin-only user management
    Route::middleware('can:viewAny,App\Models\User')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class)->only(['index', 'show', 'destroy']);
        Route::post('users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
        Route::post('users/{user}/promote', [UserController::class, 'promote'])->name('users.promote');
        Route::resource('tags', \App\Http\Controllers\Admin\TagController::class)->except(['show']);
    });

    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
