<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_otps', function (Blueprint $table) {
            $table->id();
            $table->string('identifier'); // email or phone number
            $table->string('otp_code', 6);
            $table->enum('type', ['email', 'sms'])->default('email');
            $table->enum('purpose', ['login', 'registration', 'password_reset', 'verification'])->default('login');
            $table->timestamp('expires_at');
            $table->boolean('is_used')->default(false);
            $table->timestamp('used_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->integer('attempts')->default(0);
            $table->timestamp('last_attempt_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('identifier');
            $table->index('otp_code');
            $table->index('type');
            $table->index('purpose');
            $table->index('expires_at');
            $table->index('is_used');
            $table->index(['identifier', 'purpose']);
            $table->index(['identifier', 'is_used', 'expires_at']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_otps');
    }
};
