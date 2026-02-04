<?php

namespace Database\Seeders;

use App\Models\AboutPageValue;
use Illuminate\Database\Seeder;

class CoreValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $values = [
            [
                'title' => 'Sustainability',
                'description' => 'We\'re committed to eco-friendly practices, minimizing our environmental impact, and supporting conservation efforts across Tanzania.',
                'icon' => 'fas fa-leaf',
                'image_url' => 'images/values/sustainability.jpg',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Community',
                'description' => 'We support local communities by hiring local guides, using local suppliers, and contributing to community development projects.',
                'icon' => 'fas fa-users',
                'image_url' => 'images/values/community.jpg',
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Safety',
                'description' => 'Your safety is our top priority. We maintain the highest safety standards and provide comprehensive support throughout your journey.',
                'icon' => 'fas fa-shield-alt',
                'image_url' => 'images/values/safety.jpg',
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Excellence',
                'description' => 'We strive for excellence in every detail, from expert guides to premium accommodations and seamless service.',
                'icon' => 'fas fa-star',
                'image_url' => 'images/values/excellence.jpg',
                'display_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Authenticity',
                'description' => 'We provide genuine Tanzanian experiences that connect you with the real culture, wildlife, and landscapes of our country.',
                'icon' => 'fas fa-heart',
                'image_url' => 'images/values/authenticity.jpg',
                'display_order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'Integrity',
                'description' => 'We operate with honesty, transparency, and ethical business practices in all our interactions and operations.',
                'icon' => 'fas fa-handshake',
                'image_url' => 'images/values/integrity.jpg',
                'display_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($values as $value) {
            AboutPageValue::updateOrCreate(
                ['title' => $value['title']],
                $value
            );
        }

        $this->command->info('✓ Core Values seeded successfully!');
    }
}












