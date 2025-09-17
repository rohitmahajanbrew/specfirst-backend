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
        Schema::create('analytics_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable();
            
            // Event Info
            $table->string('event_name', 100);
            $table->string('event_category', 50)->nullable();
            $table->json('event_data')->nullable();
            
            // Context
            $table->text('page_url')->nullable();
            $table->text('referrer_url')->nullable();
            $table->string('utm_source', 100)->nullable();
            $table->string('utm_medium', 100)->nullable();
            $table->string('utm_campaign', 100)->nullable();
            
            // Device Info
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device_type', 50)->nullable();
            $table->string('browser', 50)->nullable();
            $table->string('os', 50)->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('session_id');
            $table->index('event_name');
            $table->index('event_category');
            $table->index('created_at');
            $table->index(['user_id', 'event_name']);
            $table->index(['event_name', 'created_at']);
            $table->index(['session_id', 'created_at']);
            $table->index('utm_source');
            $table->index('utm_campaign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics_events');
    }
};
