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
        Schema::create('project_collaborators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->enum('role', ['viewer', 'editor', 'owner'])->default('viewer');
            $table->foreignId('invited_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('invitation_email')->nullable();
            $table->string('invitation_token')->unique()->nullable();
            $table->boolean('invitation_accepted')->default(false);
            
            $table->json('permissions')->nullable();
            
            $table->timestamps();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('last_accessed_at')->nullable();
            
            // Indexes
            $table->index('project_id');
            $table->index('user_id');
            $table->index('invited_by');
            $table->index('invitation_token');
            $table->index('role');
            $table->index('invitation_accepted');
            $table->index(['project_id', 'user_id']);
            $table->index(['project_id', 'role']);
            $table->index(['user_id', 'role']);
            
            // Unique constraint to prevent duplicate collaborators
            $table->unique(['project_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_collaborators');
    }
};
