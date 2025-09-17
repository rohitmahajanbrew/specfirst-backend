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
            // Change existing fields
            $table->string('name')->nullable()->change();
            $table->string('password')->nullable()->change();
            
            // Add new fields
            $table->string('full_name')->nullable()->after('name');
            $table->string('company_name')->nullable()->after('full_name');
            $table->string('phone_number', 50)->nullable()->after('company_name');
            $table->text('avatar_url')->nullable()->after('phone_number');
            $table->enum('role', ['user', 'admin', 'vendor'])->default('user')->after('avatar_url');
            $table->boolean('onboarding_completed')->default(false)->after('role');
            $table->string('preferred_project_type', 50)->nullable()->after('onboarding_completed');
            
            // Device fields
            $table->string('device_type', 50)->nullable()->after('preferred_project_type');
            $table->text('device_token')->nullable()->after('device_type');
            
            // Timestamps
            $table->timestamp('last_login_at')->nullable()->after('device_token');
            $table->softDeletes();
            
            // Metadata
            $table->string('timezone', 50)->default('UTC')->after('deleted_at');
            $table->string('locale', 10)->default('en')->after('timezone');
            $table->json('notification_preferences')->nullable()->after('locale');
            $table->json('metadata')->nullable()->after('notification_preferences');
            
            // Add indexes
            $table->index('role');
            $table->index('created_at');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'full_name',
                'company_name', 
                'phone_number',
                'avatar_url',
                'role',
                'onboarding_completed',
                'preferred_project_type',
                'device_type',
                'device_token',
                'last_login_at',
                'deleted_at',
                'timezone',
                'locale',
                'notification_preferences',
                'metadata'
            ]);
            
            $table->dropIndex(['role']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['deleted_at']);
        });
    }
};
