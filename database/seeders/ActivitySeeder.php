<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activities = [
            [
                'name' => 'Wildlife Safari',
                'description' => 'Game drives, Big Five sightings, and incredible wildlife encounters',
                'icon' => 'fas fa-binoculars',
                'image_url' => 'images/activities/wildlife-safari.jpg',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Mountain Climbing',
                'description' => 'Conquer Kilimanjaro and other peaks with expert guides',
                'icon' => 'fas fa-mountain',
                'image_url' => 'images/activities/mountain-climbing.jpg',
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Beach Holidays',
                'description' => 'Relax on pristine beaches in Zanzibar and coastal areas',
                'icon' => 'fas fa-umbrella-beach',
                'image_url' => 'images/activities/beach-holidays.jpg',
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Photography Tours',
                'description' => 'Capture stunning wildlife and landscapes with professional guidance',
                'icon' => 'fas fa-camera',
                'image_url' => 'images/activities/photography-tours.jpg',
                'display_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Cultural Tours',
                'description' => 'Experience local cultures, traditions, and authentic interactions',
                'icon' => 'fas fa-users',
                'image_url' => 'images/activities/cultural-tours.jpg',
                'display_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Water Activities',
                'description' => 'Snorkeling, diving, boat safaris, and water sports',
                'icon' => 'fas fa-water',
                'image_url' => 'images/activities/water-activities.jpg',
                'display_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($activities as $activity) {
            Activity::updateOrCreate(
                ['name' => $activity['name']],
                $activity
            );
        }

        $this->command->info('✓ Activities seeded successfully!');
    }
}












