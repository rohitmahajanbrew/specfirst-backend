<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserOtp;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\OtpMail;
use Carbon\Carbon;

class OtpService
{
    /**
     * Send OTP to user's email (unified login/signup flow).
     */
    public function sendOtp(string $identifier, string $purpose = 'auth'): array
    {
        try {
            // For unified auth flow, we always send OTP regardless of user existence
            // The user will be created during verification if they don't exist

            // Rate limiting - check if OTP was sent recently
            $recentOtp = UserOtp::where('identifier', $identifier)
                ->where('purpose', $purpose)
                ->where('created_at', '>', Carbon::now()->subMinute())
                ->first();

            if ($recentOtp) {
                return [
                    'success' => false,
                    'message' => 'Please wait before requesting another OTP.',
                ];
            }

            // Generate new OTP
            $otp = UserOtp::createOtp($identifier, 'email', $purpose);

            // Skip email sending for testing - log the OTP instead
            Log::info("Static OTP generated for testing", [
                'identifier' => $identifier,
                'otp_code' => $otp->otp_code,
                'purpose' => $purpose,
            ]);

            // Log OTP generation (without the actual code for security)
            Log::info('OTP generated', [
                'identifier' => $identifier,
                'purpose' => $purpose,
                'expires_at' => $otp->expires_at,
            ]);

            return [
                'success' => true,
                'message' => 'OTP sent successfully to your email.',
                'expires_at' => $otp->expires_at,
            ];

        } catch (\Exception $e) {
            Log::error('Failed to send OTP', [
                'identifier' => $identifier,
                'purpose' => $purpose,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send OTP. Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Verify OTP and return result with user information.
     */
    public function verifyOtp(string $identifier, string $code, string $purpose = 'auth'): array
    {
        try {
            // Find the most recent valid OTP
            $otp = UserOtp::where('identifier', $identifier)
                ->where('purpose', $purpose)
                ->valid()
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$otp) {
                return [
                    'success' => false,
                    'message' => 'No valid OTP found. Please request a new one.',
                ];
            }

            // Verify the code
            $isValid = $otp->verify($code);

            if (!$isValid) {
                $remainingAttempts = 5 - $otp->attempts;
                
                if ($remainingAttempts <= 0) {
                    return [
                        'success' => false,
                        'message' => 'Too many invalid attempts. Please request a new OTP.',
                    ];
                }

                return [
                    'success' => false,
                    'message' => "Invalid OTP code. {$remainingAttempts} attempts remaining.",
                ];
            }

            // Log successful verification
            Log::info('OTP verified successfully', [
                'identifier' => $identifier,
                'purpose' => $purpose,
            ]);

            return [
                'success' => true,
                'message' => 'OTP verified successfully.',
                'otp' => $otp,
            ];

        } catch (\Exception $e) {
            Log::error('Failed to verify OTP', [
                'identifier' => $identifier,
                'purpose' => $purpose,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to verify OTP. Please try again.',
            ];
        }
    }

    /**
     * Verify OTP and handle unified auth (login/signup).
     */
    public function verifyUnifiedAuth(string $email, string $code, array $userData = []): array
    {
        try {
            // Verify the OTP first
            $otpResult = $this->verifyOtp($email, $code, 'auth');
            
            if (!$otpResult['success']) {
                return $otpResult;
            }

            // Check if user exists
            $user = User::where('email', $email)->first();
            $isNewUser = false;

            if (!$user) {
                // Create new user
                $user = User::create([
                    'name' => $userData['name'] ?? explode('@', $email)[0], // Use email prefix as default name
                    'email' => $email,
                    'full_name' => $userData['full_name'] ?? null,
                    'company_name' => $userData['company_name'] ?? null,
                    'phone_number' => $userData['phone_number'] ?? null,
                    'email_verified_at' => now(), // Auto-verify since they used OTP
                    'role' => 'user',
                    'timezone' => $userData['timezone'] ?? 'UTC',
                    'locale' => $userData['locale'] ?? 'en',
                    'device_type' => $userData['device_type'] ?? null,
                    'device_token' => $userData['device_token'] ?? null,
                ]);
                $isNewUser = true;

                Log::info('New user created via OTP auth', [
                    'user_id' => $user->id,
                    'email' => $email,
                ]);
            } else {
                // Update existing user's device info and last login
                $user->update([
                    'last_login_at' => now(),
                    'device_type' => $userData['device_type'] ?? $user->device_type,
                    'device_token' => $userData['device_token'] ?? $user->device_token,
                ]);

                Log::info('Existing user logged in via OTP', [
                    'user_id' => $user->id,
                    'email' => $email,
                ]);
            }

            return [
                'success' => true,
                'message' => $isNewUser ? 'Account created and logged in successfully.' : 'Logged in successfully.',
                'user' => $user,
                'is_new_user' => $isNewUser,
                'otp' => $otpResult['otp'],
            ];

        } catch (\Exception $e) {
            Log::error('Failed to verify unified auth', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Authentication failed. Please try again.',
            ];
        }
    }

    /**
     * Send OTP via email.
     */
    private function sendOtpEmail(string $email, string $code, string $purpose): void
    {
        try {
            $data = [
                'otp_code' => $code,
                'purpose' => $purpose,
                'expires_in' => '10 minutes',
            ];

            // For now, we'll log the OTP instead of actually sending email
            // In production, you'd use Mail::to($email)->send(new OtpMail($data));
            Log::info("OTP Email sent to {$email}", [
                'code' => $code, // Remove this in production
                'purpose' => $purpose,
            ]);

            // Uncomment this when you have email configured:
            // Mail::to($email)->send(new OtpMail($data));
        } catch (\Exception $e) {
            Log::error("Failed to send OTP email", [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
            throw $e; // Re-throw to be caught by the calling method
        }
    }

    /**
     * Clean up expired OTPs.
     */
    public function cleanupExpiredOtps(): int
    {
        $deleted = UserOtp::where('expires_at', '<', Carbon::now())
            ->orWhere('is_used', true)
            ->where('created_at', '<', Carbon::now()->subDay())
            ->delete();

        Log::info("Cleaned up {$deleted} expired OTP records");

        return $deleted;
    }

    /**
     * Get OTP statistics for admin.
     */
    public function getOtpStats(): array
    {
        return [
            'total_generated' => UserOtp::count(),
            'used' => UserOtp::where('is_used', true)->count(),
            'expired' => UserOtp::where('expires_at', '<', Carbon::now())->count(),
            'pending' => UserOtp::valid()->count(),
            'recent_24h' => UserOtp::where('created_at', '>', Carbon::now()->subDay())->count(),
        ];
    }
}
