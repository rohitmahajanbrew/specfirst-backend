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
        Schema::create('project_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Web App, Mobile App, etc.
            $table->string('slug')->unique(); // web_app, mobile_app, etc.
            $table->text('description'); // Dashboards, SaaS, Portals
            $table->string('icon')->nullable(); // Icon class or URL
            $table->string('color')->default('#6366f1'); // Hex color for UI
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            // Indexes
            $table->index('slug');
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_types');
    }
};