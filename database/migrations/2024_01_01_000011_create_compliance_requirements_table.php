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
        Schema::create('compliance_requirements', function (Blueprint $table) {
            $table->id();
            
            $table->string('name');
            $table->string('code', 50)->unique();
            $table->text('description')->nullable();
            
            // Requirements to add
            $table->json('requirements_checklist');
            $table->json('auto_detect_keywords')->nullable();
            
            // Categorization
            $table->string('industry', 100)->nullable();
            $table->string('region', 100)->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes
            $table->index('code');
            $table->index('industry');
            $table->index('region');
            $table->index('is_active');
            $table->index(['industry', 'region']);
            $table->index(['industry', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compliance_requirements');
    }
};
