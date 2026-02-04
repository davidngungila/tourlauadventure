<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AboutPageTeamMember;

class TeamMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teamMembers = [
            [
                'name' => 'Laurence',
                'role' => 'Founder & CEO',
                'bio' => 'With extensive experience in Tanzania tourism, Laurence founded Lau Paradise Adventures to share his passion for Tanzania\'s natural wonders. He has over 15 years of experience guiding travelers through the Serengeti, Ngorongoro Crater, and Mount Kilimanjaro. His vision is to provide authentic, sustainable, and transformative travel experiences that connect visitors with Tanzania\'s incredible wildlife and rich culture.',
                'image_url' => null, // Will use fallback avatar
                'expertise' => [
                    'Safari Expert',
                    'Wildlife Guide',
                    'Kilimanjaro Climbing',
                    'Tour Planning',
                    'Cultural Tourism',
                    'Conservation'
                ],
                'social_links' => [
                    'facebook' => 'https://facebook.com/laurence',
                    'linkedin' => 'https://linkedin.com/in/laurence',
                    'twitter' => 'https://twitter.com/laurence'
                ],
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Sarah Johnson',
                'role' => 'Head of Operations',
                'bio' => 'Sarah brings over 12 years of experience in tourism operations and customer service. She ensures every detail of your journey is perfectly planned and executed. Her expertise in logistics and guest relations guarantees smooth, memorable experiences for all our travelers.',
                'image_url' => null, // Will use fallback avatar
                'expertise' => [
                    'Operations Management',
                    'Customer Service',
                    'Logistics Planning',
                    'Quality Assurance',
                    'Team Leadership'
                ],
                'social_links' => [
                    'linkedin' => 'https://linkedin.com/in/sarah',
                    'facebook' => 'https://facebook.com/sarah'
                ],
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Jackson Lyoseko',
                'role' => 'Senior Safari Guide',
                'bio' => 'Jackson is one of Tanzania\'s most experienced safari guides with over 20 years in the field. His deep knowledge of wildlife behavior, tracking skills, and passion for conservation make every safari an educational and thrilling adventure. He has guided thousands of travelers through the Serengeti, Ngorongoro, and Tarangire National Parks.',
                'image_url' => null, // Will use fallback avatar
                'expertise' => [
                    'Wildlife Tracking',
                    'Safari Guiding',
                    'Bird Watching',
                    'Photography Tours',
                    'Conservation Education',
                    'Big Five Specialist'
                ],
                'social_links' => [
                    'facebook' => 'https://facebook.com/jackson',
                    'instagram' => 'https://instagram.com/jackson'
                ],
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Amina Hassan',
                'role' => 'Cultural Tourism Specialist',
                'bio' => 'Amina specializes in cultural immersion experiences, connecting travelers with local communities and traditions. She speaks fluent Swahili, English, and several local dialects. Her tours provide authentic insights into Tanzanian culture, from Maasai villages to Zanzibar spice farms.',
                'image_url' => null, // Will use fallback avatar
                'expertise' => [
                    'Cultural Tours',
                    'Community Engagement',
                    'Language Translation',
                    'Heritage Preservation',
                    'Local Partnerships'
                ],
                'social_links' => [
                    'linkedin' => 'https://linkedin.com/in/amina',
                    'facebook' => 'https://facebook.com/amina'
                ],
                'display_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'David Mwangi',
                'role' => 'Kilimanjaro Climbing Guide',
                'bio' => 'David is a certified mountain guide with over 18 years of experience leading climbers to the summit of Mount Kilimanjaro. He has successfully guided over 500 expeditions and holds certifications in mountain rescue and high-altitude first aid. His patient, encouraging approach helps climbers of all levels achieve their summit dreams.',
                'image_url' => null, // Will use fallback avatar
                'expertise' => [
                    'Mountain Climbing',
                    'High Altitude Guiding',
                    'Safety & Rescue',
                    'Fitness Training',
                    'Route Planning',
                    'Equipment Management'
                ],
                'social_links' => [
                    'linkedin' => 'https://linkedin.com/in/david',
                    'instagram' => 'https://instagram.com/david'
                ],
                'display_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Grace Kimathi',
                'role' => 'Zanzibar Beach Specialist',
                'bio' => 'Grace is our expert for all things Zanzibar. With deep knowledge of the island\'s history, beaches, and culture, she creates unforgettable beach holidays and cultural experiences. From Stone Town tours to spice farm visits and pristine beach getaways, Grace ensures every moment in Zanzibar is magical.',
                'image_url' => null, // Will use fallback avatar
                'expertise' => [
                    'Beach Holidays',
                    'Island Tours',
                    'Water Sports',
                    'Historical Tours',
                    'Spice Farm Visits',
                    'Dhow Cruises'
                ],
                'social_links' => [
                    'facebook' => 'https://facebook.com/grace',
                    'instagram' => 'https://instagram.com/grace'
                ],
                'display_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Michael Chen',
                'role' => 'Photography Tour Guide',
                'bio' => 'Michael combines his passion for wildlife photography with expert guiding skills. He helps photographers capture stunning images of Tanzania\'s wildlife and landscapes. With knowledge of animal behavior and lighting conditions, he ensures photographers get the perfect shots.',
                'image_url' => null, // Will use fallback avatar
                'expertise' => [
                    'Wildlife Photography',
                    'Landscape Photography',
                    'Camera Techniques',
                    'Lighting & Composition',
                    'Photo Tours',
                    'Equipment Advice'
                ],
                'social_links' => [
                    'instagram' => 'https://instagram.com/michael',
                    'linkedin' => 'https://linkedin.com/in/michael'
                ],
                'display_order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Fatuma Juma',
                'role' => 'Customer Relations Manager',
                'bio' => 'Fatuma ensures every guest receives personalized attention from the moment they inquire until after they return home. Her warm, friendly approach and attention to detail make travelers feel valued and cared for throughout their journey with us.',
                'image_url' => null, // Will use fallback avatar
                'expertise' => [
                    'Customer Relations',
                    'Travel Consultation',
                    'Booking Management',
                    'Guest Services',
                    'Problem Solving'
                ],
                'social_links' => [
                    'facebook' => 'https://facebook.com/fatuma',
                    'linkedin' => 'https://linkedin.com/in/fatuma'
                ],
                'display_order' => 8,
                'is_active' => true,
            ],
        ];

        foreach ($teamMembers as $member) {
            AboutPageTeamMember::updateOrCreate(
                ['name' => $member['name']],
                $member
            );
        }

        $this->command->info('Team members seeded successfully!');
    }
}
