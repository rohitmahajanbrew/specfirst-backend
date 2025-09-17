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
        Schema::create('feature_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
            
            $table->string('feature_name', 100);
            $table->integer('usage_count')->default(1);
            $table->timestamp('last_used_at')->useCurrent();
            
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('project_id');
            $table->index('feature_name');
            $table->index('last_used_at');
            $table->index(['user_id', 'feature_name']);
            $table->index(['project_id', 'feature_name']);
            $table->index(['feature_name', 'last_used_at']);
            
            // Unique constraint to prevent duplicate entries
            $table->unique(['user_id', 'project_id', 'feature_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feature_usage');
    }
};
