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
        Schema::create('waitlist', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            
            // Interest
            $table->string('project_type', 50)->nullable();
            $table->string('company_size', 50)->nullable();
            $table->text('use_case')->nullable();
            
            // Status
            $table->enum('status', ['waiting', 'invited', 'activated'])->default('waiting');
            $table->timestamp('invited_at')->nullable();
            $table->timestamp('activated_at')->nullable();
            
            // Source
            $table->string('referral_source', 100)->nullable();
            $table->string('utm_source', 100)->nullable();
            $table->string('utm_campaign', 100)->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('email');
            $table->index('status');
            $table->index('created_at');
            $table->index('project_type');
            $table->index('company_size');
            $table->index('utm_source');
            $table->index('utm_campaign');
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waitlist');
    }
};
