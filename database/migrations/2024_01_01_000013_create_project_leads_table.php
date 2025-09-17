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
        Schema::create('project_leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            
            // Lead Status
            $table->enum('status', ['new', 'viewed', 'contacted', 'quoted', 'won', 'lost'])->default('new');
            
            // Matching
            $table->integer('match_score')->nullable(); // Range 0-100, validated at application level
            $table->json('match_reasons')->nullable();
            
            // Vendor Actions
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('contacted_at')->nullable();
            $table->timestamp('quoted_at')->nullable();
            $table->integer('quote_amount')->nullable();
            $table->integer('quote_timeline_weeks')->nullable();
            
            // Outcome
            $table->enum('outcome', ['pending', 'hired', 'rejected', 'expired'])->default('pending');
            $table->text('outcome_reason')->nullable();
            
            // Billing
            $table->decimal('lead_price', 10, 2)->nullable();
            $table->timestamp('paid_at')->nullable();
            
            $table->timestamps();
            $table->timestamp('expires_at')->nullable();
            
            // Indexes
            $table->index('project_id');
            $table->index('vendor_id');
            $table->index('status');
            $table->index('outcome');
            $table->index('match_score');
            $table->index(['project_id', 'status']);
            $table->index(['vendor_id', 'status']);
            $table->index(['vendor_id', 'outcome']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_leads');
    }
};
