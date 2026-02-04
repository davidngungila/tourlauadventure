<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tour;
use App\Models\Destination;
use App\Models\TourItinerary;
use Illuminate\Support\Str;

class DayTripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create destinations
        $arusha = Destination::firstOrCreate(['slug' => 'arusha'], ['name' => 'Arusha', 'slug' => 'arusha']);
        $tarangire = Destination::firstOrCreate(['slug' => 'tarangire'], ['name' => 'Tarangire National Park', 'slug' => 'tarangire']);
        $ngorongoro = Destination::firstOrCreate(['slug' => 'ngorongoro'], ['name' => 'Ngorongoro Crater', 'slug' => 'ngorongoro']);
        $manyara = Destination::firstOrCreate(['slug' => 'manyara'], ['name' => 'Lake Manyara', 'slug' => 'manyara']);
        $kilimanjaro = Destination::firstOrCreate(['slug' => 'kilimanjaro'], ['name' => 'Mount Kilimanjaro', 'slug' => 'kilimanjaro']);
        $zanzibar = Destination::firstOrCreate(['slug' => 'zanzibar'], ['name' => 'Zanzibar', 'slug' => 'zanzibar']);
        $serengeti = Destination::firstOrCreate(['slug' => 'serengeti'], ['name' => 'Serengeti National Park', 'slug' => 'serengeti']);

        $dayTrips = $this->getDayTripsData($arusha, $tarangire, $ngorongoro, $manyara, $kilimanjaro, $zanzibar, $serengeti);

        foreach ($dayTrips as $tripData) {
            $itineraries = $tripData['itineraries'] ?? [];
            unset($tripData['itineraries']);
            
            $tour = Tour::updateOrCreate(
                ['slug' => $tripData['slug']],
                $tripData
            );

            // Create detailed itineraries for this day trip
            if (!empty($itineraries)) {
                foreach ($itineraries as $itineraryData) {
                    $itineraryData['tour_id'] = $tour->id;
                    TourItinerary::updateOrCreate(
                        [
                            'tour_id' => $tour->id,
                            'day_number' => $itineraryData['day_number'],
                            'sort_order' => $itineraryData['sort_order']
                        ],
                        $itineraryData
                    );
                }
            }
        }

        $totalTrips = count($dayTrips);
        $this->command->info("Successfully seeded {$totalTrips} day trips with detailed itineraries for Tanzania!");
        $this->command->info("Trips include: " . implode(', ', array_slice(array_column($dayTrips, 'slug'), 0, 10)) . (count($dayTrips) > 10 ? '...' : ''));
    }

    private function getDayTripsData($arusha, $tarangire, $ngorongoro, $manyara, $kilimanjaro, $zanzibar, $serengeti): array
    {
        return [
            // 1. Tarangire National Park Day Trip
            [
                'name' => 'Tarangire National Park Day Trip - Elephant Paradise',
                'slug' => 'tarangire-national-park-day-trip',
                'destination_id' => $tarangire->id,
                'short_description' => 'Experience Tarangire National Park in a full-day safari adventure. Witness massive elephant herds, ancient baobab trees, and diverse wildlife.',
                'description' => 'Embark on an unforgettable day trip to Tarangire National Park, known for its large elephant herds and iconic baobab trees. This full-day safari takes you through diverse landscapes from river valleys to open grasslands.',
                'long_description' => 'Tarangire National Park is one of Tanzania\'s hidden gems, famous for having the largest concentration of elephants in the country. This full-day adventure begins with an early morning departure from Arusha, taking you through the scenic countryside to the park entrance. Once inside, you\'ll embark on game drives through varied terrain including river valleys, swamps, and open grasslands. The park is home to over 550 bird species, making it a paradise for bird watchers.',
                'duration_days' => 1, 'duration_nights' => 0, 'start_location' => 'Arusha', 'end_location' => 'Arusha',
                'tour_type' => 'Private', 'max_group_size' => 6, 'min_age' => 5, 'price' => 285, 'starting_price' => 285,
                'rating' => 4.8, 'fitness_level' => 'low', 'difficulty_level' => 'Easy',
                'image_url' => '/storage/gallery/69382883869fd_1765288067.jpg',
                'gallery_images' => ['/storage/gallery/69382883869fd_1765288067.jpg', '/storage/gallery/tarangire_elephants_1765288068.jpg'],
                'highlights' => ['Witness the largest elephant herds in Tanzania', 'Explore ancient baobab tree landscapes', 'Over 550 bird species'],
                'inclusions' => ['Park entry fees', 'Professional guide', '4x4 safari vehicle', 'Picnic lunch', 'All transportation'],
                'exclusions' => ['International flights', 'Visa fees', 'Travel insurance', 'Tips', 'Alcoholic beverages'],
                'terms_conditions' => 'Booking requires full payment. Cancellation: 48+ hours: 80% refund.',
                'cancellation_policy' => 'Free cancellation up to 48 hours before departure (minus 5% processing fee).',
                'important_notes' => 'Early morning departure (6:00 AM) from Arusha. Return around 6:00 PM.',
                'meta_title' => 'Tarangire National Park Day Trip - Elephant Safari | Lau Paradise Adventures',
                'meta_description' => 'Full-day Tarangire National Park safari. Witness massive elephant herds and diverse wildlife.',
                'meta_keywords' => 'Tarangire day trip, Tarangire safari, elephant safari Tanzania',
                'availability_status' => 'Available', 'is_featured' => true, 'status' => 'active', 'publish_status' => 'published',
                'itineraries' => [
                    [
                        'day_number' => 1, 'sort_order' => 1,
                        'title' => 'Early Morning Departure & Park Entry',
                        'short_summary' => 'Depart from Arusha and enter Tarangire National Park',
                        'description' => 'Your day begins with an early morning pickup from your hotel in Arusha at 6:00 AM. After a brief introduction with your professional guide, you\'ll embark on a scenic 2-hour drive to Tarangire National Park. Along the way, you\'ll pass through local villages and witness the beautiful Tanzanian countryside. Upon arrival at the park gate, your guide will complete the entry formalities while you enjoy refreshments. The excitement builds as you enter the park and begin your wildlife adventure.',
                        'location' => 'Arusha to Tarangire National Park',
                        'meals_included' => ['Breakfast'],
                        'activities' => [
                            ['name' => 'Hotel Pickup', 'icon' => 'map-pin'],
                            ['name' => 'Scenic Drive', 'icon' => 'car'],
                            ['name' => 'Park Entry', 'icon' => 'ticket']
                        ],
                        'vehicle_type' => '4x4 Safari Vehicle',
                        'day_notes' => 'Bring camera, binoculars, and sunscreen. Early morning is best for wildlife viewing.'
                    ],
                    [
                        'day_number' => 1, 'sort_order' => 2,
                        'title' => 'Morning Game Drive - Elephant Herds & Baobabs',
                        'short_summary' => 'Game drive through Tarangire searching for elephants and wildlife',
                        'description' => 'The morning game drive takes you through Tarangire\'s diverse ecosystems. Your guide will navigate to areas known for large elephant herds - you may see groups of 50-100 elephants! The iconic baobab trees create stunning photo opportunities. Keep your eyes peeled for lions, leopards, cheetahs, giraffes, zebras, wildebeest, and various antelope species. The park\'s river valleys are particularly rich in wildlife during the dry season.',
                        'location' => 'Tarangire National Park',
                        'meals_included' => [],
                        'activities' => [
                            ['name' => 'Game Drive', 'icon' => 'binoculars'],
                            ['name' => 'Wildlife Viewing', 'icon' => 'camera'],
                            ['name' => 'Photography', 'icon' => 'image']
                        ],
                        'vehicle_type' => '4x4 Safari Vehicle with Pop-up Roof',
                        'day_notes' => 'Morning light is perfect for photography. Elephants are most active in the morning.'
                    ],
                    [
                        'day_number' => 1, 'sort_order' => 3,
                        'title' => 'Picnic Lunch in the Park',
                        'short_summary' => 'Enjoy a delicious picnic lunch in a scenic location',
                        'description' => 'Around midday, your guide will find a scenic spot within the park for a picnic lunch. Enjoy a delicious meal surrounded by nature, with the sounds of the African bush as your soundtrack. This is a perfect time to relax, take photos of the landscape, and discuss the wildlife you\'ve seen. Your guide will share interesting facts about the park\'s ecosystem and answer any questions.',
                        'location' => 'Tarangire National Park',
                        'meals_included' => ['Lunch'],
                        'activities' => [
                            ['name' => 'Picnic Lunch', 'icon' => 'utensils'],
                            ['name' => 'Nature Observation', 'icon' => 'eye']
                        ],
                        'day_notes' => 'Lunch is served in a safe, designated picnic area with restroom facilities.'
                    ],
                    [
                        'day_number' => 1, 'sort_order' => 4,
                        'title' => 'Afternoon Game Drive - Bird Watching & More Wildlife',
                        'short_summary' => 'Continue exploring the park with focus on bird watching',
                        'description' => 'The afternoon game drive continues your exploration of Tarangire. With over 550 bird species, this is a paradise for bird watchers. Look for colorful species like the yellow-collared lovebird, ashy starling, and various birds of prey. Your guide will also search for predators that may be more active in the afternoon. The diverse landscapes - from swamps to grasslands - offer different wildlife viewing opportunities.',
                        'location' => 'Tarangire National Park',
                        'meals_included' => [],
                        'activities' => [
                            ['name' => 'Game Drive', 'icon' => 'binoculars'],
                            ['name' => 'Bird Watching', 'icon' => 'feather'],
                            ['name' => 'Wildlife Photography', 'icon' => 'camera']
                        ],
                        'vehicle_type' => '4x4 Safari Vehicle',
                        'day_notes' => 'Afternoon temperatures can be warm. Stay hydrated and use sunscreen.'
                    ],
                    [
                        'day_number' => 1, 'sort_order' => 5,
                        'title' => 'Return to Arusha',
                        'short_summary' => 'Depart from park and return to Arusha',
                        'description' => 'As the day comes to an end, you\'ll exit Tarangire National Park around 4:00 PM and begin the journey back to Arusha. The return drive takes approximately 2 hours, giving you time to reflect on the incredible wildlife encounters of the day. You\'ll arrive back at your hotel in Arusha around 6:00 PM, filled with memories of elephants, baobabs, and the wild beauty of Tanzania.',
                        'location' => 'Tarangire to Arusha',
                        'meals_included' => [],
                        'activities' => [
                            ['name' => 'Park Exit', 'icon' => 'arrow-right'],
                            ['name' => 'Return Transfer', 'icon' => 'car'],
                            ['name' => 'Hotel Drop-off', 'icon' => 'map-pin']
                        ],
                        'vehicle_type' => '4x4 Safari Vehicle',
                        'day_notes' => 'Arrival time may vary depending on wildlife sightings and traffic.'
                    ]
                ]
            ],

            // 2. Ngorongoro Crater Day Trip
            [
                'name' => 'Ngorongoro Crater Day Trip - World\'s Largest Caldera',
                'slug' => 'ngorongoro-crater-day-trip',
                'destination_id' => $ngorongoro->id,
                'short_description' => 'Descend into the Ngorongoro Crater, one of Africa\'s Seven Natural Wonders, for an incredible day of wildlife viewing.',
                'description' => 'Experience the breathtaking Ngorongoro Crater, a UNESCO World Heritage Site. This full-day adventure takes you into the crater floor where you\'ll witness an incredible concentration of wildlife, including the Big Five.',
                'long_description' => 'The Ngorongoro Crater is one of the most remarkable natural wonders on Earth. This full-day expedition begins with an early morning drive from Arusha, taking you through the scenic highlands to the crater rim. As you descend into the crater floor, you\'ll be amazed by the sheer beauty and diversity of this unique ecosystem.',
                'duration_days' => 1, 'duration_nights' => 0, 'start_location' => 'Arusha', 'end_location' => 'Arusha',
                'tour_type' => 'Private', 'max_group_size' => 6, 'min_age' => 5, 'price' => 395, 'starting_price' => 395,
                'rating' => 4.9, 'fitness_level' => 'low', 'difficulty_level' => 'Easy',
                'image_url' => '/storage/gallery/ngorongoro_crater_1765288071.jpg',
                'gallery_images' => ['/storage/gallery/ngorongoro_crater_1765288071.jpg', '/storage/gallery/ngorongoro_lions_1765288072.jpg'],
                'highlights' => ['Descend into world\'s largest caldera', 'Witness Africa\'s Natural Wonder', 'Excellent Big Five viewing'],
                'inclusions' => ['Crater entry fees', 'Professional guide', '4x4 vehicle', 'Picnic lunch', 'All transportation'],
                'exclusions' => ['International flights', 'Visa fees', 'Travel insurance', 'Tips', 'Alcoholic beverages'],
                'terms_conditions' => 'Booking requires full payment. Maximum 6 hours allowed in crater per day.',
                'cancellation_policy' => 'Free cancellation up to 48 hours before departure (minus 5% processing fee).',
                'important_notes' => 'Very early departure (5:30 AM) from Arusha. Return around 7:00 PM.',
                'meta_title' => 'Ngorongoro Crater Day Trip - Africa\'s Natural Wonder | Lau Paradise Adventures',
                'meta_description' => 'Full-day Ngorongoro Crater safari. Descend into the world\'s largest caldera and witness Big Five.',
                'meta_keywords' => 'Ngorongoro day trip, Ngorongoro Crater safari, Big Five Tanzania',
                'availability_status' => 'Available', 'is_featured' => true, 'status' => 'active', 'publish_status' => 'published',
                'itineraries' => [
                    [
                        'day_number' => 1, 'sort_order' => 1,
                        'title' => 'Early Morning Departure to Ngorongoro',
                        'short_summary' => 'Depart from Arusha and drive to Ngorongoro Conservation Area',
                        'description' => 'Your adventure begins with a very early morning pickup at 5:30 AM from your hotel in Arusha. After meeting your professional guide, you\'ll embark on a scenic 3-hour drive to the Ngorongoro Conservation Area. The journey takes you through the beautiful highlands, past coffee plantations, and local Maasai villages. You\'ll stop briefly for breakfast and to stretch your legs before continuing to the crater rim.',
                        'location' => 'Arusha to Ngorongoro Conservation Area',
                        'meals_included' => ['Breakfast'],
                        'activities' => [
                            ['name' => 'Early Morning Pickup', 'icon' => 'clock'],
                            ['name' => 'Scenic Highland Drive', 'icon' => 'car'],
                            ['name' => 'Breakfast Stop', 'icon' => 'coffee']
                        ],
                        'vehicle_type' => '4x4 Safari Vehicle',
                        'day_notes' => 'Bring warm clothing as the crater rim can be cold, especially in the morning.'
                    ],
                    [
                        'day_number' => 1, 'sort_order' => 2,
                        'title' => 'Crater Rim Viewpoint & Descent',
                        'short_summary' => 'Enjoy panoramic views from the rim before descending into the crater',
                        'description' => 'Upon reaching the Ngorongoro Crater rim, you\'ll stop at a viewpoint to take in the breathtaking panoramic views of the crater floor below. This is one of the most spectacular sights in Africa - a 260 square kilometer caldera teeming with wildlife. After taking photos and absorbing the view, you\'ll begin the descent into the crater. The drive down is an adventure in itself, with stunning scenery at every turn.',
                        'location' => 'Ngorongoro Crater Rim',
                        'meals_included' => [],
                        'activities' => [
                            ['name' => 'Crater Rim Viewpoint', 'icon' => 'eye'],
                            ['name' => 'Photography', 'icon' => 'camera'],
                            ['name' => 'Crater Descent', 'icon' => 'arrow-down']
                        ],
                        'vehicle_type' => '4x4 Safari Vehicle',
                        'day_notes' => 'The descent takes about 30 minutes. Hold on tight - it\'s a steep but safe road!'
                    ],
                    [
                        'day_number' => 1, 'sort_order' => 3,
                        'title' => 'Morning Game Drive - Big Five Safari',
                        'short_summary' => 'Search for the Big Five and other wildlife on the crater floor',
                        'description' => 'Once on the crater floor, your game drive begins in earnest. The Ngorongoro Crater is home to approximately 25,000 large animals, and you have excellent chances of seeing the Big Five. Look for lions (the crater has one of Africa\'s densest lion populations), elephants, buffalo, and if you\'re lucky, the rare black rhino. The enclosed nature of the crater means animals rarely leave, creating almost guaranteed wildlife viewing.',
                        'location' => 'Ngorongoro Crater Floor',
                        'meals_included' => [],
                        'activities' => [
                            ['name' => 'Big Five Safari', 'icon' => 'binoculars'],
                            ['name' => 'Wildlife Viewing', 'icon' => 'eye'],
                            ['name' => 'Photography', 'icon' => 'camera']
                        ],
                        'vehicle_type' => '4x4 Safari Vehicle with Pop-up Roof',
                        'day_notes' => 'Rhino sightings are rare but possible. Lions are commonly seen throughout the crater.'
                    ],
                    [
                        'day_number' => 1, 'sort_order' => 4,
                        'title' => 'Picnic Lunch at Hippo Pool',
                        'short_summary' => 'Enjoy lunch while observing hippos at the pool',
                        'description' => 'Around midday, your guide will take you to the hippo pool for a picnic lunch. This is a unique experience - you\'ll enjoy your meal while observing hippos in their natural habitat. The pool is also frequented by various bird species. After lunch, you can take a short walk (with your guide) to stretch your legs and get closer views of the hippos.',
                        'location' => 'Hippo Pool, Ngorongoro Crater',
                        'meals_included' => ['Lunch'],
                        'activities' => [
                            ['name' => 'Picnic Lunch', 'icon' => 'utensils'],
                            ['name' => 'Hippo Observation', 'icon' => 'eye'],
                            ['name' => 'Bird Watching', 'icon' => 'feather']
                        ],
                        'day_notes' => 'Stay close to your guide when near the hippo pool. Hippos can be dangerous.'
                    ],
                    [
                        'day_number' => 1, 'sort_order' => 5,
                        'title' => 'Afternoon Game Drive - Lerai Forest & Lake Magadi',
                        'short_summary' => 'Explore different ecosystems within the crater',
                        'description' => 'The afternoon game drive takes you to different areas of the crater, including the Lerai Forest (home to elephants and various bird species) and Lake Magadi, a soda lake that attracts flamingos and other water birds. Your guide will continue searching for wildlife, and you may see hyenas, jackals, wildebeest, zebras, and various antelope species. The diverse landscapes within the crater offer different wildlife viewing opportunities.',
                        'location' => 'Ngorongoro Crater - Various Locations',
                        'meals_included' => [],
                        'activities' => [
                            ['name' => 'Game Drive', 'icon' => 'binoculars'],
                            ['name' => 'Forest Exploration', 'icon' => 'tree'],
                            ['name' => 'Lake Viewing', 'icon' => 'droplet']
                        ],
                        'vehicle_type' => '4x4 Safari Vehicle',
                        'day_notes' => 'Maximum 6 hours allowed in the crater. Your guide will manage time efficiently.'
                    ],
                    [
                        'day_number' => 1, 'sort_order' => 6,
                        'title' => 'Crater Ascent & Return to Arusha',
                        'short_summary' => 'Exit the crater and return to Arusha',
                        'description' => 'As your time in the crater comes to an end (maximum 6 hours allowed), you\'ll begin the ascent back to the crater rim. The drive up offers different perspectives of this incredible landscape. Once at the rim, you\'ll begin the journey back to Arusha, arriving around 7:00 PM. You\'ll be dropped off at your hotel, filled with memories of one of the world\'s most unique wildlife experiences.',
                        'location' => 'Ngorongoro to Arusha',
                        'meals_included' => [],
                        'activities' => [
                            ['name' => 'Crater Ascent', 'icon' => 'arrow-up'],
                            ['name' => 'Return Journey', 'icon' => 'car'],
                            ['name' => 'Hotel Drop-off', 'icon' => 'map-pin']
                        ],
                        'vehicle_type' => '4x4 Safari Vehicle',
                        'day_notes' => 'Return time may vary. Long day but incredibly rewarding experience.'
                    ]
                ]
            ],

            // 3. Lake Manyara Day Trip
            [
                'name' => 'Lake Manyara National Park Day Trip - Tree Climbing Lions',
                'slug' => 'lake-manyara-day-trip',
                'destination_id' => $manyara->id,
                'short_description' => 'Discover Lake Manyara National Park, famous for tree-climbing lions, diverse birdlife, and stunning landscapes in a full-day adventure.',
                'description' => 'Explore the compact but diverse Lake Manyara National Park, known for its unique tree-climbing lions, alkaline lake, and incredible bird diversity. This day trip offers excellent wildlife viewing in a beautiful setting.',
                'long_description' => 'Lake Manyara National Park may be small in size, but it packs a big punch in terms of wildlife and scenery. The park is famous for its tree-climbing lions, a behavior rarely seen elsewhere. The alkaline Lake Manyara attracts thousands of flamingos and other water birds, creating a spectacular sight.',
                'duration_days' => 1, 'duration_nights' => 0, 'start_location' => 'Arusha', 'end_location' => 'Arusha',
                'tour_type' => 'Private', 'max_group_size' => 6, 'min_age' => 5, 'price' => 245, 'starting_price' => 245,
                'rating' => 4.7, 'fitness_level' => 'low', 'difficulty_level' => 'Easy',
                'image_url' => '/storage/gallery/manyara_lake_1765288076.jpg',
                'gallery_images' => ['/storage/gallery/manyara_lake_1765288076.jpg', '/storage/gallery/manyara_lions_1765288077.jpg'],
                'highlights' => ['See tree-climbing lions', 'Flamingo viewing at alkaline lake', 'Over 400 bird species', 'Compact park with diverse wildlife'],
                'inclusions' => ['Park entry fees', 'Professional guide', '4x4 vehicle', 'Picnic lunch', 'All transportation'],
                'exclusions' => ['International flights', 'Visa fees', 'Travel insurance', 'Tips', 'Alcoholic beverages'],
                'terms_conditions' => 'Booking requires full payment. Cancellation: 48+ hours: 80% refund.',
                'cancellation_policy' => 'Free cancellation up to 48 hours before departure.',
                'important_notes' => 'Departure at 7:00 AM from Arusha. Return around 6:00 PM.',
                'meta_title' => 'Lake Manyara Day Trip - Tree Climbing Lions | Lau Paradise Adventures',
                'meta_description' => 'Full-day Lake Manyara National Park safari. See tree-climbing lions and diverse birdlife.',
                'meta_keywords' => 'Lake Manyara day trip, tree climbing lions, Manyara safari',
                'availability_status' => 'Available', 'is_featured' => true, 'status' => 'active', 'publish_status' => 'published',
                'itineraries' => [
                    [
                        'day_number' => 1, 'sort_order' => 1,
                        'title' => 'Departure from Arusha & Park Entry',
                        'short_summary' => 'Drive to Lake Manyara and enter the park',
                        'description' => 'Your day begins with a 7:00 AM pickup from your hotel in Arusha. After meeting your guide, you\'ll drive approximately 2 hours to Lake Manyara National Park. The journey takes you through the Great Rift Valley escarpment with beautiful views. Upon arrival, your guide will complete entry formalities while you prepare for your wildlife adventure.',
                        'location' => 'Arusha to Lake Manyara',
                        'meals_included' => ['Breakfast'],
                        'activities' => [['name' => 'Hotel Pickup', 'icon' => 'map-pin'], ['name' => 'Scenic Drive', 'icon' => 'car']],
                        'vehicle_type' => '4x4 Safari Vehicle'
                    ],
                    [
                        'day_number' => 1, 'sort_order' => 2,
                        'title' => 'Morning Game Drive - Search for Tree Climbing Lions',
                        'short_summary' => 'Game drive looking for the famous tree-climbing lions',
                        'description' => 'The morning game drive focuses on finding Lake Manyara\'s famous tree-climbing lions. Your guide knows the best areas to spot these unique felines resting in acacia trees. You\'ll also see elephants, buffalo, giraffes, zebras, wildebeest, and various antelope species. The park\'s diverse habitats - from groundwater forest to open grasslands - support a wide variety of wildlife.',
                        'location' => 'Lake Manyara National Park',
                        'meals_included' => [],
                        'activities' => [['name' => 'Game Drive', 'icon' => 'binoculars'], ['name' => 'Lion Viewing', 'icon' => 'eye']],
                        'vehicle_type' => '4x4 Safari Vehicle with Pop-up Roof'
                    ],
                    [
                        'day_number' => 1, 'sort_order' => 3,
                        'title' => 'Picnic Lunch & Lake Viewing',
                        'short_summary' => 'Lunch with views of Lake Manyara and flamingos',
                        'description' => 'Enjoy a picnic lunch at a scenic location overlooking Lake Manyara. The alkaline lake attracts thousands of flamingos, creating a pink spectacle. You\'ll also see pelicans, storks, and many other water birds. This is a perfect time for photography and bird watching.',
                        'location' => 'Lake Manyara National Park',
                        'meals_included' => ['Lunch'],
                        'activities' => [['name' => 'Picnic Lunch', 'icon' => 'utensils'], ['name' => 'Flamingo Viewing', 'icon' => 'eye']],
                        'day_notes' => 'The lake view is spectacular, especially when flamingos are present.'
                    ],
                    [
                        'day_number' => 1, 'sort_order' => 4,
                        'title' => 'Afternoon Game Drive - Groundwater Forest & Wildlife',
                        'short_summary' => 'Explore the groundwater forest and continue wildlife viewing',
                        'description' => 'The afternoon game drive takes you through the park\'s groundwater forest, a unique ecosystem fed by underground springs. This area is home to troops of baboons and blue monkeys. You\'ll continue searching for wildlife and may see hippos, elephants, and various bird species. The compact size of the park means you can cover most areas in a day.',
                        'location' => 'Lake Manyara National Park',
                        'meals_included' => [],
                        'activities' => [['name' => 'Forest Exploration', 'icon' => 'tree'], ['name' => 'Wildlife Viewing', 'icon' => 'binoculars']],
                        'vehicle_type' => '4x4 Safari Vehicle'
                    ],
                    [
                        'day_number' => 1, 'sort_order' => 5,
                        'title' => 'Return to Arusha',
                        'short_summary' => 'Exit park and return to Arusha',
                        'description' => 'After a full day of wildlife viewing, you\'ll exit Lake Manyara National Park and begin the return journey to Arusha. You\'ll arrive back at your hotel around 6:00 PM, having experienced one of Tanzania\'s most unique national parks.',
                        'location' => 'Lake Manyara to Arusha',
                        'meals_included' => [],
                        'activities' => [['name' => 'Park Exit', 'icon' => 'arrow-right'], ['name' => 'Return Transfer', 'icon' => 'car']],
                        'vehicle_type' => '4x4 Safari Vehicle'
                    ]
                ]
            ],

            // 4. Arusha National Park Day Trip
            [
                'name' => 'Arusha National Park Day Trip - Mount Meru Views',
                'slug' => 'arusha-national-park-day-trip',
                'destination_id' => $arusha->id,
                'short_description' => 'Explore Arusha National Park, the closest national park to Arusha town, featuring Mount Meru, diverse wildlife, and beautiful landscapes.',
                'description' => 'Discover Arusha National Park, a compact but beautiful park offering excellent wildlife viewing, stunning views of Mount Meru, and diverse ecosystems including montane forest, grasslands, and alkaline lakes.',
                'long_description' => 'Arusha National Park is the closest national park to Arusha town, making it perfect for a day trip. Despite its small size, the park offers incredible diversity - from the snow-capped peak of Mount Meru to the alkaline Momella Lakes. The park is home to giraffes, buffalo, elephants, zebras, and over 400 bird species.',
                'duration_days' => 1, 'duration_nights' => 0, 'start_location' => 'Arusha', 'end_location' => 'Arusha',
                'tour_type' => 'Private', 'max_group_size' => 6, 'min_age' => 5, 'price' => 195, 'starting_price' => 195,
                'rating' => 4.6, 'fitness_level' => 'low', 'difficulty_level' => 'Easy',
                'image_url' => '/storage/gallery/arusha_park_1765288078.jpg',
                'gallery_images' => ['/storage/gallery/arusha_park_1765288078.jpg', '/storage/gallery/mount_meru_1765288079.jpg'],
                'highlights' => ['Mount Meru views', 'Momella Lakes', 'Diverse wildlife', 'Close to Arusha town', 'Montane forest exploration'],
                'inclusions' => ['Park entry fees', 'Professional guide', '4x4 vehicle', 'Picnic lunch', 'All transportation'],
                'exclusions' => ['International flights', 'Visa fees', 'Travel insurance', 'Tips', 'Alcoholic beverages'],
                'terms_conditions' => 'Booking requires full payment.',
                'cancellation_policy' => 'Free cancellation up to 48 hours before departure.',
                'important_notes' => 'Departure at 8:00 AM from Arusha. Return around 5:00 PM.',
                'meta_title' => 'Arusha National Park Day Trip - Mount Meru | Lau Paradise Adventures',
                'meta_description' => 'Full-day Arusha National Park safari. See Mount Meru and diverse wildlife.',
                'meta_keywords' => 'Arusha National Park, Mount Meru, day trip Arusha',
                'availability_status' => 'Available', 'is_featured' => false, 'status' => 'active', 'publish_status' => 'published',
                'itineraries' => [
                    [
                        'day_number' => 1, 'sort_order' => 1,
                        'title' => 'Morning Departure & Park Entry',
                        'short_summary' => 'Short drive to Arusha National Park',
                        'description' => 'Your day begins with an 8:00 AM pickup from your hotel. Arusha National Park is just 30 minutes from town, making it the closest national park. After a brief drive, you\'ll enter the park and begin your adventure.',
                        'location' => 'Arusha to Arusha National Park',
                        'meals_included' => ['Breakfast'],
                        'activities' => [['name' => 'Hotel Pickup', 'icon' => 'map-pin'], ['name' => 'Park Entry', 'icon' => 'ticket']],
                        'vehicle_type' => '4x4 Safari Vehicle'
                    ],
                    [
                        'day_number' => 1, 'sort_order' => 2,
                        'title' => 'Game Drive - Wildlife & Mount Meru Views',
                        'short_summary' => 'Game drive with views of Mount Meru',
                        'description' => 'Your game drive takes you through diverse habitats. The park offers excellent views of Mount Meru, Tanzania\'s second-highest peak. You\'ll see giraffes, buffalo, zebras, wildebeest, and various antelope species. The montane forest is home to colobus monkeys and numerous bird species.',
                        'location' => 'Arusha National Park',
                        'meals_included' => [],
                        'activities' => [['name' => 'Game Drive', 'icon' => 'binoculars'], ['name' => 'Mountain Views', 'icon' => 'mountain']],
                        'vehicle_type' => '4x4 Safari Vehicle'
                    ],
                    [
                        'day_number' => 1, 'sort_order' => 3,
                        'title' => 'Momella Lakes & Picnic Lunch',
                        'short_summary' => 'Visit the alkaline Momella Lakes and enjoy lunch',
                        'description' => 'The Momella Lakes are a series of alkaline lakes that attract water birds including flamingos. You\'ll enjoy a picnic lunch with views of the lakes and Mount Meru in the background. This is a perfect spot for photography.',
                        'location' => 'Momella Lakes, Arusha National Park',
                        'meals_included' => ['Lunch'],
                        'activities' => [['name' => 'Lake Viewing', 'icon' => 'droplet'], ['name' => 'Picnic Lunch', 'icon' => 'utensils']],
                        'day_notes' => 'Beautiful setting for lunch with mountain and lake views.'
                    ],
                    [
                        'day_number' => 1, 'sort_order' => 4,
                        'title' => 'Afternoon Exploration & Return',
                        'short_summary' => 'Continue exploring before returning to Arusha',
                        'description' => 'The afternoon allows for more exploration of the park. You may take a short walk (with your guide) in designated areas, or continue with game drives. The compact size of the park means you can see most highlights in a day. You\'ll exit the park around 4:00 PM and return to Arusha by 5:00 PM.',
                        'location' => 'Arusha National Park to Arusha',
                        'meals_included' => [],
                        'activities' => [['name' => 'Park Exploration', 'icon' => 'compass'], ['name' => 'Return Transfer', 'icon' => 'car']],
                        'vehicle_type' => '4x4 Safari Vehicle'
                    ]
                ]
            ],

            // 5. Materuni Waterfall & Coffee Tour
            [
                'name' => 'Materuni Waterfall & Coffee Farm Day Trip',
                'slug' => 'materuni-waterfall-coffee-tour',
                'destination_id' => $kilimanjaro->id,
                'short_description' => 'Visit the stunning Materuni Waterfall and learn about traditional coffee farming in a Chagga village near Mount Kilimanjaro.',
                'description' => 'Experience authentic Tanzanian culture on this day trip to Materuni village. Hike to a beautiful waterfall, learn about traditional coffee processing, and interact with the local Chagga community.',
                'long_description' => 'Materuni village is located on the slopes of Mount Kilimanjaro, home to the Chagga people. This cultural and nature tour combines a hike to a stunning 80-meter waterfall with a visit to a traditional coffee farm where you\'ll learn about coffee cultivation and processing.',
                'duration_days' => 1, 'duration_nights' => 0, 'start_location' => 'Arusha/Moshi', 'end_location' => 'Arusha/Moshi',
                'tour_type' => 'Private', 'max_group_size' => 8, 'min_age' => 8, 'price' => 85, 'starting_price' => 85,
                'rating' => 4.8, 'fitness_level' => 'moderate', 'difficulty_level' => 'Easy',
                'image_url' => '/storage/gallery/materuni_waterfall_1765288080.jpg',
                'gallery_images' => ['/storage/gallery/materuni_waterfall_1765288080.jpg', '/storage/gallery/coffee_farm_1765288081.jpg'],
                'highlights' => ['80-meter waterfall', 'Traditional coffee tour', 'Chagga culture experience', 'Mountain views', 'Local village visit'],
                'inclusions' => ['Transportation', 'Local guide', 'Coffee tour', 'Traditional lunch', 'Waterfall visit'],
                'exclusions' => ['International flights', 'Visa fees', 'Travel insurance', 'Tips', 'Personal expenses'],
                'terms_conditions' => 'Booking requires full payment.',
                'cancellation_policy' => 'Free cancellation up to 24 hours before departure.',
                'important_notes' => 'Moderate hiking required. Wear comfortable walking shoes. Departure at 8:00 AM.',
                'meta_title' => 'Materuni Waterfall & Coffee Tour - Cultural Experience | Lau Paradise Adventures',
                'meta_description' => 'Day trip to Materuni village. Hike to waterfall and learn about traditional coffee farming.',
                'meta_keywords' => 'Materuni waterfall, coffee tour, Chagga culture, Kilimanjaro day trip',
                'availability_status' => 'Available', 'is_featured' => true, 'status' => 'active', 'publish_status' => 'published',
                'itineraries' => [
                    [
                        'day_number' => 1, 'sort_order' => 1,
                        'title' => 'Departure to Materuni Village',
                        'short_summary' => 'Drive to Materuni village on Mount Kilimanjaro slopes',
                        'description' => 'Your day begins with pickup from your hotel in Arusha or Moshi at 8:00 AM. You\'ll drive approximately 1.5 hours to Materuni village, located on the southern slopes of Mount Kilimanjaro. The drive offers beautiful views of the mountain and surrounding countryside.',
                        'location' => 'Arusha/Moshi to Materuni Village',
                        'meals_included' => ['Breakfast'],
                        'activities' => [['name' => 'Hotel Pickup', 'icon' => 'map-pin'], ['name' => 'Scenic Drive', 'icon' => 'car']],
                        'vehicle_type' => 'Tour Vehicle'
                    ],
                    [
                        'day_number' => 1, 'sort_order' => 2,
                        'title' => 'Hike to Materuni Waterfall',
                        'short_summary' => 'Moderate hike to the 80-meter waterfall',
                        'description' => 'Upon arrival in Materuni, you\'ll meet your local guide and begin a moderate 45-minute hike to Materuni Waterfall. The trail takes you through lush forest and banana plantations. The waterfall is spectacular - an 80-meter cascade into a natural pool. You can swim in the pool (bring swimwear) and enjoy the refreshing water.',
                        'location' => 'Materuni Waterfall',
                        'meals_included' => [],
                        'activities' => [['name' => 'Hiking', 'icon' => 'mountain'], ['name' => 'Waterfall Visit', 'icon' => 'droplet'], ['name' => 'Swimming', 'icon' => 'swimming-pool']],
                        'day_notes' => 'Moderate fitness required. Bring swimwear and towel if you want to swim.'
                    ],
                    [
                        'day_number' => 1, 'sort_order' => 3,
                        'title' => 'Traditional Coffee Farm Tour',
                        'short_summary' => 'Learn about coffee cultivation and processing',
                        'description' => 'After the waterfall, you\'ll visit a local coffee farm. Your guide will explain the entire coffee process from planting to cup. You\'ll see coffee plants, learn about harvesting, and participate in traditional coffee processing including roasting and grinding. You\'ll then enjoy a cup of freshly brewed local coffee.',
                        'location' => 'Materuni Coffee Farm',
                        'meals_included' => [],
                        'activities' => [['name' => 'Coffee Tour', 'icon' => 'coffee'], ['name' => 'Cultural Learning', 'icon' => 'book']],
                        'day_notes' => 'Interactive experience - you can participate in coffee processing.'
                    ],
                    [
                        'day_number' => 1, 'sort_order' => 4,
                        'title' => 'Traditional Chagga Lunch',
                        'short_summary' => 'Enjoy authentic Chagga cuisine',
                        'description' => 'You\'ll be treated to a traditional Chagga lunch prepared by local women. The meal typically includes local dishes such as banana stew, vegetables, and other regional specialties. This is a great opportunity to experience authentic Tanzanian cuisine and interact with the local community.',
                        'location' => 'Materuni Village',
                        'meals_included' => ['Lunch'],
                        'activities' => [['name' => 'Traditional Lunch', 'icon' => 'utensils'], ['name' => 'Cultural Interaction', 'icon' => 'users']],
                        'day_notes' => 'Vegetarian options available. Please inform in advance of dietary restrictions.'
                    ],
                    [
                        'day_number' => 1, 'sort_order' => 5,
                        'title' => 'Village Exploration & Return',
                        'short_summary' => 'Explore the village before returning',
                        'description' => 'After lunch, you\'ll have time to explore Materuni village, interact with locals, and learn about Chagga culture and traditions. You can purchase local crafts and coffee beans as souvenirs. Around 3:00 PM, you\'ll begin the return journey, arriving back at your hotel around 5:00 PM.',
                        'location' => 'Materuni Village to Arusha/Moshi',
                        'meals_included' => [],
                        'activities' => [['name' => 'Village Tour', 'icon' => 'compass'], ['name' => 'Shopping', 'icon' => 'shopping-bag'], ['name' => 'Return Transfer', 'icon' => 'car']],
                        'vehicle_type' => 'Tour Vehicle'
                    ]
                ]
            ],

            // 6. Kikuletwa Hot Springs Day Trip
            [
                'name' => 'Kikuletwa Hot Springs Day Trip - Natural Oasis',
                'slug' => 'kikuletwa-hot-springs-day-trip',
                'destination_id' => $arusha->id,
                'short_description' => 'Relax in the crystal-clear natural hot springs of Kikuletwa, a hidden oasis in the Tanzanian bush.',
                'description' => 'Escape to Kikuletwa Hot Springs, a natural paradise with crystal-clear warm water surrounded by fig trees. Perfect for swimming, relaxing, and enjoying nature.',
                'long_description' => 'Kikuletwa Hot Springs (also known as Chemka Hot Springs) is a natural oasis located about 1.5 hours from Arusha. The springs feature crystal-clear, warm water that bubbles up from underground, creating a perfect swimming spot. The area is surrounded by lush vegetation and fig trees, creating a tropical paradise in the middle of the bush.',
                'duration_days' => 1, 'duration_nights' => 0, 'start_location' => 'Arusha', 'end_location' => 'Arusha',
                'tour_type' => 'Private', 'max_group_size' => 8, 'min_age' => 5, 'price' => 95, 'starting_price' => 95,
                'rating' => 4.7, 'fitness_level' => 'low', 'difficulty_level' => 'Easy',
                'image_url' => '/storage/gallery/kikuletwa_springs_1765288082.jpg',
                'gallery_images' => ['/storage/gallery/kikuletwa_springs_1765288082.jpg'],
                'highlights' => ['Crystal-clear warm water', 'Natural swimming pool', 'Beautiful surroundings', 'Relaxing experience', 'Perfect for families'],
                'inclusions' => ['Transportation', 'Guide', 'Picnic lunch', 'Entrance fees'],
                'exclusions' => ['International flights', 'Visa fees', 'Travel insurance', 'Tips', 'Personal expenses'],
                'terms_conditions' => 'Booking requires full payment.',
                'cancellation_policy' => 'Free cancellation up to 24 hours before departure.',
                'important_notes' => 'Bring swimwear and towel. Departure at 9:00 AM. Return around 5:00 PM.',
                'meta_title' => 'Kikuletwa Hot Springs Day Trip - Natural Oasis | Lau Paradise Adventures',
                'meta_description' => 'Day trip to Kikuletwa Hot Springs. Swim in crystal-clear warm water in a natural paradise.',
                'meta_keywords' => 'Kikuletwa hot springs, Chemka springs, natural springs Tanzania',
                'availability_status' => 'Available', 'is_featured' => false, 'status' => 'active', 'publish_status' => 'published',
                'itineraries' => [
                    [
                        'day_number' => 1, 'sort_order' => 1,
                        'title' => 'Departure to Kikuletwa Hot Springs',
                        'short_summary' => 'Drive to the natural hot springs',
                        'description' => 'Your day begins with a 9:00 AM pickup from your hotel in Arusha. You\'ll drive approximately 1.5 hours through rural Tanzania to reach Kikuletwa Hot Springs. The journey takes you through local villages and offers glimpses of rural life.',
                        'location' => 'Arusha to Kikuletwa',
                        'meals_included' => [],
                        'activities' => [['name' => 'Hotel Pickup', 'icon' => 'map-pin'], ['name' => 'Scenic Drive', 'icon' => 'car']],
                        'vehicle_type' => 'Tour Vehicle'
                    ],
                    [
                        'day_number' => 1, 'sort_order' => 2,
                        'title' => 'Swimming & Relaxation at Hot Springs',
                        'short_summary' => 'Enjoy swimming in the crystal-clear warm water',
                        'description' => 'Upon arrival at Kikuletwa, you\'ll be amazed by the crystal-clear, warm water. The springs create a natural swimming pool surrounded by fig trees and lush vegetation. You can spend several hours swimming, relaxing, and enjoying this natural paradise. The water temperature is perfect for swimming year-round.',
                        'location' => 'Kikuletwa Hot Springs',
                        'meals_included' => [],
                        'activities' => [['name' => 'Swimming', 'icon' => 'swimming-pool'], ['name' => 'Relaxation', 'icon' => 'umbrella']],
                        'day_notes' => 'Bring swimwear, towel, and sunscreen. The water is safe for swimming.'
                    ],
                    [
                        'day_number' => 1, 'sort_order' => 3,
                        'title' => 'Picnic Lunch by the Springs',
                        'short_summary' => 'Enjoy lunch in this beautiful natural setting',
                        'description' => 'A picnic lunch will be served near the hot springs, allowing you to enjoy your meal in this beautiful natural setting. After lunch, you can continue swimming or simply relax and enjoy the peaceful atmosphere.',
                        'location' => 'Kikuletwa Hot Springs',
                        'meals_included' => ['Lunch'],
                        'activities' => [['name' => 'Picnic Lunch', 'icon' => 'utensils'], ['name' => 'Nature Appreciation', 'icon' => 'eye']],
                        'day_notes' => 'Lunch is served in a shaded area near the springs.'
                    ],
                    [
                        'day_number' => 1, 'sort_order' => 4,
                        'title' => 'Return to Arusha',
                        'short_summary' => 'Depart from springs and return to Arusha',
                        'description' => 'After a relaxing day at the hot springs, you\'ll begin the journey back to Arusha around 3:00 PM. You\'ll arrive back at your hotel around 5:00 PM, feeling refreshed and relaxed.',
                        'location' => 'Kikuletwa to Arusha',
                        'meals_included' => [],
                        'activities' => [['name' => 'Return Transfer', 'icon' => 'car'], ['name' => 'Hotel Drop-off', 'icon' => 'map-pin']],
                        'vehicle_type' => 'Tour Vehicle'
                    ]
                ]
            ],

            // Continue with more day trips... (I'll add 14 more to reach 20+)
            // Due to length, I'll create a more compact version for the remaining trips
        ];

        // Add more day trips (7-20) with detailed itineraries
        $additionalTrips = $this->getAdditionalDayTrips($arusha, $tarangire, $ngorongoro, $manyara, $kilimanjaro, $zanzibar, $serengeti);
        
        $allTrips = array_merge($dayTrips, $additionalTrips);
        
        // Debug: Log trip counts
        if (method_exists($this, 'command') && $this->command) {
            $this->command->line("Main trips: " . count($dayTrips) . ", Additional trips: " . count($additionalTrips) . ", Total: " . count($allTrips));
        }
        
        return $allTrips;
    }

    private function getAdditionalDayTrips($arusha, $tarangire, $ngorongoro, $manyara, $kilimanjaro, $zanzibar, $serengeti): array
    {
        $trips = [
            // 7. Mount Kilimanjaro Day Hike
            [
                'name' => 'Mount Kilimanjaro Day Hike - Shira Plateau',
                'slug' => 'kilimanjaro-day-hike-shira',
                'destination_id' => $kilimanjaro->id,
                'short_description' => 'Experience the lower slopes of Mount Kilimanjaro with a day hike to the Shira Plateau, offering stunning views and mountain experience.',
                'description' => 'Hike to the Shira Plateau on Mount Kilimanjaro\'s lower slopes. This day hike offers a taste of Kilimanjaro without the full climb, with beautiful views and mountain scenery.',
                'long_description' => 'Perfect for those who want to experience Mount Kilimanjaro but don\'t have time for a full climb, this day hike takes you to the Shira Plateau at approximately 3,500 meters. You\'ll hike through montane forest and heath zones, experiencing the mountain\'s diverse ecosystems.',
                'duration_days' => 1, 'duration_nights' => 0, 'start_location' => 'Moshi', 'end_location' => 'Moshi',
                'tour_type' => 'Private', 'max_group_size' => 6, 'min_age' => 12, 'price' => 145, 'starting_price' => 145,
                'rating' => 4.8, 'fitness_level' => 'moderate', 'difficulty_level' => 'Medium',
                'image_url' => '/storage/gallery/kilimanjaro_hike_1765288083.jpg',
                'gallery_images' => ['/storage/gallery/kilimanjaro_hike_1765288083.jpg'],
                'highlights' => ['Shira Plateau hike', 'Mountain views', 'Diverse ecosystems', 'Kilimanjaro experience'],
                'inclusions' => ['Transportation', 'Mountain guide', 'Park fees', 'Packed lunch', 'All equipment'],
                'exclusions' => ['International flights', 'Visa fees', 'Travel insurance', 'Tips'],
                'terms_conditions' => 'Good fitness level required. Altitude may affect some people.',
                'cancellation_policy' => 'Free cancellation up to 48 hours before departure.',
                'important_notes' => 'Moderate to good fitness required. Departure at 6:00 AM from Moshi.',
                'meta_title' => 'Mount Kilimanjaro Day Hike - Shira Plateau | Lau Paradise Adventures',
                'meta_description' => 'Day hike to Shira Plateau on Mount Kilimanjaro. Experience the mountain without full climb.',
                'meta_keywords' => 'Kilimanjaro day hike, Shira Plateau, mountain hiking Tanzania',
                'availability_status' => 'Available', 'is_featured' => true, 'status' => 'active', 'publish_status' => 'published',
                'itineraries' => [
                    ['day_number' => 1, 'sort_order' => 1, 'title' => 'Early Departure to Kilimanjaro', 'short_summary' => 'Drive to Kilimanjaro National Park', 'description' => 'Early morning pickup at 6:00 AM from Moshi. Drive to Londorossi Gate and begin the hike.', 'location' => 'Moshi to Kilimanjaro', 'meals_included' => ['Breakfast'], 'activities' => [['name' => 'Park Entry', 'icon' => 'ticket']], 'vehicle_type' => '4x4 Vehicle'],
                    ['day_number' => 1, 'sort_order' => 2, 'title' => 'Hike to Shira Plateau', 'short_summary' => 'Moderate hike through forest and heath zones', 'description' => 'Hike approximately 4-5 hours to Shira Plateau (3,500m). Experience diverse ecosystems and stunning mountain views.', 'location' => 'Shira Plateau', 'meals_included' => ['Lunch'], 'activities' => [['name' => 'Hiking', 'icon' => 'mountain'], ['name' => 'Mountain Views', 'icon' => 'eye']], 'day_notes' => 'Moderate fitness required. Altitude may cause mild symptoms.'],
                    ['day_number' => 1, 'sort_order' => 3, 'title' => 'Return Descent', 'short_summary' => 'Hike back down and return to Moshi', 'description' => 'After enjoying the views, descend back to the gate. Return to Moshi around 6:00 PM.', 'location' => 'Kilimanjaro to Moshi', 'meals_included' => [], 'activities' => [['name' => 'Descent', 'icon' => 'arrow-down'], ['name' => 'Return Transfer', 'icon' => 'car']], 'vehicle_type' => '4x4 Vehicle']
                ]
            ],

            // 8. Mto wa Mbu Cultural Tour
            [
                'name' => 'Mto wa Mbu Cultural Village Tour - Local Life Experience',
                'slug' => 'mto-wa-mbu-cultural-tour',
                'destination_id' => $manyara->id,
                'short_description' => 'Experience authentic Tanzanian village life in Mto wa Mbu, a vibrant multi-ethnic village near Lake Manyara.',
                'description' => 'Visit Mto wa Mbu village, home to over 120 ethnic groups. Experience local culture, traditional crafts, banana plantations, and authentic Tanzanian life.',
                'long_description' => 'Mto wa Mbu is a unique village where over 120 different ethnic groups live together harmoniously. This cultural tour takes you into the heart of the village to experience daily life, traditional crafts, local markets, and banana plantations.',
                'duration_days' => 1, 'duration_nights' => 0, 'start_location' => 'Arusha', 'end_location' => 'Arusha',
                'tour_type' => 'Private', 'max_group_size' => 8, 'min_age' => 5, 'price' => 75, 'starting_price' => 75,
                'rating' => 4.6, 'fitness_level' => 'low', 'difficulty_level' => 'Easy',
                'image_url' => '/storage/gallery/mto_wa_mbu_1765288084.jpg',
                'gallery_images' => ['/storage/gallery/mto_wa_mbu_1765288084.jpg'],
                'highlights' => ['Multi-ethnic village', 'Local market visit', 'Banana plantation tour', 'Traditional crafts', 'Authentic culture'],
                'inclusions' => ['Transportation', 'Local guide', 'Village tour', 'Traditional lunch', 'Craft demonstrations'],
                'exclusions' => ['International flights', 'Visa fees', 'Travel insurance', 'Tips', 'Personal expenses'],
                'terms_conditions' => 'Booking requires full payment.',
                'cancellation_policy' => 'Free cancellation up to 24 hours before departure.',
                'important_notes' => 'Respectful dress recommended. Departure at 8:00 AM.',
                'meta_title' => 'Mto wa Mbu Cultural Village Tour | Lau Paradise Adventures',
                'meta_description' => 'Experience authentic Tanzanian village life in Mto wa Mbu. Cultural tour with local interactions.',
                'meta_keywords' => 'Mto wa Mbu, cultural tour, village tour Tanzania',
                'availability_status' => 'Available', 'is_featured' => false, 'status' => 'active', 'publish_status' => 'published',
                'itineraries' => [
                    ['day_number' => 1, 'sort_order' => 1, 'title' => 'Departure to Mto wa Mbu', 'short_summary' => 'Drive to the village', 'description' => 'Pickup at 8:00 AM. Drive 1.5 hours to Mto wa Mbu village.', 'location' => 'Arusha to Mto wa Mbu', 'meals_included' => ['Breakfast'], 'activities' => [['name' => 'Village Arrival', 'icon' => 'map-pin']], 'vehicle_type' => 'Tour Vehicle'],
                    ['day_number' => 1, 'sort_order' => 2, 'title' => 'Village Walking Tour', 'short_summary' => 'Explore the village with local guide', 'description' => 'Walk through the village with a local guide. Visit local market, see traditional houses, and learn about the diverse ethnic groups.', 'location' => 'Mto wa Mbu Village', 'meals_included' => [], 'activities' => [['name' => 'Walking Tour', 'icon' => 'walking'], ['name' => 'Market Visit', 'icon' => 'shopping-bag']], 'day_notes' => 'Comfortable walking shoes recommended.'],
                    ['day_number' => 1, 'sort_order' => 3, 'title' => 'Banana Plantation & Traditional Lunch', 'short_summary' => 'Tour banana plantations and enjoy lunch', 'description' => 'Visit banana plantations and learn about cultivation. Enjoy traditional lunch prepared by local women.', 'location' => 'Mto wa Mbu', 'meals_included' => ['Lunch'], 'activities' => [['name' => 'Plantation Tour', 'icon' => 'tree'], ['name' => 'Traditional Lunch', 'icon' => 'utensils']]],
                    ['day_number' => 1, 'sort_order' => 4, 'title' => 'Craft Demonstrations & Return', 'short_summary' => 'See traditional crafts before returning', 'description' => 'Watch local artisans create traditional crafts. Purchase souvenirs. Return to Arusha around 4:00 PM.', 'location' => 'Mto wa Mbu to Arusha', 'meals_included' => [], 'activities' => [['name' => 'Craft Workshop', 'icon' => 'hammer'], ['name' => 'Return Transfer', 'icon' => 'car']], 'vehicle_type' => 'Tour Vehicle']
                ]
            ],
        ];
        
        // 9. Olduvai Gorge & Maasai Village
        $trips[] = $this->createDayTripData('olduvai-gorge-maasai-village', 'Olduvai Gorge & Maasai Village Day Trip - Cradle of Mankind', $ngorongoro->id, 'Visit Olduvai Gorge, the "Cradle of Mankind", and experience authentic Maasai culture in a traditional village.', 325, 4.7, [
            ['sort' => 1, 'title' => 'Early Departure to Olduvai Gorge', 'desc' => 'Drive to Olduvai Gorge, one of the most important paleoanthropological sites in the world.', 'location' => 'Arusha to Olduvai Gorge', 'meals' => ['Breakfast']],
            ['sort' => 2, 'title' => 'Olduvai Gorge Museum & Site Visit', 'desc' => 'Explore the museum and learn about early human evolution. See the actual excavation sites where early hominid fossils were discovered.', 'location' => 'Olduvai Gorge', 'meals' => []],
            ['sort' => 3, 'title' => 'Maasai Village Visit', 'desc' => 'Visit an authentic Maasai village. Learn about their culture, traditions, and way of life. Participate in traditional dances and see their homes.', 'location' => 'Maasai Village', 'meals' => ['Lunch']],
            ['sort' => 4, 'title' => 'Return to Arusha', 'desc' => 'Return journey to Arusha, arriving around 7:00 PM.', 'location' => 'Olduvai to Arusha', 'meals' => []]
        ]);
        
        // 10. Lake Chala Day Trip
        $trips[] = $this->createDayTripData('lake-chala-day-trip', 'Lake Chala Day Trip - Crater Lake Adventure', $kilimanjaro->id, 'Hike around the stunning Lake Chala, a crater lake on the border of Tanzania and Kenya, with views of Mount Kilimanjaro.', 125, 4.6, [
                ['sort' => 1, 'title' => 'Departure to Lake Chala', 'desc' => 'Drive from Moshi/Arusha to Lake Chala (1.5 hours).', 'location' => 'Moshi/Arusha to Lake Chala', 'meals' => ['Breakfast']],
                ['sort' => 2, 'title' => 'Lake Chala Hike', 'desc' => 'Hike around the crater lake (3-4 hours). Enjoy stunning views of the turquoise water and Mount Kilimanjaro.', 'location' => 'Lake Chala', 'meals' => ['Lunch']],
                ['sort' => 3, 'title' => 'Kayaking (Optional)', 'desc' => 'Optional kayaking on the lake for those interested.', 'location' => 'Lake Chala', 'meals' => []],
                ['sort' => 4, 'title' => 'Return', 'desc' => 'Return to hotel around 5:00 PM.', 'location' => 'Lake Chala to Moshi/Arusha', 'meals' => []]
            ]);
        
        // 11. Arusha Town & Markets Tour
        $trips[] = $this->createDayTripData('arusha-town-markets', 'Arusha Town & Markets Cultural Tour', $arusha->id, 'Explore Arusha town, visit local markets, cultural heritage center, and experience authentic Tanzanian urban life.', 65, 4.5, [
                ['sort' => 1, 'title' => 'Arusha Town Walking Tour', 'desc' => 'Explore Arusha town center with a local guide. Learn about the town\'s history and culture.', 'location' => 'Arusha Town', 'meals' => []],
                ['sort' => 2, 'title' => 'Central Market Visit', 'desc' => 'Visit the bustling central market. See local produce, crafts, and experience daily Tanzanian life.', 'location' => 'Arusha Central Market', 'meals' => []],
                ['sort' => 3, 'title' => 'Cultural Heritage Center', 'desc' => 'Visit the Arusha Cultural Heritage Center. See traditional artifacts, gemstones, and local art.', 'location' => 'Cultural Heritage Center', 'meals' => ['Lunch']],
                ['sort' => 4, 'title' => 'Maasai Market Shopping', 'desc' => 'Visit the Maasai market for authentic crafts and souvenirs. Return to hotel around 4:00 PM.', 'location' => 'Arusha', 'meals' => []]
            ]);
        
        // 12. Ndarakwai Ranch Day Trip
        $trips[] = $this->createDayTripData('ndarakwai-ranch-day-trip', 'Ndarakwai Ranch Day Trip - Private Conservation Area', $arusha->id, 'Explore Ndarakwai Ranch, a private conservation area offering excellent wildlife viewing and beautiful landscapes.', 185, 4.7, [
                ['sort' => 1, 'title' => 'Departure to Ndarakwai', 'desc' => 'Drive to Ndarakwai Ranch (1 hour from Arusha).', 'location' => 'Arusha to Ndarakwai', 'meals' => ['Breakfast']],
                ['sort' => 2, 'title' => 'Morning Game Drive', 'desc' => 'Game drive through the ranch. See elephants, giraffes, zebras, and various antelope species.', 'location' => 'Ndarakwai Ranch', 'meals' => []],
                ['sort' => 3, 'title' => 'Picnic Lunch', 'desc' => 'Picnic lunch in a scenic location within the ranch.', 'location' => 'Ndarakwai Ranch', 'meals' => ['Lunch']],
                ['sort' => 4, 'title' => 'Afternoon Activities', 'desc' => 'Optional walking safari or continued game drive. Return to Arusha around 5:00 PM.', 'location' => 'Ndarakwai to Arusha', 'meals' => []]
            ]);
        
        // 13. Lake Duluti Canoeing
        $trips[] = $this->createDayTripData('lake-duluti-canoeing', 'Lake Duluti Canoeing Day Trip - Peaceful Crater Lake', $arusha->id, 'Enjoy a peaceful day canoeing on Lake Duluti, a beautiful crater lake near Arusha surrounded by lush forest.', 95, 4.6, [
                ['sort' => 1, 'title' => 'Departure to Lake Duluti', 'desc' => 'Short drive to Lake Duluti (30 minutes from Arusha).', 'location' => 'Arusha to Lake Duluti', 'meals' => []],
                ['sort' => 2, 'title' => 'Canoeing on Lake', 'desc' => 'Canoe on the peaceful crater lake. Spot birds and enjoy the serene atmosphere.', 'location' => 'Lake Duluti', 'meals' => []],
                ['sort' => 3, 'title' => 'Lakeside Picnic', 'desc' => 'Picnic lunch by the lake.', 'location' => 'Lake Duluti', 'meals' => ['Lunch']],
                ['sort' => 4, 'title' => 'Forest Walk & Return', 'desc' => 'Optional forest walk around the lake. Return to Arusha around 4:00 PM.', 'location' => 'Lake Duluti to Arusha', 'meals' => []]
            ]);
        
        // 14. Horseback Riding Safari
        $trips[] = $this->createDayTripData('horseback-riding-safari', 'Horseback Riding Safari Day Trip - Unique Wildlife Viewing', $arusha->id, 'Experience wildlife viewing from horseback, a unique and intimate way to see animals in their natural habitat.', 195, 4.8, [
                ['sort' => 1, 'title' => 'Departure to Riding Stables', 'desc' => 'Drive to the riding stables near Arusha.', 'location' => 'Arusha to Stables', 'meals' => ['Breakfast']],
                ['sort' => 2, 'title' => 'Horseback Safari', 'desc' => 'Ride through the bush on horseback. See wildlife including zebras, giraffes, and antelopes from a unique perspective.', 'location' => 'Riding Area', 'meals' => []],
                ['sort' => 3, 'title' => 'Lunch Break', 'desc' => 'Lunch at the stables or picnic in the bush.', 'location' => 'Stables', 'meals' => ['Lunch']],
                ['sort' => 4, 'title' => 'Return Ride & Departure', 'desc' => 'Additional riding or return to stables. Drive back to Arusha around 5:00 PM.', 'location' => 'Stables to Arusha', 'meals' => []]
            ]);
        
        // 15. Ol Doinyo Lengai & Lake Natron
        $trips[] = $this->createDayTripData('ol-doinyo-lengai-lake-natron', 'Ol Doinyo Lengai & Lake Natron Day Trip - Active Volcano & Flamingos', $arusha->id, 'Visit the active volcano Ol Doinyo Lengai and Lake Natron, famous for its flamingo population.', 285, 4.7, [
                ['sort' => 1, 'title' => 'Early Departure', 'desc' => 'Very early departure (4:00 AM) for the long drive to Lake Natron area.', 'location' => 'Arusha to Lake Natron', 'meals' => ['Breakfast']],
                ['sort' => 2, 'title' => 'Ol Doinyo Lengai Viewpoint', 'desc' => 'View the active volcano Ol Doinyo Lengai (Mountain of God). Learn about its unique geology.', 'location' => 'Ol Doinyo Lengai', 'meals' => []],
                ['sort' => 3, 'title' => 'Lake Natron Flamingo Viewing', 'desc' => 'Visit Lake Natron to see thousands of flamingos. The alkaline lake creates a stunning pink spectacle.', 'location' => 'Lake Natron', 'meals' => ['Lunch']],
                ['sort' => 4, 'title' => 'Return Journey', 'desc' => 'Long return drive to Arusha, arriving around 8:00 PM.', 'location' => 'Lake Natron to Arusha', 'meals' => []]
            ]);
        
        // 16. Zanzibar Stone Town Day Tour
        $trips[] = $this->createDayTripData('zanzibar-stone-town-tour', 'Zanzibar Stone Town Day Tour - UNESCO World Heritage', $zanzibar->id, 'Explore Stone Town, Zanzibar\'s historic heart and UNESCO World Heritage Site, with its rich Swahili, Arab, and European influences.', 85, 4.8, [
                ['sort' => 1, 'title' => 'Stone Town Walking Tour', 'desc' => 'Guided walking tour through Stone Town\'s narrow streets. Visit historic buildings and learn about the island\'s history.', 'location' => 'Stone Town', 'meals' => []],
                ['sort' => 2, 'title' => 'Historic Sites', 'desc' => 'Visit the Old Fort, House of Wonders, and former slave market. Learn about Zanzibar\'s complex history.', 'location' => 'Stone Town', 'meals' => []],
                ['sort' => 3, 'title' => 'Local Market & Lunch', 'desc' => 'Explore the Forodhani Gardens and local market. Enjoy Swahili cuisine for lunch.', 'location' => 'Stone Town', 'meals' => ['Lunch']],
                ['sort' => 4, 'title' => 'Spice Shop Visit', 'desc' => 'Visit spice shops and learn about Zanzibar\'s spice trade history. Tour ends around 4:00 PM.', 'location' => 'Stone Town', 'meals' => []]
            ]);
        
        // 17. Zanzibar Spice Plantation Tour
        $trips[] = $this->createDayTripData('zanzibar-spice-plantation', 'Zanzibar Spice Plantation Tour - Spice Island Experience', $zanzibar->id, 'Visit a working spice plantation on the Spice Island. Learn about spice cultivation and enjoy a traditional Swahili lunch.', 75, 4.7, [
                ['sort' => 1, 'title' => 'Departure to Spice Plantation', 'desc' => 'Drive to a spice plantation in the countryside.', 'location' => 'Zanzibar to Plantation', 'meals' => []],
                ['sort' => 2, 'title' => 'Spice Tour', 'desc' => 'Guided tour of the plantation. See and smell various spices including cloves, vanilla, cinnamon, and nutmeg.', 'location' => 'Spice Plantation', 'meals' => []],
                ['sort' => 3, 'title' => 'Traditional Lunch & Cooking Demo', 'desc' => 'Enjoy traditional Swahili lunch and watch a cooking demonstration using local spices.', 'location' => 'Spice Plantation', 'meals' => ['Lunch']],
                ['sort' => 4, 'title' => 'Spice Shopping & Return', 'desc' => 'Purchase spices and local products. Return to hotel around 3:00 PM.', 'location' => 'Plantation to Hotel', 'meals' => []]
            ]);
        
        // 18. Zanzibar Jozani Forest
        $trips[] = $this->createDayTripData('zanzibar-jozani-forest', 'Zanzibar Jozani Forest Day Trip - Red Colobus Monkeys', $zanzibar->id, 'Visit Jozani Forest, home to the endemic Zanzibar red colobus monkeys, and explore the mangrove boardwalk.', 95, 4.6, [
                ['sort' => 1, 'title' => 'Drive to Jozani Forest', 'desc' => 'Drive to Jozani Chwaka Bay National Park.', 'location' => 'Zanzibar to Jozani', 'meals' => []],
                ['sort' => 2, 'title' => 'Red Colobus Monkey Viewing', 'desc' => 'Walk through the forest with a guide to see the endemic Zanzibar red colobus monkeys in their natural habitat.', 'location' => 'Jozani Forest', 'meals' => []],
                ['sort' => 3, 'title' => 'Mangrove Boardwalk', 'desc' => 'Walk the mangrove boardwalk to see the unique mangrove ecosystem and birdlife.', 'location' => 'Jozani Mangroves', 'meals' => ['Lunch']],
                ['sort' => 4, 'title' => 'Return', 'desc' => 'Return to hotel around 3:00 PM.', 'location' => 'Jozani to Hotel', 'meals' => []]
            ]);
        
        // 19. Zanzibar Dolphin Tour
        $trips[] = $this->createDayTripData('zanzibar-dolphin-tour', 'Zanzibar Dolphin Watching & Snorkeling Day Trip', $zanzibar->id, 'Swim with dolphins in their natural habitat and enjoy snorkeling in the crystal-clear waters of the Indian Ocean.', 125, 4.8, [
                ['sort' => 1, 'title' => 'Early Departure to Kizimkazi', 'desc' => 'Early morning drive to Kizimkazi village on the southern coast.', 'location' => 'Zanzibar to Kizimkazi', 'meals' => ['Breakfast']],
                ['sort' => 2, 'title' => 'Dolphin Watching & Swimming', 'desc' => 'Boat trip to find dolphins. Opportunity to swim with dolphins in their natural environment.', 'location' => 'Indian Ocean, Kizimkazi', 'meals' => []],
                ['sort' => 3, 'title' => 'Snorkeling', 'desc' => 'Snorkeling in coral reefs. See colorful fish and marine life.', 'location' => 'Coral Reefs', 'meals' => ['Lunch']],
                ['sort' => 4, 'title' => 'Return', 'desc' => 'Return to hotel around 2:00 PM.', 'location' => 'Kizimkazi to Hotel', 'meals' => []]
            ]);
        
            // 20. Zanzibar Dhow Sunset Cruise
            $trips[] = $this->createDayTripData('zanzibar-dhow-sunset-cruise', 'Zanzibar Dhow Sunset Cruise - Traditional Sailing', $zanzibar->id, 'Experience a traditional dhow sailing trip at sunset, complete with snacks and drinks, along Zanzibar\'s beautiful coastline.', 85, 4.7, [
                ['sort' => 1, 'title' => 'Dhow Boarding', 'desc' => 'Board a traditional dhow sailboat in the late afternoon.', 'location' => 'Zanzibar Coast', 'meals' => []],
                ['sort' => 2, 'title' => 'Sailing & Snorkeling', 'desc' => 'Sail along the coast. Optional snorkeling stop to see marine life.', 'location' => 'Indian Ocean', 'meals' => []],
                ['sort' => 3, 'title' => 'Sunset Viewing', 'desc' => 'Watch the spectacular sunset over the Indian Ocean. Enjoy snacks and drinks on board.', 'location' => 'Indian Ocean', 'meals' => []],
                ['sort' => 4, 'title' => 'Return to Shore', 'desc' => 'Return to shore around 7:00 PM after sunset.', 'location' => 'Zanzibar Coast', 'meals' => []]
            ]);
        
        // Add 20 more day trips (21-40)
        $moreTrips = $this->getMoreDayTrips($arusha, $tarangire, $ngorongoro, $manyara, $kilimanjaro, $zanzibar, $serengeti);
        $trips = array_merge($trips, $moreTrips);
        
        return $trips;
    }

    private function createDayTripData($slug, $name, $destinationId, $shortDesc, $price, $rating, $itineraries): array
    {
        $baseData = [
            'name' => $name,
            'slug' => $slug,
            'destination_id' => $destinationId,
            'short_description' => $shortDesc,
            'description' => $shortDesc,
            'long_description' => $shortDesc . ' This day trip offers an authentic Tanzanian experience.',
            'duration_days' => 1,
            'duration_nights' => 0,
            'start_location' => 'Arusha',
            'end_location' => 'Arusha',
            'tour_type' => 'Private',
            'max_group_size' => 6,
            'min_age' => 5,
            'price' => $price,
            'starting_price' => $price,
            'rating' => $rating,
            'fitness_level' => 'low',
            'difficulty_level' => 'Easy',
            'image_url' => '/storage/gallery/' . $slug . '_1765288' . rand(100, 999) . '.jpg',
            'gallery_images' => ['/storage/gallery/' . $slug . '_1765288' . rand(100, 999) . '.jpg'],
            'highlights' => ['Authentic experience', 'Professional guide', 'Small group'],
            'inclusions' => ['Transportation', 'Guide', 'Lunch', 'All activities'],
            'exclusions' => ['International flights', 'Visa fees', 'Travel insurance', 'Tips'],
            'terms_conditions' => 'Booking requires full payment.',
            'cancellation_policy' => 'Free cancellation up to 48 hours before departure.',
            'important_notes' => 'Departure times vary. Please confirm with operator.',
            'meta_title' => $name . ' | Lau Paradise Adventures',
            'meta_description' => $shortDesc,
            'meta_keywords' => strtolower(str_replace(' ', ', ', $name)),
            'availability_status' => 'Available',
            'is_featured' => false,
            'status' => 'active',
            'publish_status' => 'published',
        ];

        $baseData['itineraries'] = array_map(function($it) {
            return [
                'day_number' => 1,
                'sort_order' => $it['sort'],
                'title' => $it['title'],
                'short_summary' => $it['title'],
                'description' => $it['desc'],
                'location' => $it['location'],
                'meals_included' => $it['meals'] ?? [],
                'activities' => [['name' => 'Activity', 'icon' => 'compass']],
                'vehicle_type' => 'Tour Vehicle',
            ];
        }, $itineraries);

        return $baseData;
    }

    private function getMoreDayTrips($arusha, $tarangire, $ngorongoro, $manyara, $kilimanjaro, $zanzibar, $serengeti): array
    {
        $trips = [];
        
        // 21. Serengeti Day Trip (from Arusha - long day)
        $trips[] = $this->createDayTripData('serengeti-day-trip-arusha', 'Serengeti National Park Day Trip from Arusha', $serengeti->id, 'Experience the world-famous Serengeti National Park in a full-day adventure. Witness the endless plains, diverse wildlife, and possibly the Great Migration.', 425, 4.9, [
            ['sort' => 1, 'title' => 'Very Early Departure', 'desc' => 'Extremely early departure (4:00 AM) from Arusha for the long drive to Serengeti (5-6 hours).', 'location' => 'Arusha to Serengeti', 'meals' => ['Breakfast']],
            ['sort' => 2, 'title' => 'Serengeti Game Drive', 'desc' => 'Full day game drive in Serengeti. Search for the Big Five, witness the endless plains, and experience one of Africa\'s greatest wildlife spectacles.', 'location' => 'Serengeti National Park', 'meals' => []],
            ['sort' => 3, 'title' => 'Picnic Lunch in the Park', 'desc' => 'Picnic lunch in a scenic location within Serengeti, surrounded by wildlife and stunning landscapes.', 'location' => 'Serengeti National Park', 'meals' => ['Lunch']],
            ['sort' => 4, 'title' => 'Afternoon Game Drive & Return', 'desc' => 'Continue game drive in the afternoon. Exit park around 4:00 PM and begin long return journey to Arusha, arriving around 10:00 PM.', 'location' => 'Serengeti to Arusha', 'meals' => []]
        ]);
        
        // 22. Tarangire & Lake Manyara Combined
        $trips[] = $this->createDayTripData('tarangire-manyara-combined', 'Tarangire & Lake Manyara Combined Day Trip', $tarangire->id, 'Visit both Tarangire National Park and Lake Manyara National Park in one action-packed day, maximizing your wildlife viewing.', 345, 4.8, [
            ['sort' => 1, 'title' => 'Early Departure & Tarangire Entry', 'desc' => 'Early morning departure (5:30 AM) to Tarangire National Park. Enter park and begin morning game drive.', 'location' => 'Arusha to Tarangire', 'meals' => ['Breakfast']],
            ['sort' => 2, 'title' => 'Tarangire Morning Game Drive', 'desc' => 'Game drive in Tarangire focusing on elephant herds and baobab trees. Excellent wildlife viewing.', 'location' => 'Tarangire National Park', 'meals' => []],
            ['sort' => 3, 'title' => 'Transfer to Lake Manyara & Lunch', 'desc' => 'Exit Tarangire and drive to Lake Manyara (1 hour). Picnic lunch near Lake Manyara.', 'location' => 'Tarangire to Lake Manyara', 'meals' => ['Lunch']],
            ['sort' => 4, 'title' => 'Lake Manyara Afternoon Game Drive', 'desc' => 'Afternoon game drive in Lake Manyara. Search for tree-climbing lions and enjoy bird watching. Exit park and return to Arusha around 7:00 PM.', 'location' => 'Lake Manyara to Arusha', 'meals' => []]
        ]);
        
        // 23. Arusha National Park Canoeing & Walking Safari
        $trips[] = $this->createDayTripData('arusha-park-canoeing-walking', 'Arusha National Park Canoeing & Walking Safari', $arusha->id, 'Combine canoeing on Momella Lakes with a walking safari in Arusha National Park for a unique and active day trip.', 165, 4.7, [
            ['sort' => 1, 'title' => 'Departure to Arusha National Park', 'desc' => 'Short drive to Arusha National Park (30 minutes). Park entry and briefing.', 'location' => 'Arusha to Arusha NP', 'meals' => ['Breakfast']],
            ['sort' => 2, 'title' => 'Canoeing on Momella Lakes', 'desc' => 'Canoe on the beautiful Momella Lakes. Spot hippos, water birds, and enjoy the peaceful atmosphere with Mount Meru views.', 'location' => 'Momella Lakes', 'meals' => []],
            ['sort' => 3, 'title' => 'Walking Safari with Armed Ranger', 'desc' => 'Guided walking safari through the park with an armed ranger. Get close to nature and learn about the ecosystem.', 'location' => 'Arusha National Park', 'meals' => ['Lunch']],
            ['sort' => 4, 'title' => 'Game Drive & Return', 'desc' => 'Short game drive before exiting the park. Return to Arusha around 5:00 PM.', 'location' => 'Arusha NP to Arusha', 'meals' => []]
        ]);
        
        // 24. Mount Meru Day Hike
        $trips[] = $this->createDayTripData('mount-meru-day-hike', 'Mount Meru Day Hike - Tanzania\'s Second Highest', $arusha->id, 'Hike the lower slopes of Mount Meru, Tanzania\'s second-highest peak, offering stunning views and diverse ecosystems.', 125, 4.6, [
            ['sort' => 1, 'title' => 'Early Departure to Mount Meru', 'desc' => 'Early morning departure (6:00 AM) to Mount Meru. Drive to the starting point and meet your guide.', 'location' => 'Arusha to Mount Meru', 'meals' => ['Breakfast']],
            ['sort' => 2, 'title' => 'Hike Through Montane Forest', 'desc' => 'Hike through beautiful montane forest. See diverse birdlife and possibly colobus monkeys. Reach viewpoints with stunning vistas.', 'location' => 'Mount Meru', 'meals' => []],
            ['sort' => 3, 'title' => 'Summit Viewpoint & Picnic', 'desc' => 'Reach a viewpoint offering panoramic views of Arusha, Kilimanjaro (on clear days), and surrounding landscapes. Picnic lunch.', 'location' => 'Mount Meru Viewpoint', 'meals' => ['Lunch']],
            ['sort' => 4, 'title' => 'Descent & Return', 'desc' => 'Hike back down through the forest. Return to Arusha around 5:00 PM.', 'location' => 'Mount Meru to Arusha', 'meals' => []]
        ]);
        
        // 25. Usambara Mountains Day Trip
        $trips[] = $this->createDayTripData('usambara-mountains-day-trip', 'Usambara Mountains Day Trip - Lush Highlands', $arusha->id, 'Explore the beautiful Usambara Mountains, known for their lush forests, stunning views, and rich biodiversity.', 185, 4.6, [
            ['sort' => 1, 'title' => 'Early Departure to Usambara', 'desc' => 'Very early departure (5:00 AM) for the drive to Usambara Mountains (4-5 hours).', 'location' => 'Arusha to Usambara', 'meals' => ['Breakfast']],
            ['sort' => 2, 'title' => 'Mountain Hiking & Village Visit', 'desc' => 'Hike through lush mountain forests. Visit local villages and learn about the local culture. Enjoy stunning mountain views.', 'location' => 'Usambara Mountains', 'meals' => []],
            ['sort' => 3, 'title' => 'Traditional Lunch', 'desc' => 'Traditional lunch in a local village or mountain lodge.', 'location' => 'Usambara Mountains', 'meals' => ['Lunch']],
            ['sort' => 4, 'title' => 'Return Journey', 'desc' => 'Begin return journey to Arusha, arriving around 8:00 PM.', 'location' => 'Usambara to Arusha', 'meals' => []]
        ]);
        
        // 26. Chemka Hot Springs (Alternative Name)
        $trips[] = $this->createDayTripData('chemka-hot-springs', 'Chemka Hot Springs Day Trip - Natural Paradise', $arusha->id, 'Relax in the natural hot springs of Chemka (Kikuletwa), a hidden oasis with crystal-clear warm water perfect for swimming.', 95, 4.7, [
            ['sort' => 1, 'title' => 'Departure to Chemka', 'desc' => 'Drive to Chemka Hot Springs (1.5 hours from Arusha).', 'location' => 'Arusha to Chemka', 'meals' => []],
            ['sort' => 2, 'title' => 'Swimming & Relaxation', 'desc' => 'Spend several hours swimming and relaxing in the crystal-clear warm springs. The natural pool is surrounded by fig trees creating a tropical paradise.', 'location' => 'Chemka Hot Springs', 'meals' => []],
            ['sort' => 3, 'title' => 'Picnic Lunch', 'desc' => 'Picnic lunch by the springs.', 'location' => 'Chemka Hot Springs', 'meals' => ['Lunch']],
            ['sort' => 4, 'title' => 'Return to Arusha', 'desc' => 'Return to Arusha around 5:00 PM.', 'location' => 'Chemka to Arusha', 'meals' => []]
        ]);
        
        // 27. Lake Eyasi & Hadzabe Tribe
        $trips[] = $this->createDayTripData('lake-eyasi-hadzabe', 'Lake Eyasi & Hadzabe Tribe Day Trip - Hunter Gatherers', $arusha->id, 'Visit Lake Eyasi and meet the Hadzabe people, one of the last remaining hunter-gatherer tribes in Africa.', 285, 4.8, [
            ['sort' => 1, 'title' => 'Early Departure to Lake Eyasi', 'desc' => 'Very early departure (4:30 AM) for the drive to Lake Eyasi (4-5 hours).', 'location' => 'Arusha to Lake Eyasi', 'meals' => ['Breakfast']],
            ['sort' => 2, 'title' => 'Hadzabe Tribe Visit', 'desc' => 'Meet the Hadzabe people and learn about their traditional way of life. Watch hunting demonstrations and traditional activities.', 'location' => 'Lake Eyasi Area', 'meals' => []],
            ['sort' => 3, 'title' => 'Lake Eyasi Exploration & Lunch', 'desc' => 'Explore Lake Eyasi area. Picnic lunch with views of the alkaline lake.', 'location' => 'Lake Eyasi', 'meals' => ['Lunch']],
            ['sort' => 4, 'title' => 'Return to Arusha', 'desc' => 'Return journey to Arusha, arriving around 8:00 PM.', 'location' => 'Lake Eyasi to Arusha', 'meals' => []]
        ]);
        
        // 28. Bagamoyo Historical Town Tour
        $trips[] = $this->createDayTripData('bagamoyo-historical-tour', 'Bagamoyo Historical Town Day Trip - Slave Trade History', $arusha->id, 'Explore Bagamoyo, a historic coastal town with significant Swahili, Arab, and European influences, including slave trade history.', 165, 4.6, [
            ['sort' => 1, 'title' => 'Departure to Bagamoyo', 'desc' => 'Early morning departure (6:00 AM) for the drive to Bagamoyo (2-3 hours from Dar es Salaam/Arusha).', 'location' => 'Arusha/Dar to Bagamoyo', 'meals' => ['Breakfast']],
            ['sort' => 2, 'title' => 'Historical Sites Tour', 'desc' => 'Visit historical sites including the Old Fort, German Boma, Catholic Mission, and former slave market. Learn about the town\'s complex history.', 'location' => 'Bagamoyo Town', 'meals' => []],
            ['sort' => 3, 'title' => 'Beach & Lunch', 'desc' => 'Visit the beach and enjoy lunch at a local restaurant.', 'location' => 'Bagamoyo Beach', 'meals' => ['Lunch']],
            ['sort' => 4, 'title' => 'Return Journey', 'desc' => 'Return to starting point around 6:00 PM.', 'location' => 'Bagamoyo to Arusha/Dar', 'meals' => []]
        ]);
        
        // 29. Zanzibar Prison Island Tour
        $trips[] = $this->createDayTripData('zanzibar-prison-island', 'Zanzibar Prison Island Day Trip - Giant Tortoises', $zanzibar->id, 'Visit Prison Island (Changuu Island) to see giant Aldabra tortoises and enjoy snorkeling in crystal-clear waters.', 95, 4.7, [
            ['sort' => 1, 'title' => 'Boat Departure to Prison Island', 'desc' => 'Board a boat from Stone Town for the 30-minute journey to Prison Island.', 'location' => 'Stone Town to Prison Island', 'meals' => []],
            ['sort' => 2, 'title' => 'Giant Tortoise Viewing', 'desc' => 'Meet the giant Aldabra tortoises on the island. These ancient creatures are over 100 years old and very friendly.', 'location' => 'Prison Island', 'meals' => []],
            ['sort' => 3, 'title' => 'Snorkeling & Beach Time', 'desc' => 'Snorkel in the clear waters around the island. Relax on the beach and enjoy the tropical paradise.', 'location' => 'Prison Island', 'meals' => ['Lunch']],
            ['sort' => 4, 'title' => 'Return to Stone Town', 'desc' => 'Boat ride back to Stone Town. Tour ends around 3:00 PM.', 'location' => 'Prison Island to Stone Town', 'meals' => []]
        ]);
        
        // 30. Zanzibar Mnemba Atoll Snorkeling
        $trips[] = $this->createDayTripData('zanzibar-mnemba-snorkeling', 'Zanzibar Mnemba Atoll Snorkeling Day Trip', $zanzibar->id, 'Experience world-class snorkeling at Mnemba Atoll, one of Zanzibar\'s best snorkeling spots with vibrant coral reefs and marine life.', 135, 4.9, [
            ['sort' => 1, 'title' => 'Boat Departure to Mnemba', 'desc' => 'Early morning boat departure to Mnemba Atoll (1-1.5 hours from northeast coast).', 'location' => 'Zanzibar to Mnemba Atoll', 'meals' => ['Breakfast']],
            ['sort' => 2, 'title' => 'First Snorkeling Session', 'desc' => 'Snorkel in the pristine coral reefs. See colorful fish, coral formations, and possibly dolphins or sea turtles.', 'location' => 'Mnemba Atoll', 'meals' => []],
            ['sort' => 3, 'title' => 'Lunch & Second Snorkeling', 'desc' => 'Lunch on the boat. Second snorkeling session at a different spot with different marine life.', 'location' => 'Mnemba Atoll', 'meals' => ['Lunch']],
            ['sort' => 4, 'title' => 'Return to Coast', 'desc' => 'Return boat journey. Arrive back around 4:00 PM.', 'location' => 'Mnemba to Zanzibar', 'meals' => []]
        ]);
        
        // 31. Zanzibar Blue Safari (Swimming with Dolphins)
        $trips[] = $this->createDayTripData('zanzibar-blue-safari', 'Zanzibar Blue Safari - Swimming with Dolphins', $zanzibar->id, 'Swim with wild dolphins in their natural habitat off the coast of Zanzibar, followed by snorkeling and beach time.', 145, 4.8, [
            ['sort' => 1, 'title' => 'Early Departure to Kizimkazi', 'desc' => 'Early morning drive to Kizimkazi village (1 hour from Stone Town).', 'location' => 'Zanzibar to Kizimkazi', 'meals' => ['Breakfast']],
            ['sort' => 2, 'title' => 'Dolphin Swimming Experience', 'desc' => 'Boat trip to find dolphins. Opportunity to swim with wild dolphins in the open ocean. An unforgettable experience!', 'location' => 'Indian Ocean, Kizimkazi', 'meals' => []],
            ['sort' => 3, 'title' => 'Snorkeling & Beach', 'desc' => 'Snorkeling session and beach time. Lunch served on the beach.', 'location' => 'Kizimkazi Beach', 'meals' => ['Lunch']],
            ['sort' => 4, 'title' => 'Return', 'desc' => 'Return to hotel around 3:00 PM.', 'location' => 'Kizimkazi to Hotel', 'meals' => []]
        ]);
        
        // 32. Zanzibar Nungwi Beach Day Trip
        $trips[] = $this->createDayTripData('zanzibar-nungwi-beach', 'Zanzibar Nungwi Beach Day Trip - Pristine Beaches', $zanzibar->id, 'Spend a day at Nungwi Beach, one of Zanzibar\'s most beautiful beaches with white sand and turquoise waters.', 75, 4.7, [
            ['sort' => 1, 'title' => 'Drive to Nungwi', 'desc' => 'Drive to Nungwi Beach on the northern tip of Zanzibar (1.5 hours from Stone Town).', 'location' => 'Zanzibar to Nungwi', 'meals' => []],
            ['sort' => 2, 'title' => 'Beach Activities', 'desc' => 'Enjoy the pristine beach. Optional activities include swimming, sunbathing, or water sports.', 'location' => 'Nungwi Beach', 'meals' => []],
            ['sort' => 3, 'title' => 'Lunch at Beach Restaurant', 'desc' => 'Lunch at a beachfront restaurant with ocean views.', 'location' => 'Nungwi Beach', 'meals' => ['Lunch']],
            ['sort' => 4, 'title' => 'Dhow Boat Building Visit', 'desc' => 'Visit local dhow boat builders. See traditional boat construction. Return around 5:00 PM.', 'location' => 'Nungwi to Hotel', 'meals' => []]
        ]);
        
        // 33. Zanzibar Forodhani Night Market Tour
        $trips[] = $this->createDayTripData('zanzibar-forodhani-market', 'Zanzibar Forodhani Night Market Food Tour', $zanzibar->id, 'Experience Zanzibar\'s famous Forodhani Night Market with its incredible street food, local culture, and vibrant atmosphere.', 65, 4.8, [
            ['sort' => 1, 'title' => 'Evening Pickup', 'desc' => 'Evening pickup from hotel (around 5:00 PM) for the short drive to Forodhani Gardens.', 'location' => 'Hotel to Forodhani', 'meals' => []],
            ['sort' => 2, 'title' => 'Market Exploration & Food Tasting', 'desc' => 'Explore the night market with a local guide. Sample various street foods including Zanzibar pizza, seafood, and local delicacies.', 'location' => 'Forodhani Night Market', 'meals' => []],
            ['sort' => 3, 'title' => 'Cultural Experience', 'desc' => 'Learn about local food culture and traditions. Interact with vendors and experience authentic Zanzibar street food scene.', 'location' => 'Forodhani Gardens', 'meals' => []],
            ['sort' => 4, 'title' => 'Return to Hotel', 'desc' => 'Return to hotel around 8:00 PM after experiencing the market.', 'location' => 'Forodhani to Hotel', 'meals' => []]
        ]);
        
        // 34. Kilimanjaro Coffee Plantation Tour
        $trips[] = $this->createDayTripData('kilimanjaro-coffee-plantation', 'Kilimanjaro Coffee Plantation Day Tour', $kilimanjaro->id, 'Visit a coffee plantation on the slopes of Mount Kilimanjaro. Learn about coffee cultivation and enjoy fresh coffee.', 95, 4.7, [
            ['sort' => 1, 'title' => 'Departure to Coffee Plantation', 'desc' => 'Drive to a coffee plantation on Mount Kilimanjaro\'s slopes (1 hour from Moshi).', 'location' => 'Moshi to Plantation', 'meals' => ['Breakfast']],
            ['sort' => 2, 'title' => 'Coffee Farm Tour', 'desc' => 'Guided tour of the coffee plantation. Learn about cultivation, harvesting, and processing. See coffee plants at different stages.', 'location' => 'Coffee Plantation', 'meals' => []],
            ['sort' => 3, 'title' => 'Coffee Processing & Tasting', 'desc' => 'Participate in traditional coffee processing. Roast and grind coffee beans. Enjoy fresh coffee tasting session.', 'location' => 'Coffee Plantation', 'meals' => ['Lunch']],
            ['sort' => 4, 'title' => 'Return to Moshi', 'desc' => 'Return to Moshi around 4:00 PM. Option to purchase coffee beans.', 'location' => 'Plantation to Moshi', 'meals' => []]
        ]);
        
        // 35. Arusha Cultural Heritage Center & Museum
        $trips[] = $this->createDayTripData('arusha-cultural-heritage', 'Arusha Cultural Heritage Center Day Tour', $arusha->id, 'Explore Arusha\'s Cultural Heritage Center with its extensive collection of gemstones, artifacts, and local art.', 55, 4.5, [
            ['sort' => 1, 'title' => 'Heritage Center Visit', 'desc' => 'Guided tour of the Cultural Heritage Center. See gemstones, traditional artifacts, and contemporary art.', 'location' => 'Arusha Cultural Heritage', 'meals' => []],
            ['sort' => 2, 'title' => 'Gemstone Collection', 'desc' => 'Explore the impressive gemstone collection including tanzanite, the rare gemstone found only in Tanzania.', 'location' => 'Cultural Heritage Center', 'meals' => []],
            ['sort' => 3, 'title' => 'Art Gallery & Lunch', 'desc' => 'Visit the art gallery showcasing local and international artists. Lunch at the center\'s restaurant.', 'location' => 'Cultural Heritage Center', 'meals' => ['Lunch']],
            ['sort' => 4, 'title' => 'Shopping & Return', 'desc' => 'Time for shopping at the center\'s shops. Return to hotel around 3:00 PM.', 'location' => 'Arusha', 'meals' => []]
        ]);
        
        // 36. Tarangire Bird Watching Specialized Tour
        $trips[] = $this->createDayTripData('tarangire-bird-watching', 'Tarangire Bird Watching Specialized Day Trip', $tarangire->id, 'Specialized bird watching tour in Tarangire National Park, home to over 550 bird species including many endemic and rare species.', 295, 4.8, [
            ['sort' => 1, 'title' => 'Early Departure for Bird Watching', 'desc' => 'Very early departure (5:30 AM) to catch birds during their most active morning hours.', 'location' => 'Arusha to Tarangire', 'meals' => ['Breakfast']],
            ['sort' => 2, 'title' => 'Morning Bird Watching Session', 'desc' => 'Guided bird watching with expert ornithologist guide. Focus on identifying and photographing various bird species.', 'location' => 'Tarangire National Park', 'meals' => []],
            ['sort' => 3, 'title' => 'Lunch & Afternoon Session', 'desc' => 'Picnic lunch. Continue bird watching in different habitats - river areas, swamps, and grasslands.', 'location' => 'Tarangire National Park', 'meals' => ['Lunch']],
            ['sort' => 4, 'title' => 'Return to Arusha', 'desc' => 'Return to Arusha around 7:00 PM with bird checklist and photos.', 'location' => 'Tarangire to Arusha', 'meals' => []]
        ]);
        
        // 37. Ngorongoro Crater Rim Walk
        $trips[] = $this->createDayTripData('ngorongoro-rim-walk', 'Ngorongoro Crater Rim Walking Tour', $ngorongoro->id, 'Walk along the Ngorongoro Crater rim with stunning views into the crater and surrounding highlands. No descent into crater.', 195, 4.7, [
            ['sort' => 1, 'title' => 'Drive to Ngorongoro Rim', 'desc' => 'Drive to Ngorongoro Conservation Area and reach the crater rim (3 hours from Arusha).', 'location' => 'Arusha to Ngorongoro Rim', 'meals' => ['Breakfast']],
            ['sort' => 2, 'title' => 'Crater Rim Walk', 'desc' => 'Guided walk along the crater rim. Enjoy spectacular views into the crater and surrounding landscapes. Learn about the geology and ecosystem.', 'location' => 'Ngorongoro Crater Rim', 'meals' => []],
            ['sort' => 3, 'title' => 'Picnic Lunch with Views', 'desc' => 'Picnic lunch at a viewpoint with panoramic crater views.', 'location' => 'Ngorongoro Rim', 'meals' => ['Lunch']],
            ['sort' => 4, 'title' => 'Return Journey', 'desc' => 'Return to Arusha around 6:00 PM.', 'location' => 'Ngorongoro to Arusha', 'meals' => []]
        ]);
        
        // 38. Lake Manyara Night Game Drive
        $trips[] = $this->createDayTripData('lake-manyara-night-drive', 'Lake Manyara Night Game Drive - Nocturnal Wildlife', $manyara->id, 'Experience Lake Manyara National Park after dark with a specialized night game drive to see nocturnal animals.', 285, 4.8, [
            ['sort' => 1, 'title' => 'Afternoon Departure', 'desc' => 'Afternoon departure (2:00 PM) to Lake Manyara. Enter park and begin afternoon game drive.', 'location' => 'Arusha to Lake Manyara', 'meals' => []],
            ['sort' => 2, 'title' => 'Afternoon Game Drive', 'desc' => 'Afternoon game drive to see diurnal animals before sunset.', 'location' => 'Lake Manyara National Park', 'meals' => []],
            ['sort' => 3, 'title' => 'Dinner & Night Drive Preparation', 'desc' => 'Dinner at a lodge or picnic. Prepare for night drive with spotlights.', 'location' => 'Lake Manyara', 'meals' => ['Dinner']],
            ['sort' => 4, 'title' => 'Night Game Drive', 'desc' => 'Night game drive with spotlights. See nocturnal animals including civets, genets, porcupines, and possibly lions hunting. Return to Arusha around 11:00 PM.', 'location' => 'Lake Manyara to Arusha', 'meals' => []]
        ]);
        
        // 39. Zanzibar Safari Blue (Full Day Sailing)
        $trips[] = $this->createDayTripData('zanzibar-safari-blue', 'Zanzibar Safari Blue - Full Day Sailing Adventure', $zanzibar->id, 'Full day sailing adventure on a traditional dhow. Visit sandbanks, snorkel, and enjoy a seafood BBQ on a private island.', 125, 4.9, [
            ['sort' => 1, 'title' => 'Dhow Departure', 'desc' => 'Board traditional dhow in the morning for full day sailing adventure.', 'location' => 'Zanzibar Coast', 'meals' => ['Breakfast']],
            ['sort' => 2, 'title' => 'Sandbank Visit & Snorkeling', 'desc' => 'Visit a beautiful sandbank. Snorkel in crystal-clear waters with excellent visibility and marine life.', 'location' => 'Indian Ocean', 'meals' => []],
            ['sort' => 3, 'title' => 'Private Island & Seafood BBQ', 'desc' => 'Sail to a private island. Enjoy fresh seafood BBQ lunch prepared on the beach.', 'location' => 'Private Island', 'meals' => ['Lunch']],
            ['sort' => 4, 'title' => 'Return Sailing', 'desc' => 'Sail back to the coast, arriving around 5:00 PM.', 'location' => 'Island to Zanzibar', 'meals' => []]
        ]);
        
        // 40. Zanzibar Stone Town & Spice Tour Combined
        $trips[] = $this->createDayTripData('zanzibar-stone-town-spice', 'Zanzibar Stone Town & Spice Tour Combined', $zanzibar->id, 'Combine Stone Town historical tour with a spice plantation visit for a comprehensive Zanzibar cultural experience.', 95, 4.8, [
            ['sort' => 1, 'title' => 'Stone Town Morning Tour', 'desc' => 'Morning walking tour of Stone Town. Visit historic sites, markets, and learn about the island\'s history.', 'location' => 'Stone Town', 'meals' => []],
            ['sort' => 2, 'title' => 'Transfer to Spice Plantation', 'desc' => 'Drive to spice plantation in the countryside.', 'location' => 'Stone Town to Plantation', 'meals' => []],
            ['sort' => 3, 'title' => 'Spice Tour & Traditional Lunch', 'desc' => 'Guided spice plantation tour. Learn about spices and their uses. Enjoy traditional Swahili lunch.', 'location' => 'Spice Plantation', 'meals' => ['Lunch']],
            ['sort' => 4, 'title' => 'Return to Stone Town', 'desc' => 'Return to Stone Town. Tour ends around 4:00 PM.', 'location' => 'Plantation to Stone Town', 'meals' => []]
        ]);
        
        return $trips;
    }
}
