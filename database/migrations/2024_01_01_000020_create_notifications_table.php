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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('type', 50);
            $table->string('title');
            $table->text('message')->nullable();
            
            // Related entities
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('vendor_id')->nullable()->constrained()->onDelete('cascade');
            
            // Status
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            
            // Delivery
            $table->boolean('email_sent')->default(false);
            $table->boolean('sms_sent')->default(false);
            
            $table->text('action_url')->nullable();
            
            $table->timestamps();
            $table->timestamp('expires_at')->nullable();
            
            // Indexes
            $table->index('user_id');
            $table->index('project_id');
            $table->index('vendor_id');
            $table->index('type');
            $table->index('is_read');
            $table->index('created_at');
            $table->index('expires_at');
            $table->index(['user_id', 'is_read']);
            $table->index(['user_id', 'type']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
