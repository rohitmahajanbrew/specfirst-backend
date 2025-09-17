<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\OtpService;

class TestVerifyOtpCommand extends Command
{
    protected $signature = 'test:verify-otp {email} {code}';
    protected $description = 'Test OTP verification';

    public function handle()
    {
        $email = $this->argument('email');
        $code = $this->argument('code');
        $otpService = new OtpService();

        $this->info("Testing OTP verification for: {$email} with code: {$code}");

        $result = $otpService->verifyOtp($email, $code, 'login');

        if ($result['success']) {
            $this->info("✅ OTP verified successfully!");
            $this->info("Message: " . $result['message']);
        } else {
            $this->error("❌ OTP verification failed: " . $result['message']);
        }

        return 0;
    }
}
