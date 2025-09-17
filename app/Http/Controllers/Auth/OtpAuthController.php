<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class OtpAuthController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * @OA\Post(
     *     path="/api/auth/send-otp",
     *     tags={"Authentication"},
     *     summary="Send OTP for authentication",
     *     description="Sends a One-Time Password to the user's email for unified login/signup authentication",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="OTP sent successfully to your email."),
     *             @OA\Property(property="expires_at", type="string", format="datetime")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="User not found with this email address.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid email address or user not found."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email address.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $result = $this->otpService->sendOtp($request->email, 'auth');

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/verify-otp",
     *     tags={"Authentication"},
     *     summary="Verify OTP and authenticate user",
     *     description="Verifies the OTP code, creates user if new, and returns a JWT access token for API authentication",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "otp_code"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="otp_code", type="string", minLength=6, maxLength=6, example="123456"),
     *             @OA\Property(property="name", type="string", example="John Doe", description="Name for new users (optional)"),
     *             @OA\Property(property="full_name", type="string", example="John Doe", description="Full name (optional)"),
     *             @OA\Property(property="company_name", type="string", example="Acme Corp", description="Company name (optional)"),
     *             @OA\Property(property="phone_number", type="string", example="+1234567890", description="Phone number (optional)"),
     *             @OA\Property(property="device_type", type="string", enum={"web", "mobile", "tablet"}, example="mobile"),
     *             @OA\Property(property="device_token", type="string", example="fcm_token_for_notifications")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Authentication successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Logged in successfully."),
     *             @OA\Property(property="is_new_user", type="boolean", example=false, description="True if user was just created"),
     *             @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."),
     *             @OA\Property(property="token_type", type="string", example="Bearer"),
     *             @OA\Property(property="expires_at", type="string", format="datetime"),
     *             @OA\Property(property="scopes", type="array", @OA\Items(type="string"), example={"read-projects", "write-projects"}),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="user@example.com"),
     *                 @OA\Property(property="role", type="string", example="user"),
     *                 @OA\Property(property="onboarding_completed", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid OTP",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid OTP code. 3 attempts remaining.")
     *         )
     *     )
     * )
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp_code' => 'required|string|size:6',
            'name' => 'nullable|string|max:255',
            'full_name' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:50',
            'device_type' => 'nullable|string|in:web,mobile,tablet',
            'device_token' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input data.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Prepare user data for potential user creation
        $userData = [
            'name' => $request->name,
            'full_name' => $request->full_name,
            'company_name' => $request->company_name,
            'phone_number' => $request->phone_number,
            'device_type' => $request->device_type,
            'device_token' => $request->device_token,
        ];

        // Verify OTP and handle user creation/login
        $result = $this->otpService->verifyUnifiedAuth($request->email, $request->otp_code, $userData);

        if (!$result['success']) {
            return response()->json($result, 400);
        }

        $user = $result['user'];
        $isNewUser = $result['is_new_user'];

        // Login user
        Auth::login($user);

        // Create API token for mobile/API access
        $tokenResult = $user->createToken('API Token');
        $token = $tokenResult->token;
        
        // Set token scopes based on user role
        $scopes = $this->getUserScopes($user);
        $token->scopes = $scopes;
        $token->save();

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'is_new_user' => $isNewUser,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'full_name' => $user->full_name,
                'company_name' => $user->company_name,
                'phone_number' => $user->phone_number,
                'role' => $user->role,
                'onboarding_completed' => $user->onboarding_completed,
                'avatar_url' => $user->avatar_url,
            ],
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => $token->expires_at,
            'scopes' => $scopes,
        ]);
    }

    // Note: Registration and login are now unified in sendOtp() and verifyOtp() methods

    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     tags={"Authentication"},
     *     summary="Logout user",
     *     description="Revokes the current user's token and logs them out",
     *     security={{"passport": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logged out successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Logged out successfully.")
     *         )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        try {
            // For API authentication, we only need to revoke the current token
            if ($request->user() && $request->user()->token()) {
                $request->user()->token()->revoke();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Logged out successfully.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No active session found.',
            ], 401);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout failed. Please try again.',
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/auth/me",
     *     tags={"Authentication"},
     *     summary="Get current authenticated user",
     *     description="Returns the current authenticated user's profile information",
     *     security={{"passport": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="User profile retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="user@example.com"),
     *                 @OA\Property(property="full_name", type="string", example="John Doe"),
     *                 @OA\Property(property="company_name", type="string", example="Acme Corp"),
     *                 @OA\Property(property="role", type="string", example="user"),
     *                 @OA\Property(property="onboarding_completed", type="boolean", example=true),
     *                 @OA\Property(property="device_type", type="string", example="mobile"),
     *                 @OA\Property(property="created_at", type="string", format="datetime"),
     *                 @OA\Property(property="last_login_at", type="string", format="datetime")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Not authenticated.")
     *         )
     *     )
     * )
     */
    public function me(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated.',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'full_name' => $user->full_name,
                'company_name' => $user->company_name,
                'phone_number' => $user->phone_number,
                'role' => $user->role,
                'onboarding_completed' => $user->onboarding_completed,
                'avatar_url' => $user->avatar_url,
                'timezone' => $user->timezone,
                'locale' => $user->locale,
                'last_login_at' => $user->last_login_at,
                'device_type' => $user->device_type,
                'created_at' => $user->created_at,
            ],
        ]);
    }

    /**
     * Resend OTP (with rate limiting).
     */
    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input data.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $result = $this->otpService->sendOtp($request->email, 'auth');

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Revoke all tokens for user.
     */
    public function revokeAllTokens(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated.',
            ], 401);
        }

        // Revoke all tokens
        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'All tokens revoked successfully.',
        ]);
    }

    /**
     * Get user's active tokens.
     */
    public function getTokens(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated.',
            ], 401);
        }

        $tokens = $user->tokens()->where('revoked', false)->get();

        return response()->json([
            'success' => true,
            'tokens' => $tokens->map(function ($token) {
                return [
                    'id' => $token->id,
                    'name' => $token->name,
                    'scopes' => $token->scopes,
                    'created_at' => $token->created_at,
                    'expires_at' => $token->expires_at,
                    'last_used_at' => $token->last_used_at,
                ];
            }),
        ]);
    }

    /**
     * Refresh access token.
     */
    public function refreshToken(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated.',
            ], 401);
        }

        // Revoke current token
        $request->user()->token()->revoke();

        // Create new token
        $tokenResult = $user->createToken('API Token');
        $token = $tokenResult->token;
        
        // Set token scopes based on user role
        $scopes = $this->getUserScopes($user);
        $token->scopes = $scopes;
        $token->save();

        return response()->json([
            'success' => true,
            'message' => 'Token refreshed successfully.',
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => $token->expires_at,
            'scopes' => $scopes,
        ]);
    }

    /**
     * Get user scopes based on role.
     */
    private function getUserScopes(User $user): array
    {
        $baseScopes = ['read-projects'];

        switch ($user->role) {
            case 'admin':
                return ['admin', 'read-projects', 'write-projects', 'delete-projects', 'manage-collaborators', 'read-analytics', 'manage-vendors'];
            
            case 'vendor':
                return ['read-projects', 'manage-vendors', 'read-analytics'];
            
            case 'user':
            default:
                return ['read-projects', 'write-projects', 'manage-collaborators'];
        }
    }
}
