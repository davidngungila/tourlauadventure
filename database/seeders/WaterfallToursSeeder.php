<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tour;
use App\Models\TourItinerary;
use App\Models\Destination;
use Illuminate\Support\Str;

class WaterfallToursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create destinations
        $kilimanjaro = Destination::firstOrCreate(
            ['slug' => 'kilimanjaro'],
            ['name' => 'Mount Kilimanjaro', 'slug' => 'kilimanjaro']
        );
        
        $arusha = Destination::firstOrCreate(
            ['slug' => 'arusha'],
            ['name' => 'Arusha', 'slug' => 'arusha']
        );

        $waterfallTours = [
            // Kilimanjaro Waterfall Tours
            [
                'name' => 'Materuni Waterfall & Coffee Tour - Day Trip',
                'slug' => 'materuni-waterfall-coffee-tour',
                'destination_id' => $kilimanjaro->id,
                'short_description' => 'Discover the stunning Materuni Waterfall and experience authentic Chagga coffee culture in the foothills of Mount Kilimanjaro.',
                'description' => 'A perfect day trip combining nature and culture! Hike through lush rainforest to reach the magnificent Materuni Waterfall, then learn about traditional Chagga coffee making in a local village.',
                'long_description' => 'This immersive day trip takes you to the beautiful Materuni Waterfall, one of the most spectacular waterfalls in the Kilimanjaro region. The journey begins with a scenic drive through coffee plantations and banana farms to the Materuni village. From there, you\'ll embark on a moderate 1.5-hour hike through dense rainforest, following a crystal-clear stream. The trail offers stunning views of Mount Kilimanjaro and the surrounding countryside. Upon reaching the waterfall, you\'ll be amazed by the 80-meter cascade plunging into a natural pool perfect for swimming. After enjoying the waterfall, you\'ll visit a local Chagga family to learn about traditional coffee cultivation and processing. Participate in the entire coffee-making process from picking to roasting, and enjoy a cup of freshly brewed coffee. This tour provides an authentic cultural experience while showcasing the natural beauty of the Kilimanjaro region.',
                'duration_days' => 1,
                'duration_nights' => 0,
                'start_location' => 'Arusha/Moshi',
                'end_location' => 'Arusha/Moshi',
                'tour_type' => 'Day Trip',
                'max_group_size' => 12,
                'min_age' => 8,
                'price' => 85,
                'starting_price' => 85,
                'rating' => 4.8,
                'fitness_level' => 'moderate',
                'difficulty_level' => 'Easy',
                'image_url' => 'images/tours/materuni-waterfall.jpg',
                'gallery_images' => [
                    'images/tours/materuni-waterfall-1.jpg',
                    'images/tours/materuni-coffee.jpg',
                    'images/tours/kilimanjaro-foothills.jpg'
                ],
                'highlights' => [
                    'Hike to the spectacular 80-meter Materuni Waterfall',
                    'Swim in the natural pool at the base of the waterfall',
                    'Learn traditional Chagga coffee making process',
                    'Visit local Chagga village and interact with community',
                    'Stunning views of Mount Kilimanjaro',
                    'Walk through lush rainforest and coffee plantations',
                    'Authentic cultural experience',
                    'Small group tour for personalized attention'
                ],
                'inclusions' => [
                    'Professional English-speaking guide',
                    'Transportation from Arusha/Moshi',
                    'All entrance fees',
                    'Coffee tour and tasting',
                    'Lunch at local restaurant',
                    'Bottled water',
                    'All activities mentioned'
                ],
                'exclusions' => [
                    'Tips and gratuities',
                    'Personal expenses',
                    'Travel insurance',
                    'Alcoholic beverages'
                ],
                'terms_conditions' => 'Booking requires full payment. Cancellation 48+ hours before: full refund. Less than 48 hours: 50% refund.',
                'cancellation_policy' => 'Free cancellation up to 48 hours before departure. Less than 48 hours: 50% refund.',
                'important_notes' => 'Moderate fitness level required. Bring swimwear, towel, comfortable hiking shoes, and camera. Weather-dependent activity.',
                'meta_title' => 'Materuni Waterfall & Coffee Tour - Kilimanjaro Day Trip',
                'meta_description' => 'Experience the stunning Materuni Waterfall and authentic Chagga coffee culture. Perfect day trip from Arusha or Moshi.',
                'meta_keywords' => 'Materuni waterfall, Kilimanjaro, coffee tour, day trip, Chagga culture, Tanzania waterfalls',
                'availability_status' => 'Available',
                'is_featured' => true,
                'status' => 'active',
                'publish_status' => 'published'
            ],
            [
                'name' => 'Kikuletwa Hot Springs & Waterfall Adventure',
                'slug' => 'kikuletwa-hot-springs-waterfall',
                'destination_id' => $kilimanjaro->id,
                'short_description' => 'Relax in the crystal-clear natural hot springs and explore the beautiful Kikuletwa Waterfall in the heart of the Kilimanjaro region.',
                'description' => 'A refreshing day trip to the famous Kikuletwa Hot Springs, also known as "Chemka Hot Springs". Swim in the turquoise-blue natural pools surrounded by fig trees and enjoy the stunning waterfall nearby.',
                'long_description' => 'Escape the heat and immerse yourself in the natural beauty of Kikuletwa Hot Springs, one of Tanzania\'s hidden gems. Located in the middle of a dry landscape, these natural hot springs create an oasis of crystal-clear turquoise water. The springs are fed by underground sources from Mount Kilimanjaro, maintaining a perfect temperature year-round. Spend the day swimming, relaxing, and enjoying the peaceful atmosphere. The area is surrounded by massive fig trees providing natural shade, and the water is so clear you can see the bottom. Nearby, you\'ll also visit the beautiful Kikuletwa Waterfall, where water cascades over rocks creating a picturesque setting. This tour is perfect for those looking to combine relaxation with natural beauty. The journey includes a scenic drive through local villages and farmlands, offering insights into rural Tanzanian life.',
                'duration_days' => 1,
                'duration_nights' => 0,
                'start_location' => 'Arusha/Moshi',
                'end_location' => 'Arusha/Moshi',
                'tour_type' => 'Day Trip',
                'max_group_size' => 12,
                'min_age' => 6,
                'price' => 75,
                'starting_price' => 75,
                'rating' => 4.7,
                'fitness_level' => 'easy',
                'difficulty_level' => 'Easy',
                'image_url' => 'images/tours/kikuletwa-springs.jpg',
                'gallery_images' => [
                    'images/tours/kikuletwa-springs-1.jpg',
                    'images/tours/kikuletwa-waterfall.jpg',
                    'images/tours/hot-springs-swimming.jpg'
                ],
                'highlights' => [
                    'Swim in crystal-clear turquoise hot springs',
                    'Visit the beautiful Kikuletwa Waterfall',
                    'Relax in natural pools surrounded by fig trees',
                    'Perfect temperature water year-round',
                    'Scenic drive through rural Tanzania',
                    'Picnic lunch in natural setting',
                    'Great for families and all ages',
                    'Photography opportunities'
                ],
                'inclusions' => [
                    'Professional guide',
                    'Transportation',
                    'Entrance fees',
                    'Picnic lunch',
                    'Bottled water',
                    'All activities'
                ],
                'exclusions' => [
                    'Tips',
                    'Personal expenses',
                    'Travel insurance'
                ],
                'terms_conditions' => 'Full payment required. Cancellation 48+ hours: full refund.',
                'cancellation_policy' => 'Free cancellation up to 48 hours before.',
                'important_notes' => 'Bring swimwear, towel, sunscreen, and camera. Suitable for all fitness levels.',
                'meta_title' => 'Kikuletwa Hot Springs & Waterfall - Kilimanjaro Day Trip',
                'meta_description' => 'Relax in the beautiful Kikuletwa Hot Springs and explore the waterfall. Perfect day trip from Arusha.',
                'meta_keywords' => 'Kikuletwa, hot springs, Kilimanjaro, waterfall, day trip, Tanzania',
                'availability_status' => 'Available',
                'is_featured' => true,
                'status' => 'active',
                'publish_status' => 'published'
            ],
            [
                'name' => 'Chemka Hot Springs - Natural Oasis Day Trip',
                'slug' => 'chemka-hot-springs-day-trip',
                'destination_id' => $kilimanjaro->id,
                'short_description' => 'Escape to the natural paradise of Chemka Hot Springs, a hidden gem with crystal-clear turquoise waters perfect for swimming and relaxation.',
                'description' => 'Discover Chemka Hot Springs (also known as Kikuletwa), a stunning natural oasis in the heart of the Kilimanjaro region. Swim in the crystal-clear turquoise waters surrounded by massive fig trees.',
                'long_description' => 'Chemka Hot Springs, also known as Kikuletwa Hot Springs, is one of Tanzania\'s most beautiful natural attractions. Located in the middle of a dry landscape, these natural hot springs create an unexpected oasis of crystal-clear turquoise water. The springs are fed by underground sources from Mount Kilimanjaro, maintaining a perfect temperature year-round. The water is so clear you can see the bottom, and the area is surrounded by massive fig trees providing natural shade. This is the perfect place to relax, swim, and escape the heat. The journey includes a scenic drive through rural Tanzania, offering insights into local village life. You\'ll have plenty of time to swim, take photographs, and simply enjoy the peaceful atmosphere. A picnic lunch is included, allowing you to make a full day of this refreshing experience.',
                'duration_days' => 1,
                'duration_nights' => 0,
                'start_location' => 'Arusha/Moshi',
                'end_location' => 'Arusha/Moshi',
                'tour_type' => 'Day Trip',
                'max_group_size' => 12,
                'min_age' => 6,
                'price' => 75,
                'starting_price' => 75,
                'rating' => 4.9,
                'fitness_level' => 'easy',
                'difficulty_level' => 'Easy',
                'image_url' => 'images/tours/chemka-hot-springs.jpg',
                'gallery_images' => [
                    'images/tours/chemka-springs-1.jpg',
                    'images/tours/chemka-springs-2.jpg',
                    'images/tours/kikuletwa-swimming.jpg'
                ],
                'highlights' => [
                    'Swim in crystal-clear turquoise hot springs',
                    'Natural oasis in the middle of dry landscape',
                    'Perfect temperature water year-round',
                    'Surrounded by massive fig trees',
                    'Great for photography',
                    'Relaxing and refreshing experience',
                    'Scenic drive through rural Tanzania',
                    'Perfect for all ages and fitness levels'
                ],
                'inclusions' => [
                    'Professional English-speaking guide',
                    'Transportation from Arusha/Moshi',
                    'Entrance fees',
                    'Picnic lunch',
                    'Bottled water',
                    'All activities'
                ],
                'exclusions' => [
                    'Tips and gratuities',
                    'Personal expenses',
                    'Travel insurance',
                    'Alcoholic beverages'
                ],
                'terms_conditions' => 'Full payment required. Cancellation 48+ hours before: full refund. Less than 48 hours: 50% refund.',
                'cancellation_policy' => 'Free cancellation up to 48 hours before departure. Less than 48 hours: 50% refund.',
                'important_notes' => 'Bring swimwear, towel, sunscreen, and camera. Suitable for all fitness levels. Water is safe for swimming.',
                'meta_title' => 'Chemka Hot Springs Day Trip - Kilimanjaro Natural Oasis',
                'meta_description' => 'Experience the beautiful Chemka Hot Springs (Kikuletwa) - crystal-clear turquoise waters perfect for swimming. Day trip from Arusha or Moshi.',
                'meta_keywords' => 'Chemka hot springs, Kikuletwa, hot springs Tanzania, Kilimanjaro, day trip, natural springs',
                'availability_status' => 'Available',
                'is_featured' => true,
                'status' => 'active',
                'publish_status' => 'published'
            ],
            [
                'name' => 'Marangu Waterfalls & Cultural Experience',
                'slug' => 'marangu-waterfalls-cultural',
                'destination_id' => $kilimanjaro->id,
                'short_description' => 'Explore the beautiful Marangu Waterfalls and immerse yourself in Chagga culture with traditional village visits.',
                'description' => 'Discover multiple stunning waterfalls in the Marangu area, visit traditional Chagga caves, and experience authentic local culture in this comprehensive day tour.',
                'long_description' => 'Marangu, known as the "Gateway to Kilimanjaro", is home to several beautiful waterfalls and rich Chagga cultural heritage. This tour takes you through the scenic Marangu area where you\'ll visit multiple waterfalls, each with its own unique character. The main attraction is the series of cascading waterfalls that create natural pools perfect for swimming. You\'ll also explore the historic Chagga caves, which were used as hiding places during tribal wars. The cultural component includes visiting a traditional Chagga village, learning about local customs, and enjoying traditional music and dance performances. This tour combines natural beauty with deep cultural immersion, making it perfect for those interested in both nature and local traditions.',
                'duration_days' => 1,
                'duration_nights' => 0,
                'start_location' => 'Moshi',
                'end_location' => 'Moshi',
                'tour_type' => 'Day Trip',
                'max_group_size' => 10,
                'min_age' => 8,
                'price' => 90,
                'starting_price' => 90,
                'rating' => 4.6,
                'fitness_level' => 'moderate',
                'difficulty_level' => 'Easy',
                'image_url' => 'images/tours/marangu-waterfalls.jpg',
                'gallery_images' => [
                    'images/tours/marangu-waterfalls-1.jpg',
                    'images/tours/chagga-caves.jpg',
                    'images/tours/marangu-village.jpg'
                ],
                'highlights' => [
                    'Visit multiple beautiful waterfalls',
                    'Explore historic Chagga caves',
                    'Traditional village visit',
                    'Cultural performances',
                    'Learn about Chagga traditions',
                    'Swimming in natural pools',
                    'Scenic hiking trails',
                    'Authentic local lunch'
                ],
                'inclusions' => [
                    'Professional guide',
                    'Transportation',
                    'All entrance fees',
                    'Cultural performances',
                    'Traditional lunch',
                    'Bottled water'
                ],
                'exclusions' => [
                    'Tips',
                    'Personal expenses',
                    'Travel insurance'
                ],
                'terms_conditions' => 'Full payment required. Moderate fitness needed for hiking.',
                'cancellation_policy' => 'Free cancellation up to 48 hours before.',
                'important_notes' => 'Bring hiking shoes, swimwear, and camera. Moderate walking involved.',
                'meta_title' => 'Marangu Waterfalls & Cultural Tour - Kilimanjaro',
                'meta_description' => 'Explore Marangu waterfalls and experience authentic Chagga culture. Day trip from Moshi.',
                'meta_keywords' => 'Marangu waterfalls, Chagga culture, Kilimanjaro, cultural tour, Tanzania',
                'availability_status' => 'Available',
                'is_featured' => false,
                'status' => 'active',
                'publish_status' => 'published'
            ],
            // Arusha Waterfall Tours
            [
                'name' => 'Kinukamori Waterfall & Arusha National Park',
                'slug' => 'kinukamori-waterfall-arusha-park',
                'destination_id' => $arusha->id,
                'short_description' => 'Combine a visit to the beautiful Kinukamori Waterfall with a game drive in Arusha National Park for the perfect day adventure.',
                'description' => 'Experience the best of both worlds: explore the stunning Kinukamori Waterfall and enjoy wildlife viewing in Arusha National Park, all in one day.',
                'long_description' => 'This comprehensive day tour combines the natural beauty of Kinukamori Waterfall with the wildlife wonders of Arusha National Park. Start your day with a scenic drive to Kinukamori Waterfall, located in a lush forest area. The waterfall cascades beautifully and creates a serene atmosphere perfect for photography and relaxation. After enjoying the waterfall, you\'ll proceed to Arusha National Park, one of Tanzania\'s smaller but most beautiful parks. The park offers excellent wildlife viewing opportunities including giraffes, zebras, buffaloes, and various antelope species. You\'ll also have the chance to see Mount Meru, Tanzania\'s second-highest peak. The park\'s diverse habitats include montane forest, open grassland, and the beautiful Momella Lakes. This tour is perfect for those with limited time who want to experience both natural beauty and wildlife.',
                'duration_days' => 1,
                'duration_nights' => 0,
                'start_location' => 'Arusha',
                'end_location' => 'Arusha',
                'tour_type' => 'Day Trip',
                'max_group_size' => 6,
                'min_age' => 6,
                'price' => 150,
                'starting_price' => 150,
                'rating' => 4.7,
                'fitness_level' => 'easy',
                'difficulty_level' => 'Easy',
                'image_url' => 'images/tours/kinukamori-waterfall.jpg',
                'gallery_images' => [
                    'images/tours/kinukamori-waterfall-1.jpg',
                    'images/tours/arusha-national-park.jpg',
                    'images/tours/mount-meru.jpg'
                ],
                'highlights' => [
                    'Visit the beautiful Kinukamori Waterfall',
                    'Game drive in Arusha National Park',
                    'Wildlife viewing (giraffes, zebras, buffaloes)',
                    'Views of Mount Meru',
                    'Momella Lakes',
                    'Diverse landscapes',
                    'Professional guide',
                    'Small group size'
                ],
                'inclusions' => [
                    'Professional guide',
                    '4x4 safari vehicle',
                    'Park entrance fees',
                    'Waterfall entrance fees',
                    'Lunch',
                    'Bottled water',
                    'All activities'
                ],
                'exclusions' => [
                    'Tips',
                    'Personal expenses',
                    'Travel insurance',
                    'Alcoholic beverages'
                ],
                'terms_conditions' => 'Full payment required. Park fees subject to change.',
                'cancellation_policy' => 'Free cancellation up to 48 hours before.',
                'important_notes' => 'Bring camera, binoculars, and comfortable clothing. Weather-dependent.',
                'meta_title' => 'Kinukamori Waterfall & Arusha National Park Day Tour',
                'meta_description' => 'Combine Kinukamori Waterfall visit with Arusha National Park game drive. Perfect day trip.',
                'meta_keywords' => 'Kinukamori waterfall, Arusha National Park, day trip, Tanzania, wildlife',
                'availability_status' => 'Available',
                'is_featured' => true,
                'status' => 'active',
                'publish_status' => 'published'
            ],
            [
                'name' => 'Usa River Waterfalls & Coffee Plantation Tour',
                'slug' => 'usa-river-waterfalls-coffee',
                'destination_id' => $arusha->id,
                'short_description' => 'Explore the beautiful Usa River waterfalls and visit a local coffee plantation for an authentic cultural experience.',
                'description' => 'Discover the scenic Usa River waterfalls and learn about coffee cultivation on a working plantation. Perfect combination of nature and culture.',
                'long_description' => 'The Usa River area is known for its beautiful waterfalls and thriving coffee plantations. This tour takes you to explore the cascading waterfalls along the Usa River, where you can enjoy the natural beauty and take stunning photographs. The waterfalls are surrounded by lush vegetation and create a peaceful, refreshing atmosphere. After visiting the waterfalls, you\'ll tour a local coffee plantation where you\'ll learn about the entire coffee production process from bean to cup. See how coffee is grown, harvested, processed, and roasted. You\'ll have the opportunity to participate in the traditional coffee-making process and enjoy a freshly brewed cup of local coffee. This tour provides insights into both the natural beauty of the Arusha region and the important role of coffee in the local economy.',
                'duration_days' => 1,
                'duration_nights' => 0,
                'start_location' => 'Arusha',
                'end_location' => 'Arusha',
                'tour_type' => 'Day Trip',
                'max_group_size' => 10,
                'min_age' => 8,
                'price' => 80,
                'starting_price' => 80,
                'rating' => 4.5,
                'fitness_level' => 'easy',
                'difficulty_level' => 'Easy',
                'image_url' => 'images/tours/usa-river-waterfalls.jpg',
                'gallery_images' => [
                    'images/tours/usa-river-waterfalls-1.jpg',
                    'images/tours/coffee-plantation.jpg',
                    'images/tours/coffee-processing.jpg'
                ],
                'highlights' => [
                    'Visit Usa River waterfalls',
                    'Coffee plantation tour',
                    'Learn coffee production process',
                    'Coffee tasting experience',
                    'Scenic natural beauty',
                    'Cultural interaction',
                    'Small group tour',
                    'Authentic experience'
                ],
                'inclusions' => [
                    'Professional guide',
                    'Transportation',
                    'Entrance fees',
                    'Coffee tour and tasting',
                    'Lunch',
                    'Bottled water'
                ],
                'exclusions' => [
                    'Tips',
                    'Personal expenses',
                    'Travel insurance'
                ],
                'terms_conditions' => 'Full payment required.',
                'cancellation_policy' => 'Free cancellation up to 48 hours before.',
                'important_notes' => 'Bring camera and comfortable walking shoes.',
                'meta_title' => 'Usa River Waterfalls & Coffee Plantation Tour',
                'meta_description' => 'Explore Usa River waterfalls and visit coffee plantation. Day trip from Arusha.',
                'meta_keywords' => 'Usa River waterfalls, coffee plantation, Arusha, day trip, Tanzania',
                'availability_status' => 'Available',
                'is_featured' => false,
                'status' => 'active',
                'publish_status' => 'published'
            ],
            [
                'name' => 'Tengeru Waterfalls & Local Market Experience',
                'slug' => 'tengeru-waterfalls-market',
                'destination_id' => $arusha->id,
                'short_description' => 'Discover the hidden Tengeru Waterfalls and experience the vibrant local market culture in this authentic day tour.',
                'description' => 'Explore the beautiful Tengeru Waterfalls and immerse yourself in local culture with a visit to the bustling Tengeru Market.',
                'long_description' => 'Tengeru is a vibrant area just outside Arusha, known for its beautiful waterfalls and lively local market. This tour takes you to the scenic Tengeru Waterfalls, which cascade through lush forest creating a peaceful natural setting. The waterfalls are less visited than others, offering a more intimate experience. After enjoying the waterfalls, you\'ll visit the Tengeru Market, one of the largest and most colorful markets in the Arusha region. Here you\'ll experience authentic Tanzanian market culture, see local produce, crafts, and interact with friendly vendors. This tour provides a perfect blend of natural beauty and cultural immersion, giving you a genuine taste of local life in Tanzania.',
                'duration_days' => 1,
                'duration_nights' => 0,
                'start_location' => 'Arusha',
                'end_location' => 'Arusha',
                'tour_type' => 'Day Trip',
                'max_group_size' => 12,
                'min_age' => 8,
                'price' => 70,
                'starting_price' => 70,
                'rating' => 4.4,
                'fitness_level' => 'easy',
                'difficulty_level' => 'Easy',
                'image_url' => 'images/tours/tengeru-waterfalls.jpg',
                'gallery_images' => [
                    'images/tours/tengeru-waterfalls-1.jpg',
                    'images/tours/tengeru-market.jpg',
                    'images/tours/local-market.jpg'
                ],
                'highlights' => [
                    'Visit Tengeru Waterfalls',
                    'Explore Tengeru Market',
                    'Local culture immersion',
                    'Photography opportunities',
                    'Authentic market experience',
                    'Scenic natural beauty',
                    'Small group tour',
                    'Cultural interaction'
                ],
                'inclusions' => [
                    'Professional guide',
                    'Transportation',
                    'Entrance fees',
                    'Market tour',
                    'Lunch',
                    'Bottled water'
                ],
                'exclusions' => [
                    'Tips',
                    'Personal expenses',
                    'Travel insurance',
                    'Market purchases'
                ],
                'terms_conditions' => 'Full payment required.',
                'cancellation_policy' => 'Free cancellation up to 48 hours before.',
                'important_notes' => 'Bring camera and cash for market purchases if desired.',
                'meta_title' => 'Tengeru Waterfalls & Market Tour - Arusha',
                'meta_description' => 'Explore Tengeru waterfalls and experience local market culture. Day trip from Arusha.',
                'meta_keywords' => 'Tengeru waterfalls, Tengeru market, Arusha, day trip, Tanzania',
                'availability_status' => 'Available',
                'is_featured' => false,
                'status' => 'active',
                'publish_status' => 'published'
            ],
            [
                'name' => '2 Days Kilimanjaro Waterfalls Adventure',
                'slug' => '2-days-kilimanjaro-waterfalls-adventure',
                'destination_id' => $kilimanjaro->id,
                'short_description' => 'Explore multiple stunning waterfalls in the Kilimanjaro region over two days, including Materuni, Kikuletwa, and Marangu waterfalls.',
                'description' => 'A comprehensive 2-day tour visiting the most beautiful waterfalls in the Kilimanjaro region. Experience Materuni, Kikuletwa Hot Springs, and Marangu waterfalls with overnight accommodation.',
                'long_description' => 'This 2-day adventure takes you to the most spectacular waterfalls in the Kilimanjaro region. Day one includes visits to Materuni Waterfall with its coffee tour experience, and the refreshing Kikuletwa Hot Springs. Day two explores the Marangu Waterfalls and cultural sites. This extended tour allows you to fully experience each location without rushing, and includes comfortable overnight accommodation. Perfect for those who want to see multiple waterfalls and have time to truly appreciate each one.',
                'duration_days' => 2,
                'duration_nights' => 1,
                'start_location' => 'Arusha/Moshi',
                'end_location' => 'Arusha/Moshi',
                'tour_type' => 'Private',
                'max_group_size' => 8,
                'min_age' => 8,
                'price' => 280,
                'starting_price' => 280,
                'rating' => 4.8,
                'fitness_level' => 'moderate',
                'difficulty_level' => 'Easy',
                'image_url' => 'images/tours/kilimanjaro-waterfalls-2days.jpg',
                'gallery_images' => [
                    'images/tours/materuni-waterfall-1.jpg',
                    'images/tours/kikuletwa-springs-1.jpg',
                    'images/tours/marangu-waterfalls-1.jpg'
                ],
                'highlights' => [
                    'Visit Materuni Waterfall',
                    'Kikuletwa Hot Springs',
                    'Marangu Waterfalls',
                    'Coffee tour experience',
                    'Cultural interactions',
                    'Overnight accommodation',
                    'Multiple waterfall experiences',
                    'Comprehensive tour'
                ],
                'inclusions' => [
                    'Professional guide',
                    'Transportation',
                    'All entrance fees',
                    'Overnight accommodation',
                    'All meals',
                    'Coffee tour',
                    'Bottled water',
                    'All activities'
                ],
                'exclusions' => [
                    'Tips',
                    'Personal expenses',
                    'Travel insurance',
                    'Alcoholic beverages'
                ],
                'terms_conditions' => '30% deposit required. Full payment 7 days before.',
                'cancellation_policy' => 'Free cancellation up to 7 days before. 50% refund 3-6 days before.',
                'important_notes' => 'Moderate fitness required. Bring swimwear, hiking shoes, and camera.',
                'meta_title' => '2 Days Kilimanjaro Waterfalls Adventure Tour',
                'meta_description' => 'Explore multiple waterfalls in Kilimanjaro region over 2 days. Materuni, Kikuletwa, and Marangu waterfalls.',
                'meta_keywords' => 'Kilimanjaro waterfalls, 2 days tour, Materuni, Kikuletwa, Marangu, Tanzania',
                'availability_status' => 'Available',
                'is_featured' => true,
                'status' => 'active',
                'publish_status' => 'published'
            ]
        ];

        $createdCount = 0;
        $updatedCount = 0;
        
        foreach ($waterfallTours as $tourData) {
            // Add excerpt if not provided
            if (!isset($tourData['excerpt'])) {
                $tourData['excerpt'] = $tourData['short_description'];
            }
            
            $tour = Tour::firstOrCreate(
                ['slug' => $tourData['slug']],
                $tourData
            );
            
            if ($tour->wasRecentlyCreated) {
                $createdCount++;
                $this->command->info("Created: {$tour->name}");
            } else {
                $updatedCount++;
                // Update existing tour to ensure it's published
                $tour->update([
                    'status' => 'active',
                    'publish_status' => 'published'
                ]);
                $this->command->info("Updated: {$tour->name}");
            }

            // Create itinerary for each tour
            $this->createWaterfallItinerary($tour);
        }

        $this->command->info("Successfully processed {$createdCount} new and {$updatedCount} existing waterfall tours!");
    }

    /**
     * Create itinerary for waterfall tours
     */
    private function createWaterfallItinerary(Tour $tour)
    {
        // Skip if itinerary already exists
        if (TourItinerary::where('tour_id', $tour->id)->count() > 0) {
            return;
        }

        $slug = $tour->slug;
        $duration = $tour->duration_days;

        if ($duration == 1) {
            // Day trip itineraries
            if (str_contains($slug, 'materuni')) {
                $this->createMateruniItinerary($tour);
            } elseif (str_contains($slug, 'kikuletwa') || str_contains($slug, 'chemka')) {
                $this->createKikuletwaItinerary($tour);
            } elseif (str_contains($slug, 'marangu')) {
                $this->createMaranguItinerary($tour);
            } elseif (str_contains($slug, 'kinukamori')) {
                $this->createKinukamoriItinerary($tour);
            } elseif (str_contains($slug, 'usa-river')) {
                $this->createUsaRiverItinerary($tour);
            } elseif (str_contains($slug, 'tengeru')) {
                $this->createTengeruItinerary($tour);
            }
        } elseif ($duration == 2) {
            $this->create2DaysWaterfallItinerary($tour);
        }
    }

    private function createMateruniItinerary(Tour $tour)
    {
        TourItinerary::create([
            'tour_id' => $tour->id,
            'day_number' => 1,
            'title' => 'Materuni Waterfall & Coffee Tour',
            'short_summary' => 'Hike to Materuni Waterfall and experience Chagga coffee culture',
            'description' => 'Early morning pickup from your hotel in Arusha or Moshi. Drive through coffee plantations and banana farms to Materuni village (approximately 1 hour). Begin a moderate 1.5-hour hike through lush rainforest following a crystal-clear stream. Enjoy stunning views of Mount Kilimanjaro along the way. Arrive at the spectacular 80-meter Materuni Waterfall. Take time to swim in the natural pool at the base of the waterfall and capture amazing photos. After enjoying the waterfall, visit a local Chagga family. Learn about traditional coffee cultivation and participate in the entire coffee-making process from picking to roasting. Enjoy a cup of freshly brewed local coffee. Enjoy a traditional lunch at a local restaurant. Return to your hotel in the late afternoon.',
            'meals_included' => ['Breakfast', 'Lunch'],
            'accommodation_type' => 'N/A',
            'location' => 'Materuni Village, Kilimanjaro',
            'activities' => [
                ['name' => 'Hiking', 'icon' => 'hiking'],
                ['name' => 'Waterfall Visit', 'icon' => 'water'],
                ['name' => 'Swimming', 'icon' => 'swimming'],
                ['name' => 'Coffee Tour', 'icon' => 'coffee'],
                ['name' => 'Cultural Experience', 'icon' => 'users']
            ],
            'sort_order' => 1
        ]);
    }

    private function createKikuletwaItinerary(Tour $tour)
    {
        $title = str_contains($tour->slug, 'chemka') ? 'Chemka Hot Springs - Natural Oasis' : 'Kikuletwa Hot Springs & Waterfall';
        $location = str_contains($tour->slug, 'chemka') ? 'Chemka (Kikuletwa), Kilimanjaro' : 'Kikuletwa, Kilimanjaro';
        $description = str_contains($tour->slug, 'chemka') 
            ? 'Morning pickup from your hotel in Arusha or Moshi. Scenic drive through rural Tanzania to Chemka Hot Springs, also known as Kikuletwa (approximately 1.5 hours). Arrive at the natural oasis of crystal-clear turquoise hot springs in the middle of a dry landscape. The springs are fed by underground sources from Mount Kilimanjaro, maintaining perfect temperature year-round. Spend time swimming and relaxing in the crystal-clear water - so clear you can see the bottom. The springs are surrounded by massive fig trees providing natural shade. Enjoy a picnic lunch in the natural setting. More time to relax, swim, and take photographs of this beautiful natural paradise. Return to your hotel in the late afternoon feeling refreshed and rejuvenated.'
            : 'Morning pickup from your hotel. Scenic drive through rural Tanzania to Kikuletwa Hot Springs (approximately 1.5 hours). Arrive at the natural oasis of crystal-clear turquoise hot springs. Spend time swimming and relaxing in the perfect-temperature water. The springs are surrounded by massive fig trees providing natural shade. Enjoy a picnic lunch in the natural setting. Visit the nearby Kikuletwa Waterfall for photography and exploration. More time to relax and swim. Return to your hotel in the late afternoon.';
        
        TourItinerary::create([
            'tour_id' => $tour->id,
            'day_number' => 1,
            'title' => $title,
            'short_summary' => 'Relax in natural hot springs and enjoy the natural oasis',
            'description' => $description,
            'meals_included' => ['Lunch'],
            'accommodation_type' => 'N/A',
            'location' => $location,
            'activities' => [
                ['name' => 'Hot Springs', 'icon' => 'water'],
                ['name' => 'Swimming', 'icon' => 'swimming'],
                ['name' => 'Relaxation', 'icon' => 'sun'],
                ['name' => 'Photography', 'icon' => 'camera']
            ],
            'sort_order' => 1
        ]);
    }

    private function createMaranguItinerary(Tour $tour)
    {
        TourItinerary::create([
            'tour_id' => $tour->id,
            'day_number' => 1,
            'title' => 'Marangu Waterfalls & Cultural Tour',
            'short_summary' => 'Explore waterfalls and Chagga culture in Marangu',
            'description' => 'Morning pickup from Moshi. Drive to Marangu area (approximately 30 minutes). Begin hiking to visit multiple beautiful waterfalls in the area. Each waterfall offers unique characteristics and natural pools for swimming. Explore the historic Chagga caves, which were used as hiding places during tribal wars. Visit a traditional Chagga village and learn about local customs and traditions. Enjoy traditional music and dance performances by local artists. Enjoy an authentic local lunch. More time to explore waterfalls and take photographs. Return to Moshi in the late afternoon.',
            'meals_included' => ['Lunch'],
            'accommodation_type' => 'N/A',
            'location' => 'Marangu, Kilimanjaro',
            'activities' => [
                ['name' => 'Waterfall Hiking', 'icon' => 'hiking'],
                ['name' => 'Cave Exploration', 'icon' => 'mountain'],
                ['name' => 'Cultural Tour', 'icon' => 'users'],
                ['name' => 'Traditional Performances', 'icon' => 'music']
            ],
            'sort_order' => 1
        ]);
    }

    private function createKinukamoriItinerary(Tour $tour)
    {
        TourItinerary::create([
            'tour_id' => $tour->id,
            'day_number' => 1,
            'title' => 'Kinukamori Waterfall & Arusha National Park',
            'short_summary' => 'Waterfall visit and game drive in Arusha National Park',
            'description' => 'Early morning pickup from your hotel in Arusha. Drive to Kinukamori Waterfall (approximately 45 minutes). Hike through lush forest to reach the beautiful Kinukamori Waterfall. Enjoy the serene atmosphere and take photographs. After the waterfall, proceed to Arusha National Park. Begin game drive in the park, looking for wildlife including giraffes, zebras, buffaloes, and various antelope species. Enjoy views of Mount Meru, Tanzania\'s second-highest peak. Visit the beautiful Momella Lakes, home to various bird species. Picnic lunch in the park. Continue game drive exploring different habitats including montane forest and open grasslands. Return to Arusha in the late afternoon.',
            'meals_included' => ['Lunch'],
            'accommodation_type' => 'N/A',
            'location' => 'Kinukamori & Arusha National Park',
            'activities' => [
                ['name' => 'Waterfall Visit', 'icon' => 'water'],
                ['name' => 'Game Drive', 'icon' => 'binoculars'],
                ['name' => 'Wildlife Viewing', 'icon' => 'camera'],
                ['name' => 'Nature Photography', 'icon' => 'camera']
            ],
            'sort_order' => 1
        ]);
    }

    private function createUsaRiverItinerary(Tour $tour)
    {
        TourItinerary::create([
            'tour_id' => $tour->id,
            'day_number' => 1,
            'title' => 'Usa River Waterfalls & Coffee Plantation',
            'short_summary' => 'Explore waterfalls and visit coffee plantation',
            'description' => 'Morning pickup from Arusha. Drive to Usa River area (approximately 30 minutes). Visit the beautiful cascading waterfalls along the Usa River. Enjoy the natural beauty and take photographs. Tour a local coffee plantation and learn about coffee cultivation. See the entire coffee production process from growing to harvesting. Participate in traditional coffee processing and roasting. Enjoy a freshly brewed cup of local coffee. Traditional lunch at the plantation. More time to explore the waterfalls and surrounding area. Return to Arusha in the late afternoon.',
            'meals_included' => ['Lunch'],
            'accommodation_type' => 'N/A',
            'location' => 'Usa River, Arusha',
            'activities' => [
                ['name' => 'Waterfall Visit', 'icon' => 'water'],
                ['name' => 'Coffee Plantation Tour', 'icon' => 'coffee'],
                ['name' => 'Coffee Tasting', 'icon' => 'coffee'],
                ['name' => 'Cultural Experience', 'icon' => 'users']
            ],
            'sort_order' => 1
        ]);
    }

    private function createTengeruItinerary(Tour $tour)
    {
        TourItinerary::create([
            'tour_id' => $tour->id,
            'day_number' => 1,
            'title' => 'Tengeru Waterfalls & Market Tour',
            'short_summary' => 'Visit waterfalls and explore local market',
            'description' => 'Morning pickup from Arusha. Short drive to Tengeru area (approximately 20 minutes). Hike to the scenic Tengeru Waterfalls through lush forest. Enjoy the peaceful natural setting and take photographs. Visit the vibrant Tengeru Market, one of the largest markets in the Arusha region. Explore the colorful stalls selling local produce, crafts, and goods. Interact with friendly local vendors and experience authentic market culture. Enjoy local lunch at a market restaurant. More time to explore the market or return to waterfalls. Return to Arusha in the late afternoon.',
            'meals_included' => ['Lunch'],
            'accommodation_type' => 'N/A',
            'location' => 'Tengeru, Arusha',
            'activities' => [
                ['name' => 'Waterfall Visit', 'icon' => 'water'],
                ['name' => 'Market Tour', 'icon' => 'shopping'],
                ['name' => 'Cultural Experience', 'icon' => 'users'],
                ['name' => 'Photography', 'icon' => 'camera']
            ],
            'sort_order' => 1
        ]);
    }

    private function create2DaysWaterfallItinerary(Tour $tour)
    {
        // Day 1
        TourItinerary::create([
            'tour_id' => $tour->id,
            'day_number' => 1,
            'title' => 'Materuni Waterfall & Kikuletwa Hot Springs',
            'short_summary' => 'Visit Materuni Waterfall and Kikuletwa Hot Springs',
            'description' => 'Early morning pickup from your hotel. Drive to Materuni village and hike to the spectacular 80-meter Materuni Waterfall. Enjoy swimming in the natural pool and participate in the coffee tour experience. After lunch, proceed to Kikuletwa Hot Springs. Spend the afternoon relaxing in the crystal-clear turquoise hot springs. Overnight at a comfortable lodge in Moshi.',
            'meals_included' => ['Breakfast', 'Lunch', 'Dinner'],
            'accommodation_type' => 'Lodge',
            'accommodation_name' => 'Moshi Lodge or Similar',
            'accommodation_location' => 'Moshi',
            'location' => 'Materuni & Kikuletwa, Kilimanjaro',
            'activities' => [
                ['name' => 'Materuni Waterfall', 'icon' => 'water'],
                ['name' => 'Coffee Tour', 'icon' => 'coffee'],
                ['name' => 'Kikuletwa Hot Springs', 'icon' => 'water'],
                ['name' => 'Swimming', 'icon' => 'swimming']
            ],
            'sort_order' => 1
        ]);

        // Day 2
        TourItinerary::create([
            'tour_id' => $tour->id,
            'day_number' => 2,
            'title' => 'Marangu Waterfalls & Cultural Experience',
            'short_summary' => 'Explore Marangu waterfalls and Chagga culture',
            'description' => 'After breakfast, drive to Marangu area. Explore multiple beautiful waterfalls in the Marangu region. Visit historic Chagga caves and learn about local history. Experience traditional Chagga village life with cultural performances. Enjoy traditional lunch. More time to explore waterfalls and take photographs. Return to Arusha/Moshi in the late afternoon.',
            'meals_included' => ['Breakfast', 'Lunch'],
            'accommodation_type' => 'N/A',
            'location' => 'Marangu, Kilimanjaro',
            'activities' => [
                ['name' => 'Marangu Waterfalls', 'icon' => 'water'],
                ['name' => 'Cave Exploration', 'icon' => 'mountain'],
                ['name' => 'Cultural Tour', 'icon' => 'users'],
                ['name' => 'Traditional Performances', 'icon' => 'music']
            ],
            'sort_order' => 2
        ]);
    }
}

