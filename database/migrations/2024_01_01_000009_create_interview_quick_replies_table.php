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
        Schema::create('interview_quick_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('interview_sessions')->onDelete('cascade');
            $table->foreignId('message_id')->constrained('interview_messages')->onDelete('cascade');
            
            $table->string('reply_text');
            $table->text('reply_value')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('was_selected')->default(false);
            
            $table->timestamps();
            
            // Indexes
            $table->index('session_id');
            $table->index('message_id');
            $table->index(['session_id', 'display_order']);
            $table->index('was_selected');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_quick_replies');
    }
};
