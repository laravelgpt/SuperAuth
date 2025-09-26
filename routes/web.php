<?php

use Illuminate\Support\Facades\Route;
use SuperAuth\Http\Controllers\AuthController;
use SuperAuth\Http\Controllers\AdminController;
use SuperAuth\Http\Controllers\DashboardController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('superauth.login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('superauth.register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('superauth.logout');

// Dashboard Routes
Route::get('/', [DashboardController::class, 'index'])->name('superauth.dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('superauth.dashboard');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/roles', [AdminController::class, 'roles'])->name('admin.roles');
});