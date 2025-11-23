<?php
namespace Database\Seeders;
use App\Models\{Category, Perk, Subcategory};
use Illuminate\Database\Seeder;

class PerkSeeder extends Seeder
{
    public function run(): void
    {
        $samples = [
            [
                'category' => 'Lifestyle',
                'subcategory' => 'Live & Work Anywhere',
                'title' => 'WeWork - 20% Off Flexible Workspace',
                'description' => '<p>Get 20% off flexible workspace memberships for remote teams</p>',
                'short_description' => '20% off WeWork flexible workspace',
                'partner_name' => 'WeWork',
                'redeem_type' => 'external_link',
                'external_url' => 'https://www.wework.com/startups',
                'location' => 'global',
                'valid_until' => now()->addMonths(6),
                'is_featured' => true,
                'status' => 'published',
            ],
            [
                'category' => 'Lifestyle',
                'subcategory' => 'Health & Wellbeing',
                'title' => 'Headspace - 3 Months Free Mental Wellness',
                'description' => '<p>Access meditation and mental wellness tools with 3 months free Headspace.</p>',
                'short_description' => '3 months free Headspace for mental wellness',
                'partner_name' => 'Headspace',
                'redeem_type' => 'coupon_code',
                'coupon_code' => 'WELLNESS3',
                'location' => 'global',
                'valid_until' => now()->addMonths(3),
                'is_featured' => false,
                'status' => 'published',
            ],
            [
                'category' => 'SaaS & AI Tools',
                'subcategory' => 'Close More Deals',
                'title' => 'HubSpot CRM - Free for Startups',
                'description' => '<p>Manage your sales pipeline with HubSpot CRM. Free for startups.</p>',
                'short_description' => 'Free HubSpot CRM for startups',
                'partner_name' => 'HubSpot',
                'redeem_type' => 'external_link',
                'external_url' => 'https://www.hubspot.com/startups',
                'location' => 'global',
                'valid_until' => now()->addMonths(12),
                'is_featured' => true,
                'status' => 'published',
            ],
            [
                'category' => 'SaaS & AI Tools',
                'subcategory' => 'Automate with AI',
                'title' => 'OpenAI - $500 API Credits',
                'description' => '<p>Build AI-powered features with $500 in OpenAI API credits.</p>',
                'short_description' => '$500 in OpenAI API credits',
                'partner_name' => 'OpenAI',
                'redeem_type' => 'lead_form',
                'location' => 'global',
                'valid_until' => now()->addMonths(4),
                'is_featured' => true,
                'status' => 'published',
            ],
            [
                'category' => 'B2B Services',
                'subcategory' => 'Handle Legal Stuff',
                'title' => 'Stripe Atlas - $100 Off Company Formation',
                'description' => '<p>Form your company with Stripe Atlas and get $100 off.</p>',
                'short_description' => '$100 off Stripe Atlas company formation',
                'partner_name' => 'Stripe Atlas',
                'redeem_type' => 'coupon_code',
                'coupon_code' => 'ATLAS100',
                'location' => 'global',
                'valid_until' => now()->addMonths(6),
                'is_featured' => false,
                'status' => 'published',
            ],
            [
                'category' => 'B2B Services',
                'subcategory' => 'Design Your Brand',
                'title' => 'Figma - Free Professional Plan for 1 Year',
                'description' => '<p>Design your brand with Figma Professional plan free for 1 year.</p>',
                'short_description' => 'Free Figma Professional for 1 year',
                'partner_name' => 'Figma',
                'redeem_type' => 'external_link',
                'external_url' => 'https://www.figma.com/startups',
                'location' => 'global',
                'valid_until' => now()->addMonths(3),
                'is_featured' => false,
                'status' => 'published',
            ],
        ];

        foreach ($samples as $data) {
            $category = Category::where('name', $data['category'])->first();
            $subcategory = Subcategory::where('name', $data['subcategory'])->first();

            if (!$category || !$subcategory) {
                $this->command->warn("⚠️  Skipping perk '{$data['title']}' — category/subcategory not found.");
                continue;
            }

            Perk::updateOrCreate(
                ['title' => $data['title']],
                [
                    'category_id' => $category->id,
                    'subcategory_id' => $subcategory->id,
                    'description' => $data['description'],
                    'short_description' => $data['short_description'] ?? null,
                    'partner_name' => $data['partner_name'],
                    // partner_url removed by migration 2025_11_20_181219
                    'redeem_type' => $data['redeem_type'],
                    'coupon_code' => $data['coupon_code'] ?? null,
                    'external_url' => $data['external_url'] ?? null,
                    'location' => $data['location'] ?? 'global',
                    'valid_until' => $data['valid_until'] ?? null,
                    'is_featured' => $data['is_featured'] ?? false,
                    'is_active' => true,
                    'status' => $data['status'] ?? 'published',
                    'published_at' => ($data['status'] ?? 'published') === 'published' ? now() : null,
                    // display_order removed by migration 2025_11_20_181219
                ]
            );
        }

        $this->command->info('✅ Sample perks seeded!');
    }
}
