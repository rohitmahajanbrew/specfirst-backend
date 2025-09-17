<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\OtpAuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API routes (no authentication required)
Route::prefix('auth')->group(function () {
    // Unified OTP Authentication (handles both login and signup)
    Route::post('send-otp', [OtpAuthController::class, 'sendOtp']);
    Route::post('verify-otp', [OtpAuthController::class, 'verifyOtp']);
    Route::post('resend-otp', [OtpAuthController::class, 'resendOtp']);
});

// Public project types (no authentication required)
Route::get('project-types', [App\Http\Controllers\ProjectTypeController::class, 'index']);
Route::get('project-types/{projectType}', [App\Http\Controllers\ProjectTypeController::class, 'show']);

// Protected API routes (require authentication)
Route::middleware('auth:api')->group(function () {
    
    // User management
    Route::prefix('auth')->group(function () {
        Route::get('me', [OtpAuthController::class, 'me']);
        Route::post('logout', [OtpAuthController::class, 'logout']);
        Route::post('refresh-token', [OtpAuthController::class, 'refreshToken']);
        Route::get('tokens', [OtpAuthController::class, 'getTokens']);
        Route::delete('tokens', [OtpAuthController::class, 'revokeAllTokens']);
        Route::put('preferred-project-type', [OtpAuthController::class, 'updatePreferredProjectType']);
    });

    /**
     * @OA\Get(
     *     path="/api/test",
     *     tags={"Testing"},
     *     summary="Test protected route",
     *     description="Tests API authentication with JWT token",
     *     security={{"passport": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="This is a protected API route!"),
     *             @OA\Property(property="user", type="object"),
     *             @OA\Property(property="scopes", type="array", @OA\Items(type="string"))
     *         )
     *     )
     * )
     */
    Route::get('test', function (Request $request) {
        return response()->json([
            'message' => 'This is a protected API route!',
            'user' => $request->user(),
            'scopes' => $request->user()->token()->scopes ?? [],
        ]);
    });

    // TODO: Add other API routes when controllers are created
    // - Projects API
    // - Analytics API  
    // - Vendor API
    // - Admin API
});

/**
 * @OA\Get(
 *     path="/api/public-test",
 *     tags={"Testing"},
 *     summary="Test public route",
 *     description="Tests public API access without authentication",
 *     @OA\Response(
 *         response=200,
 *         description="Success",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="This is a public API route!"),
 *             @OA\Property(property="timestamp", type="string", format="datetime")
 *         )
 *     )
 * )
 */
Route::get('public-test', function () {
    return response()->json([
        'message' => 'This is a public API route!',
        'timestamp' => now(),
    ]);
});
