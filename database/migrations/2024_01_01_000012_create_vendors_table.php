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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // Company Info
            $table->string('company_name');
            $table->text('website_url')->nullable();
            $table->text('logo_url')->nullable();
            $table->text('description')->nullable();
            
            // Capabilities
            $table->json('project_types')->nullable();
            $table->json('technologies')->nullable();
            $table->string('team_size', 50)->nullable();
            $table->integer('hourly_rate_min')->nullable();
            $table->integer('hourly_rate_max')->nullable();
            $table->integer('minimum_project_budget')->nullable();
            
            // Location
            $table->string('headquarters_country', 100)->nullable();
            $table->string('headquarters_city', 100)->nullable();
            $table->json('timezones_covered')->nullable();
            
            // Verification & Quality
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamp('verification_date')->nullable();
            $table->integer('quality_score')->default(50); // Range 0-100, validated at application level
            $table->integer('response_time_hours')->nullable();
            
            // Portfolio
            $table->json('portfolio_urls')->nullable();
            $table->json('case_studies')->nullable();
            $table->json('client_logos')->nullable();
            
            // Lead Preferences
            $table->json('lead_preferences')->nullable();
            $table->integer('max_leads_per_month')->default(10);
            $table->integer('current_month_leads')->default(0);
            
            // Billing
            $table->enum('subscription_status', ['trial', 'active', 'paused', 'cancelled'])->default('trial');
            $table->string('subscription_plan', 50)->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('verification_status');
            $table->index('subscription_status');
            $table->index('quality_score');
            $table->index('headquarters_country');
            $table->index(['verification_status', 'subscription_status']);
            $table->index(['verification_status', 'quality_score']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
