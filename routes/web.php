<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\OtpAuthController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Admin panel access
Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
});

// OTP Authentication Routes (Unified Login/Signup)
Route::prefix('auth')->group(function () {
    // Unified OTP Authentication
    Route::post('send-otp', [OtpAuthController::class, 'sendOtp']);
    Route::post('verify-otp', [OtpAuthController::class, 'verifyOtp']);
    Route::post('resend-otp', [OtpAuthController::class, 'resendOtp']);
    
    // Logout
    Route::post('logout', [OtpAuthController::class, 'logout'])->middleware('auth');
    
    // Get current user
    Route::get('me', [OtpAuthController::class, 'me'])->middleware('auth');
    
    // Token management
    Route::middleware('auth')->group(function () {
        Route::post('refresh-token', [OtpAuthController::class, 'refreshToken']);
        Route::get('tokens', [OtpAuthController::class, 'getTokens']);
        Route::delete('tokens', [OtpAuthController::class, 'revokeAllTokens']);
    });
});
