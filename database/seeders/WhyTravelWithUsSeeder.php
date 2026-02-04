<?php

namespace Database\Seeders;

use App\Models\WhyTravelWithUs;
use Illuminate\Database\Seeder;

class WhyTravelWithUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'title' => 'Expert Local Guides',
                'description' => 'Our guides are Tanzanian-born experts with extensive knowledge of wildlife, culture, and safety protocols. They speak multiple languages and have years of experience.',
                'image_url' => 'images/why-travel-with-us/expert-guides.jpg',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Sustainable Tourism',
                'description' => 'We\'re committed to eco-friendly practices and supporting local communities through responsible tourism. We partner with conservation organizations and local initiatives.',
                'image_url' => 'images/why-travel-with-us/sustainable-tourism.jpg',
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Safety First Approach',
                'description' => 'Your safety is our priority. We maintain the highest safety standards, provide comprehensive insurance, and have 24/7 emergency support for all our Tanzania adventures.',
                'image_url' => 'images/why-travel-with-us/safety-first.jpg',
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Award Winning Service',
                'description' => 'Recognized as Tanzania\'s top tour operator with multiple awards for excellence in service and experience. We\'re TATO certified and fully licensed.',
                'image_url' => 'images/why-travel-with-us/award-winning.jpg',
                'display_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Local Partnerships',
                'description' => 'We work with trusted local partners, lodges, and suppliers to ensure authentic experiences while supporting Tanzania\'s tourism economy.',
                'image_url' => 'images/why-travel-with-us/local-partnerships.jpg',
                'display_order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'Personalized Service',
                'description' => 'Every tour is tailored to your interests and preferences. Our team works closely with you to create the perfect Tanzania adventure.',
                'image_url' => 'images/why-travel-with-us/personalized-service.jpg',
                'display_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($items as $item) {
            WhyTravelWithUs::updateOrCreate(
                ['title' => $item['title']],
                $item
            );
        }

        $this->command->info('✓ Why Travel With Us items seeded successfully!');
    }
}












