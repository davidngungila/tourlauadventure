<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContactPageSection;
use App\Models\ContactPageFeature;

class ContactPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sections
        $sections = [
            [
                'section_key' => 'hero',
                'section_name' => 'Hero Section',
                'content' => null,
                'data' => [
                    'badge' => 'We\'re Here to Help',
                    'title' => 'Get In Touch',
                    'subtitle' => 'We\'re here to help you plan your perfect Tanzania adventure. Reach out to our Tanzania-based team anytime - we\'re available 24/7.',
                ],
                'image_url' => null,
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'section_key' => 'why_contact',
                'section_name' => 'Why Contact Us',
                'content' => null,
                'data' => [
                    'badge' => 'Why Contact Us',
                    'title' => 'We\'re Here to Make Your Dream Trip a Reality',
                    'subtitle' => 'Our experienced team is ready to help you plan the perfect Tanzania adventure.',
                ],
                'image_url' => null,
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'section_key' => 'features',
                'section_name' => 'Why Choose Us / Features',
                'content' => null,
                'data' => [
                    'badge' => 'Why Choose Us',
                    'title' => 'Experience the Difference',
                    'subtitle' => 'We go beyond just booking tours - we create unforgettable experiences tailored to you.',
                ],
                'image_url' => null,
                'display_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($sections as $section) {
            ContactPageSection::updateOrCreate(
                ['section_key' => $section['section_key']],
                $section
            );
        }

        // Create features
        $features = [
            [
                'title' => 'Flexible Booking',
                'description' => 'Easy rescheduling and cancellation options. Book with confidence knowing you can adjust your plans.',
                'icon' => 'fas fa-calendar-check',
                'image_url' => '/images/contact/flexible-booking.jpg',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Best Price Guarantee',
                'description' => 'We offer competitive prices and match any lower price you find elsewhere. Your satisfaction is our priority.',
                'icon' => 'fas fa-dollar-sign',
                'image_url' => '/images/contact/best-price.jpg',
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => '24/7 Support',
                'description' => 'Round-the-clock assistance before, during, and after your trip. We\'re always here when you need us.',
                'icon' => 'fas fa-headset',
                'image_url' => '/images/contact/support.jpg',
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Licensed & Insured',
                'description' => 'Fully licensed tour operator with comprehensive insurance coverage for your peace of mind.',
                'icon' => 'fas fa-certificate',
                'image_url' => '/images/contact/licensed.jpg',
                'display_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Local Expertise',
                'description' => 'Born and raised in Tanzania, our team knows every corner of this beautiful country intimately.',
                'icon' => 'fas fa-users',
                'image_url' => '/images/contact/local-expertise.jpg',
                'display_order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'Passionate Service',
                'description' => 'We\'re not just a business - we\'re passionate about sharing Tanzania\'s beauty with the world.',
                'icon' => 'fas fa-heart',
                'image_url' => '/images/contact/passionate.jpg',
                'display_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($features as $feature) {
            ContactPageFeature::updateOrCreate(
                ['title' => $feature['title']],
                $feature
            );
        }
    }
}
