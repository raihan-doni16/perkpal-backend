<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;

class SubcategorySeeder extends Seeder
{
    public function run(): void
    {
        $subcategories = [
            // Lifestyle
            ['category_name' => 'Lifestyle', 'name' => 'Live & Work Anywhere'],
            ['category_name' => 'Lifestyle', 'name' => 'Health & Wellbeing'],
            ['category_name' => 'Lifestyle', 'name' => 'Manage Your Money'],
            ['category_name' => 'Lifestyle', 'name' => 'Fuel Your Team'],
            ['category_name' => 'Lifestyle', 'name' => 'Community & Experiences'],
            ['category_name' => 'Lifestyle', 'name' => 'Founder Essentials'],

            // SaaS & AI Tools
            ['category_name' => 'SaaS & AI Tools', 'name' => 'Close More Deals'],
            ['category_name' => 'SaaS & AI Tools', 'name' => 'Market Like a Pro'],
            ['category_name' => 'SaaS & AI Tools', 'name' => 'Work Smarter Together'],
            ['category_name' => 'SaaS & AI Tools', 'name' => 'Build & Deploy Fast'],
            ['category_name' => 'SaaS & AI Tools', 'name' => 'Automate with AI'],
            ['category_name' => 'SaaS & AI Tools', 'name' => 'Stay Secure'],
            ['category_name' => 'SaaS & AI Tools', 'name' => 'Support Your Customers'],
            ['category_name' => 'SaaS & AI Tools', 'name' => 'Handle the Numbers'],

            // B2B Services
            ['category_name' => 'B2B Services', 'name' => 'Grow Your Reach'],
            ['category_name' => 'B2B Services', 'name' => 'Handle Legal Stuff'],
            ['category_name' => 'B2B Services', 'name' => 'Manage Your Books'],
            ['category_name' => 'B2B Services', 'name' => 'Build Your Team'],
            ['category_name' => 'B2B Services', 'name' => 'Get Expert Advice'],
            ['category_name' => 'B2B Services', 'name' => 'Design Your Brand'],
            ['category_name' => 'B2B Services', 'name' => 'Tell Your Story'],
            ['category_name' => 'B2B Services', 'name' => 'Find New Opportunities'],
        ];

        foreach ($subcategories as $subcategoryData) {
            $category = Category::where('name', $subcategoryData['category_name'])->first();

            if ($category) {
                Subcategory::updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'name' => $subcategoryData['name'],
                    ],
                    [
                        'is_active' => true,
                    ]
                );
            }
        }

        $this->command->info('✅ Subcategories seeded successfully!');
    }
}
