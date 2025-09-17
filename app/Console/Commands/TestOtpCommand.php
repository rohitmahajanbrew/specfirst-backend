<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\OtpService;
use App\Models\User;

class TestOtpCommand extends Command
{
    protected $signature = 'test:otp {email}';
    protected $description = 'Test OTP functionality with an email';

    public function handle()
    {
        $email = $this->argument('email');
        $otpService = new OtpService();

        $this->info("Testing OTP system with email: {$email}");

        // Check if user exists, if not create one
        $user = User::where('email', $email)->first();
        if (!$user) {
            $user = User::create([
                'name' => 'Test User',
                'email' => $email,
                'role' => 'user',
                'timezone' => 'UTC',
                'locale' => 'en',
            ]);
            $this->info("Created test user with email: {$email}");
        }

        // Send OTP
        $this->info("Sending OTP...");
        $result = $otpService->sendOtp($email, 'login');
        
        if ($result['success']) {
            $this->info("✅ OTP sent successfully!");
            $this->info("Expires at: " . $result['expires_at']);
            
            // Get the latest OTP from database to show it (for testing only)
            $latestOtp = \App\Models\UserOtp::where('identifier', $email)
                ->where('purpose', 'login')
                ->orderBy('created_at', 'desc')
                ->first();
                
            if ($latestOtp) {
                $this->warn("🔐 OTP Code (for testing): " . $latestOtp->otp_code);
                $this->info("Now you can test verification with: php artisan test:verify-otp {$email} {$latestOtp->otp_code}");
            }
        } else {
            $this->error("❌ Failed to send OTP: " . $result['message']);
        }

        return 0;
    }
}
