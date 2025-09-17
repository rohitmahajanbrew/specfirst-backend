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
        Schema::create('interview_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('interview_sessions')->onDelete('cascade');
            
            // Message Content
            $table->enum('role', ['ai', 'user', 'system']);
            $table->text('content');
            $table->enum('message_type', ['question', 'answer', 'clarification', 'suggestion', 'warning'])->default('question');
            
            // Metadata
            $table->json('metadata')->nullable();
            $table->decimal('confidence_score', 3, 2)->nullable();
            $table->boolean('requires_follow_up')->default(false);
            
            $table->timestamps();
            
            // Indexes
            $table->index('session_id');
            $table->index('role');
            $table->index('message_type');
            $table->index('created_at');
            $table->index(['session_id', 'created_at']);
            $table->index(['session_id', 'role']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_messages');
    }
};
