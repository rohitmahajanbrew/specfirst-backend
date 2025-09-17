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
            // Change preferred_project_type from string to JSON array
            $table->json('preferred_project_types')->nullable()->after('onboarding_completed');
        });

        // Migrate existing data from string to JSON array
        $users = \DB::table('users')->whereNotNull('preferred_project_type')->get();
        
        foreach ($users as $user) {
            if ($user->preferred_project_type) {
                \DB::table('users')
                    ->where('id', $user->id)
                    ->update([
                        'preferred_project_types' => json_encode([$user->preferred_project_type])
                    ]);
            }
        }

        // Drop the old column after data migration
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('preferred_project_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add back the old column
            $table->string('preferred_project_type', 50)->nullable()->after('onboarding_completed');
        });

        // Migrate data back from JSON array to string (take first item)
        $users = \DB::table('users')->whereNotNull('preferred_project_types')->get();
        
        foreach ($users as $user) {
            if ($user->preferred_project_types) {
                $types = json_decode($user->preferred_project_types, true);
                if (is_array($types) && !empty($types)) {
                    \DB::table('users')
                        ->where('id', $user->id)
                        ->update([
                            'preferred_project_type' => $types[0] // Take first item
                        ]);
                }
            }
        }

        // Drop the JSON column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('preferred_project_types');
        });
    }
};