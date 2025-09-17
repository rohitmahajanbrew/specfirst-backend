<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\OtpService;

class TestPassportCommand extends Command
{
    protected $signature = 'test:passport {email}';
    protected $description = 'Test Passport integration with OTP authentication';

    public function handle()
    {
        $email = $this->argument('email');
        $otpService = new OtpService();

        $this->info("Testing Passport + OTP integration with email: {$email}");

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

        // Test token creation
        $this->info("Creating Passport token...");
        $tokenResult = $user->createToken('Test API Token');
        $token = $tokenResult->token;
        
        // Set scopes
        $scopes = ['read-projects', 'write-projects'];
        $token->scopes = $scopes;
        $token->save();

        $this->info("✅ Token created successfully!");
        $this->info("Access Token: " . substr($tokenResult->accessToken, 0, 50) . "...");
        $this->info("Token Type: Bearer");
        $this->info("Expires At: " . $token->expires_at);
        $this->info("Scopes: " . implode(', ', $scopes));

        // Test token verification
        $this->info("\nTesting token verification...");
        
        // You can test this token by making API requests with:
        $this->warn("Test this token with curl:");
        $this->line("curl -H 'Authorization: Bearer {$tokenResult->accessToken}' \\");
        $this->line("     -H 'Accept: application/json' \\");
        $this->line("     http://localhost:8000/api/auth/me");

        // Show user's active tokens
        $activeTokens = $user->tokens()->where('revoked', false)->count();
        $this->info("\nUser has {$activeTokens} active token(s)");

        return 0;
    }
}
