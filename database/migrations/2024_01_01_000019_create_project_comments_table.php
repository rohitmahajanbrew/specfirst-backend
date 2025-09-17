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
        Schema::create('project_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_comment_id')->nullable()->constrained('project_comments')->onDelete('cascade');
            
            // Location in document
            $table->string('section_id')->nullable();
            $table->text('highlighted_text')->nullable();
            
            $table->text('comment_text');
            $table->boolean('is_resolved')->default(false);
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('resolved_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('project_id');
            $table->index('user_id');
            $table->index('parent_comment_id');
            $table->index('resolved_by');
            $table->index('is_resolved');
            $table->index('section_id');
            $table->index(['project_id', 'is_resolved']);
            $table->index(['project_id', 'section_id']);
            $table->index(['user_id', 'is_resolved']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_comments');
    }
};
