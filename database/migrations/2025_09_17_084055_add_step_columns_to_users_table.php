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
        Schema::table('users', function (Blueprint $table) {
            $table->string('completed_step', 50)->nullable()->after('onboarding_completed');
            $table->string('next_step', 50)->nullable()->after('completed_step');
            
            // Add indexes for better query performance
            $table->index('completed_step');
            $table->index('next_step');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['completed_step']);
            $table->dropIndex(['next_step']);
            $table->dropColumn(['completed_step', 'next_step']);
        });
    }
};