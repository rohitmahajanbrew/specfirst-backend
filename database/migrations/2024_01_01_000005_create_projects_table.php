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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Basic Info
            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->text('description')->nullable();
            $table->enum('project_type', ['web_app', 'mobile_app', 'ecommerce', 'enterprise', 'custom']);
            $table->enum('status', ['draft', 'interview_active', 'review', 'completed', 'archived'])->default('draft');
            
            // Requirements Content
            $table->json('requirements_doc')->nullable();
            $table->longText('requirements_html')->nullable();
            $table->longText('requirements_markdown')->nullable();
            
            // Metadata
            $table->integer('completeness_score')->default(0); // Range 0-100, validated at application level
            $table->enum('complexity_score', ['simple', 'medium', 'complex', 'enterprise'])->default('medium');
            $table->integer('estimated_budget_min')->nullable();
            $table->integer('estimated_budget_max')->nullable();
            $table->integer('estimated_timeline_weeks')->nullable();
            $table->integer('word_count')->default(0);
            
            // Sharing & Collaboration
            $table->boolean('is_public')->default(false);
            $table->string('share_token')->unique()->nullable();
            $table->boolean('password_protected')->default(false);
            $table->string('password_hash')->nullable();
            
            // Analytics
            $table->integer('view_count')->default(0);
            $table->integer('download_count')->default(0);
            $table->integer('share_count')->default(0);
            
            $table->timestamps();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('last_accessed_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            
            // Indexes
            $table->index('user_id');
            $table->index('status');
            $table->index('project_type');
            $table->index('share_token');
            $table->index('created_at');
            $table->index('slug');
            $table->index(['user_id', 'status']);
            $table->index(['project_type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
