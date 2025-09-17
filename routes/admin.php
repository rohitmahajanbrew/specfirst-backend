<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProfileController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "admin" middleware group. Make something great!
|
*/

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Public routes (no authentication required)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
    });

    // Protected routes (authentication required)
    Route::middleware('auth:admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Logout
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Users Management
        Route::resource('users', UserController::class);
        Route::post('/users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulk-delete');

        // Profile Management
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

        // Projects Management (to be implemented)
        Route::prefix('projects')->name('projects.')->group(function () {
            Route::get('/', function () {
                return view('admin.projects.index');
            })->name('index');
        });

        // Vendors Management (to be implemented)
        Route::prefix('vendors')->name('vendors.')->group(function () {
            Route::get('/', function () {
                return view('admin.vendors.index');
            })->name('index');
        });

        // Interviews Management (to be implemented)
        Route::prefix('interviews')->name('interviews.')->group(function () {
            Route::get('/', function () {
                return view('admin.interviews.index');
            })->name('index');
        });

        // Analytics (to be implemented)
        Route::prefix('analytics')->name('analytics.')->group(function () {
            Route::get('/', function () {
                return view('admin.analytics.index');
            })->name('index');
        });

        // Settings (to be implemented)
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', function () {
                return view('admin.settings.index');
            })->name('index');
        });
    });
});
