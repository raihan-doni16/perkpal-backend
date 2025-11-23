<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Lifestyle',
                'description' => 'Live better, work smarter, and thrive with exclusive lifestyle perks',
                'icon' => '🌟',
                'meta_title' => 'Lifestyle Perks',
                'meta_description' => 'Access exclusive lifestyle deals for remote work, health, money management, team building, and founder resources',
                'is_active' => true,
                'display_order' => 1,
            ],
            [
                'name' => 'SaaS & AI Tools',
                'description' => 'Supercharge your workflow with cutting-edge SaaS and AI-powered tools',
                'icon' => '🚀',
                'meta_title' => 'SaaS & AI Tools Perks',
                'meta_description' => 'Get exclusive deals on sales, marketing, collaboration, development, AI automation, security, support, and accounting tools',
                'is_active' => true,
                'display_order' => 2,
            ],
            [
                'name' => 'B2B Services',
                'description' => 'Professional services to help your business grow and scale',
                'icon' => '💼',
                'meta_title' => 'B2B Services Perks',
                'meta_description' => 'Access exclusive B2B services for marketing, legal, accounting, HR, consulting, design, PR, and business development',
                'is_active' => true,
                'display_order' => 3,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }

        $this->command->info('✅ Categories seeded successfully!');
    }
}
