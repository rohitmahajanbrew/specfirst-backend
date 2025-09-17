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
        Schema::create('vendor_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->integer('rating');
            $table->text('review_text')->nullable();
            
            // Specific Ratings
            $table->integer('communication_rating')->nullable();
            $table->integer('quality_rating')->nullable();
            $table->integer('timeline_rating')->nullable();
            $table->integer('value_rating')->nullable();
            
            $table->boolean('is_verified')->default(false);
            
            $table->timestamps();
            
            // Indexes
            $table->index('vendor_id');
            $table->index('user_id');
            $table->index('project_id');
            $table->index('rating');
            $table->index('is_verified');
            $table->index(['vendor_id', 'rating']);
            $table->index(['vendor_id', 'is_verified']);
            
            // Note: Check constraints will be handled at application level for MySQL compatibility
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_reviews');
    }
};
