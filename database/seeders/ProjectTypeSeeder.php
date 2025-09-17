<?php

namespace Database\Seeders;

use App\Models\ProjectType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projectTypes = [
            [
                'name' => 'Web App',
                'slug' => 'web_app',
                'description' => 'Dashboards, SaaS, Portals',
                'icon' => '🌐', // Globe icon
                'color' => '#3b82f6', // Blue
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Mobile App',
                'slug' => 'mobile_app',
                'description' => 'iOS, Android, Native',
                'icon' => '📱', // Mobile phone icon
                'color' => '#10b981', // Green
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'E-commerce',
                'slug' => 'ecommerce',
                'description' => 'Online stores, Marketplaces',
                'icon' => '🛍️', // Shopping bags icon
                'color' => '#f59e0b', // Amber
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'ERP, CRM, HRMS',
                'icon' => '🏢', // Building icon
                'color' => '#8b5cf6', // Purple
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Custom',
                'slug' => 'custom',
                'description' => 'APIs, Special needs',
                'icon' => '⚙️', // Gear icon
                'color' => '#6b7280', // Gray
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Not Sure',
                'slug' => 'not_sure',
                'description' => 'Help me decide',
                'icon' => '🤔', // Thinking emoji
                'color' => '#ef4444', // Red
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($projectTypes as $projectType) {
            ProjectType::updateOrCreate(
                ['slug' => $projectType['slug']],
                $projectType
            );
        }
    }
}