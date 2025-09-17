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
        Schema::create('interview_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Session State
            $table->enum('status', ['active', 'paused', 'completed', 'abandoned'])->default('active');
            $table->integer('current_question_index')->default(0);
            $table->string('interview_type', 50)->nullable();
            
            // Conversation Context
            $table->json('conversation_context')->nullable();
            $table->json('extracted_requirements')->nullable();
            
            // Timing
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->integer('duration_seconds')->nullable();
            $table->timestamp('last_activity_at')->useCurrent();
            $table->timestamps();
            
            // Indexes
            $table->index('project_id');
            $table->index('user_id');
            $table->index('status');
            $table->index(['project_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index('started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_sessions');
    }
};
