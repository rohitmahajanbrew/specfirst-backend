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
        Schema::create('project_exports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->enum('export_format', ['pdf', 'word', 'markdown', 'json']);
            $table->text('file_url')->nullable();
            $table->integer('file_size_bytes')->nullable();
            
            // Email Delivery
            $table->string('sent_to_email')->nullable();
            $table->timestamp('email_sent_at')->nullable();
            
            $table->timestamps();
            $table->timestamp('expires_at')->nullable();
            
            // Indexes
            $table->index('project_id');
            $table->index('user_id');
            $table->index('export_format');
            $table->index('created_at');
            $table->index('expires_at');
            $table->index(['project_id', 'export_format']);
            $table->index(['user_id', 'export_format']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_exports');
    }
};
