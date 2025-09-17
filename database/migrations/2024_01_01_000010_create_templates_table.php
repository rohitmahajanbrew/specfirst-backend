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
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            
            // Basic Info
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('project_type', 50);
            $table->string('category', 100)->nullable();
            
            // Template Content
            $table->json('template_structure');
            $table->json('sample_questions')->nullable();
            $table->json('default_features')->nullable();
            
            // Metadata
            $table->boolean('is_premium')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->integer('usage_count')->default(0);
            $table->decimal('avg_rating', 2, 1)->nullable();
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('project_type');
            $table->index('category');
            $table->index('slug');
            $table->index('is_featured');
            $table->index('is_premium');
            $table->index(['project_type', 'category']);
            $table->index(['is_featured', 'is_premium']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
