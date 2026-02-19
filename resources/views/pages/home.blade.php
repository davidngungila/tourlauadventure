@extends('layouts.app')

@section('title', 'Lau Paradise Adventures - Premium Tanzania Tours & Safaris')
@section('description', 'Discover the beauty of Tanzania with Lau Paradise Adventures. Expert-guided safaris, Kilimanjaro climbs, Zanzibar beaches, and authentic cultural experiences.')

@section('content')

<br><br>
<!-- Hero Section with Slider -->
<section class="hero-section" id="home">
        <div class="hero-slider" id="heroSlider">
            <div class="slider-wrapper">
            @if(isset($heroSlides) && $heroSlides->count() > 0)
                @foreach($heroSlides as $index => $slide)
                @php
                    $slideNumber = $index + 1;
                    $isActive = $index === 0 ? 'active' : '';
                    $animationClass = 'content-animation-' . $slideNumber;
                    $badgeAnimationClass = 'badge-animation-' . $slideNumber;
                    $titleAnimationClass = 'title-animation-' . $slideNumber;
                    $subtitleAnimationClass = 'subtitle-animation-' . $slideNumber;
                    $actionsAnimationClass = 'actions-animation-' . $slideNumber;
                    $overlayClass = 'overlay-type-' . $slideNumber;
                    $slideTypeClass = 'slide-type-' . $slideNumber;
                @endphp
                <div class="slider-slide {{ $isActive }} {{ $slideTypeClass }}" style="background-image: url('{{ $slide['image_url'] }}');">
                    <div class="hero-overlay {{ $overlayClass }}"></div>
                    <div class="hero-content {{ $animationClass }}">
                        @if($slide['badge_text'])
                        <span class="hero-badge {{ $badgeAnimationClass }}">
                            @if($slide['badge_icon'])
                            <i class="{{ $slide['badge_icon'] }}"></i>
                            @endif
                            {{ $slide['badge_text'] }}
                        </span>
                        @endif
                        <h1 class="hero-title {{ $titleAnimationClass }}">{{ $slide['title'] }}</h1>
                        @if($slide['subtitle'])
                        <p class="hero-subtitle {{ $subtitleAnimationClass }}">{{ $slide['subtitle'] }}</p>
                        @endif
                        <div class="hero-actions {{ $actionsAnimationClass }}">
                            @if($slide['primary_button_text'] && $slide['primary_button_link'])
                            <a href="{{ $slide['primary_button_link'] }}" class="btn-hero-primary">
                                @if($slide['primary_button_icon'])
                                <i class="{{ $slide['primary_button_icon'] }}"></i>
                                @endif
                                {{ $slide['primary_button_text'] }}
                            </a>
                            @endif
                            @if($slide['secondary_button_text'] && $slide['secondary_button_link'])
                            <a href="{{ $slide['secondary_button_link'] }}" class="btn-hero-secondary">
                                @if($slide['secondary_button_icon'])
                                <i class="{{ $slide['secondary_button_icon'] }}"></i>
                                @endif
                                {{ $slide['secondary_button_text'] }}
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <!-- Fallback slide if no slides in database -->
                <div class="slider-slide active slide-type-1" style="background-image: url('{{ asset('images/safari_home-1.jpg') }}');">
                    <div class="hero-overlay overlay-type-1"></div>
                    <div class="hero-content content-animation-1">
                        <span class="hero-badge badge-animation-1"><i class="fas fa-star"></i> Best Seller</span>
                        <h1 class="hero-title title-animation-1">Tanzania Wildlife Safaris</h1>
                        <p class="hero-subtitle subtitle-animation-1">Witness the Big Five in Serengeti, Ngorongoro, and Tarangire. Experience authentic Tanzania safaris with expert local guides.</p>
                        <div class="hero-actions actions-animation-1">
                            <a href="{{ route('tours.index') }}" class="btn-hero-primary">
                                <i class="fas fa-compass"></i> Explore Safaris
                            </a>
                            <a href="{{ route('booking') }}" class="btn-hero-secondary">
                                <i class="fas fa-calendar-check"></i> Book Now
                            </a>
                        </div>
                    </div>
                </div>
            @endif

        <!-- Single Pagination Container (outside slides) -->
        <div class="slider-pagination-container">
            <div class="slider-pagination" id="sliderPagination"></div>
        </div>

        <button class="slider-btn slider-prev" id="sliderPrev">
                <i class="fas fa-chevron-left"></i>
            </button>
        <button class="slider-btn slider-next" id="sliderNext">
            <i class="fas fa-chevron-right"></i>
        </button>
        </div>

    <!-- Simple Search Modal -->
    <div class="search-modal-overlay" id="searchModal">
        <div class="search-modal-content">
            <button class="search-modal-close" id="closeSearchModal">
                <i class="fas fa-times"></i>
            </button>
            <div class="search-modal-body">
                <div class="search-modal-icon">
                    <i class="fas fa-compass"></i>
                </div>
                <h2>Find Your Perfect Tanzania Adventure</h2>
                <p class="search-modal-message">Search functionality would connect to a backend in a real application. For now, please call us at <a href="tel:+255789456123" class="phone-link">+255 789 456 123</a> to book your Tanzania adventure!</p>
                <div class="search-modal-actions">
                    <a href="tel:+255789456123" class="btn-search-call">
                        <i class="fas fa-phone"></i> Call Us Now
                    </a>
                    <a href="{{ route('contact') }}" class="btn-search-contact">
                        <i class="fas fa-envelope"></i> Contact Us
                    </a>
                </div>
                <div class="search-modal-quick-links">
                    <p>Quick Links:</p>
                    <div class="quick-links-grid">
                        <a href="{{ route('tours.index') }}"><i class="fas fa-route"></i> All Tours</a>
                        <a href="{{ route('safaris') }}"><i class="fas fa-camera"></i> Safaris</a>
                        <a href="{{ route('destinations.index') }}"><i class="fas fa-map-marker-alt"></i> Destinations</a>
                        <a href="{{ route('booking') }}"><i class="fas fa-calendar-check"></i> Book Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>

<!-- Trust Section -->
    <section class="trust-section">
        <div class="container">
            <div class="trust-grid">
            <div class="trust-item" data-aos="fade-up">
                    <div class="trust-icon"><i class="fas fa-users"></i></div>
                    <div class="trust-content">
                        <h3 class="trust-number" data-count="50000">0</h3>
                        <p class="trust-label">Happy Travelers</p>
                    </div>
                </div>
            <div class="trust-item" data-aos="fade-up" data-aos-delay="100">
                    <div class="trust-icon"><i class="fas fa-globe"></i></div>
                    <div class="trust-content">
                        <h3 class="trust-number" data-count="50">0</h3>
                        <p class="trust-label">Tanzania Destinations</p>
                    </div>
                </div>
            <div class="trust-item" data-aos="fade-up" data-aos-delay="200">
                    <div class="trust-icon"><i class="fas fa-star"></i></div>
                    <div class="trust-content">
                        <h3 class="trust-number" data-count="4.9">0</h3>
                        <p class="trust-label">Average Rating</p>
                    </div>
                </div>
            <div class="trust-item" data-aos="fade-up" data-aos-delay="300">
                    <div class="trust-icon"><i class="fas fa-award"></i></div>
                    <div class="trust-content">
                        <h3 class="trust-number" data-count="15">0</h3>
                        <p class="trust-label">Years in Tanzania</p>
                    </div>
                </div>
            </div>
            <div class="certifications" data-aos="fade-up">
                <p class="cert-label">Licensed & Certified By:</p>
                <div class="cert-badges">
                    <span class="cert-badge"><i class="fas fa-certificate"></i> TATO Member</span>
                    <span class="cert-badge"><i class="fas fa-shield-alt"></i> Fully Insured</span>
                    <span class="cert-badge"><i class="fas fa-check-circle"></i> TALA Certified</span>
                    <span class="cert-badge"><i class="fas fa-leaf"></i> Eco-Friendly</span>
                </div>
            </div>
        </div>
    </section>

<!-- Featured Tours -->
<section class="featured-tours-section" id="tours">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
            <span class="section-badge"><i class="fas fa-star"></i> Tanzania's Best Tours</span>
                    <h2 class="section-title">Our Most Popular Tanzania Adventures</h2>
                <p class="section-subtitle">Carefully curated Tanzania tours that our travelers love. From Serengeti safaris to Kilimanjaro climbs, your perfect adventure awaits.</p>
            </div>
            <div class="tours-grid">
            @if(isset($featuredTours) && count($featuredTours) > 0)
                @foreach($featuredTours as $index => $tour)
                <div class="tour-card" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="tour-card-image">
                        <img src="{{ $tour['image'] }}" alt="{{ $tour['name'] }}">
                        <div class="tour-card-badge">{{ $tour['destination'] }}</div>
                    </div>
                    <div class="tour-card-content">
                        <div class="tour-card-meta">
                            <span class="tour-duration"><i class="fas fa-clock"></i> {{ $tour['duration_days'] }} Days</span>
                            <span class="tour-rating"><i class="fas fa-star"></i> {{ $tour['rating'] }}</span>
                        </div>
                        <h3 class="tour-card-title">{{ $tour['name'] }}</h3>
                        <p class="tour-card-location"><i class="fas fa-map-marker-alt"></i> {{ $tour['destination'] }}</p>
                        <p class="tour-card-description">{{ $tour['description'] }}</p>
                        <div class="tour-card-features">
                            <span><i class="fas fa-check"></i> Expert Guides</span>
                            <span><i class="fas fa-check"></i> All Meals</span>
                            <span><i class="fas fa-check"></i> Equipment</span>
                        </div>
                        <div class="tour-card-footer">
                            <div class="tour-price">
                                <span class="price-label">From</span>
                                <span class="price-amount">${{ number_format($tour['starting_price']) }}</span>
                                <span class="price-note">per person</span>
                            </div>
                            <a href="{{ route('tours.show', $tour['slug']) }}" class="btn-secondary">View Details</a>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <!-- Fallback tours if database is empty -->
                <div class="tour-card" data-aos="fade-up">
                    <div class="tour-card-image">
                        <img src="{{ asset('images/hero-slider/kilimanjaro-climbing.jpg') }}" alt="Kilimanjaro">
                        <div class="tour-card-badge">Climbing</div>
                    </div>
                    <div class="tour-card-content">
                        <div class="tour-card-meta">
                            <span class="tour-duration"><i class="fas fa-clock"></i> 7 Days</span>
                            <span class="tour-rating"><i class="fas fa-star"></i> 4.9</span>
                        </div>
                        <h3 class="tour-card-title">Kilimanjaro Machame Route</h3>
                        <p class="tour-card-location"><i class="fas fa-map-marker-alt"></i> Mount Kilimanjaro, Tanzania</p>
                        <p class="tour-card-description">Conquer Africa's highest peak via the scenic Machame route. Expert guides and premium equipment included.</p>
                         <div class="tour-card-footer">
                            <div class="tour-price">
                                <span class="price-label">From</span>
                                <span class="price-amount">$2,578</span>
                                <span class="price-note">per person</span>
                            </div>
                            <a href="{{ route('tours.index') }}" class="btn-secondary">View Details</a>
                        </div>
                    </div>
                </div>
            @endif
            </div>
             <div class="section-footer" data-aos="fade-up">
            <a href="{{ route('tours.index') }}" class="btn-primary btn-large">
                <i class="fas fa-compass"></i> View All Tours
                <i class="fas fa-arrow-right"></i>
            </a>
            </div>
        </div>
    </section>

<!-- Popular Destinations -->
<section class="popular-destinations-section">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
            <span class="section-badge"><i class="fas fa-map-marker-alt"></i> Explore</span>
            <h2 class="section-title">Popular Destinations</h2>
            <p class="section-subtitle">Discover Tanzania's most iconic destinations</p>
                        </div>
        <div class="destinations-simple-grid">
            <a href="{{ route('destinations.show', 'serengeti') }}" class="destination-simple-card" data-aos="fade-up">
                <div class="destination-simple-image">
                    <img src="{{ asset('images/Serengetei-NP-2.jpeg') }}" alt="Serengeti">
                    </div>
                <div class="destination-simple-content">
                    <h3>Serengeti</h3>
                    <p>Great Migration</p>
                        </div>
            </a>
            <a href="{{ route('destinations.show', 'kilimanjaro') }}" class="destination-simple-card" data-aos="fade-up" data-aos-delay="100">
                <div class="destination-simple-image">
                    <img src="{{ asset('images/hero-slider/kilimanjaro-climbing.jpg') }}" alt="Kilimanjaro">
                        </div>
                <div class="destination-simple-content">
                    <h3>Kilimanjaro</h3>
                    <p>Africa's Highest Peak</p>
                            </div>
            </a>
            <a href="{{ route('destinations.show', 'zanzibar') }}" class="destination-simple-card" data-aos="fade-up" data-aos-delay="200">
                <div class="destination-simple-image">
                    <img src="{{ asset('images/zanzibar_home.jpg') }}" alt="Zanzibar">
                        </div>
                <div class="destination-simple-content">
                    <h3>Zanzibar</h3>
                    <p>Beach Paradise</p>
                    </div>
                </a>
            <a href="{{ route('destinations.show', 'ngorongoro') }}" class="destination-simple-card" data-aos="fade-up" data-aos-delay="300">
                <div class="destination-simple-image">
                    <img src="{{ asset('images/safari_home-1.jpg') }}" alt="Ngorongoro">
                </div>
                <div class="destination-simple-content">
                    <h3>Ngorongoro</h3>
                    <p>Crater Safari</p>
                    </div>
                </a>
            </div>
             <div class="section-footer" data-aos="fade-up">
            <a href="{{ route('destinations.index') }}" class="btn-secondary">
                <i class="fas fa-compass"></i> View All Destinations
                </a>
            </div>
        </div>
    </section>

<!-- Why Choose Us Simple -->
<section class="why-choose-simple-section">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
            <span class="section-badge">Why Choose Us</span>
                <h2 class="section-title">Your Trusted Tanzania Travel Partner</h2>
            </div>
        <div class="benefits-grid">
            <div class="benefit-item" data-aos="fade-up">
                <div class="benefit-icon"><i class="fas fa-check-circle"></i></div>
                <h3>Tanzania Specialists</h3>
                <p>100% focused on Tanzania with unmatched local expertise</p>
                </div>
            <div class="benefit-item" data-aos="fade-up" data-aos-delay="100">
                <div class="benefit-icon"><i class="fas fa-car"></i></div>
                <h3>Private Safaris</h3>
                <p>Dedicated vehicles and guides for personalized experiences</p>
                </div>
            <div class="benefit-item" data-aos="fade-up" data-aos-delay="200">
                <div class="benefit-icon"><i class="fas fa-shield-alt"></i></div>
                <h3>Fully Insured</h3>
                <p>Licensed, certified, and fully insured for your peace of mind</p>
                </div>
            <div class="benefit-item" data-aos="fade-up" data-aos-delay="300">
                <div class="benefit-icon"><i class="fas fa-heart"></i></div>
                <h3>Personalized Service</h3>
                <p>Tailored itineraries to match your preferences and budget</p>
                </div>
            </div>
        </div>
    </section>
    
<!-- Activities Section -->
<section class="activities-section">
        <div class="container">
             <div class="section-header" data-aos="fade-up">
            <span class="section-badge"><i class="fas fa-mountain"></i> Activities</span>
            <h2 class="section-title">What You Can Experience</h2>
            <p class="section-subtitle">Discover amazing activities and experiences in Tanzania</p>
            </div>
        <div class="activities-grid">
            @forelse($activities ?? [] as $index => $activity)
            <div class="activity-card" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                @if($activity['image_url'])
                <div class="activity-image-wrapper">
                    <img src="{{ $activity['image_url'] }}" alt="{{ $activity['name'] }}" class="activity-image">
                </div>
                @elseif($activity['icon'])
                <div class="activity-icon"><i class="{{ $activity['icon'] }}"></i></div>
                @else
                <div class="activity-icon"><i class="fas fa-star"></i></div>
                @endif
                <h3>{{ $activity['name'] }}</h3>
                @if($activity['description'])
                <p>{{ $activity['description'] }}</p>
                @endif
            </div>
            @empty
            <!-- Fallback to default activities if none in database -->
            <div class="activity-card" data-aos="fade-up">
                <div class="activity-icon"><i class="fas fa-binoculars"></i></div>
                <h3>Wildlife Safari</h3>
                <p>Game drives, Big Five sightings, and incredible wildlife encounters</p>
            </div>
            <div class="activity-card" data-aos="fade-up" data-aos-delay="100">
                <div class="activity-icon"><i class="fas fa-mountain"></i></div>
                <h3>Mountain Climbing</h3>
                <p>Conquer Kilimanjaro and other peaks with expert guides</p>
            </div>
            <div class="activity-card" data-aos="fade-up" data-aos-delay="200">
                <div class="activity-icon"><i class="fas fa-umbrella-beach"></i></div>
                <h3>Beach Holidays</h3>
                <p>Relax on pristine beaches in Zanzibar and coastal areas</p>
            </div>
            <div class="activity-card" data-aos="fade-up" data-aos-delay="300">
                <div class="activity-icon"><i class="fas fa-camera"></i></div>
                <h3>Photography Tours</h3>
                <p>Capture stunning wildlife and landscapes with professional guidance</p>
            </div>
            <div class="activity-card" data-aos="fade-up" data-aos-delay="400">
                <div class="activity-icon"><i class="fas fa-users"></i></div>
                <h3>Cultural Tours</h3>
                <p>Experience local cultures, traditions, and authentic interactions</p>
            </div>
            <div class="activity-card" data-aos="fade-up" data-aos-delay="500">
                <div class="activity-icon"><i class="fas fa-water"></i></div>
                <h3>Water Activities</h3>
                <p>Snorkeling, diving, boat safaris, and water sports</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Best Time to Visit -->
<section class="best-time-section">
    <div class="container">
        <div class="best-time-content" data-aos="fade-up">
            <div class="best-time-text">
                <span class="section-badge"><i class="fas fa-calendar-alt"></i> Travel Guide</span>
                <h2 class="section-title">Best Time to Visit Tanzania</h2>
                <p>Tanzania offers incredible experiences year-round, but timing can enhance your adventure:</p>
                <div class="season-grid">
                    <div class="season-item">
                        <h4><i class="fas fa-sun"></i> Dry Season</h4>
                        <p><strong>June - October</strong></p>
                        <p>Best for wildlife viewing, clear skies, and Great Migration</p>
                    </div>
                    <div class="season-item">
                        <h4><i class="fas fa-leaf"></i> Green Season</h4>
                        <p><strong>November - May</strong></p>
                        <p>Lush landscapes, bird watching, fewer crowds, lower prices</p>
                    </div>
                </div>
                <a href="{{ route('contact') }}" class="btn-secondary">
                    <i class="fas fa-info-circle"></i> Get Travel Advice
                </a>
            </div>
            </div>
        </div>
    </section>

<!-- Photo Gallery -->
<section class="photo-gallery-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge"><i class="fas fa-images"></i> Gallery</span>
            <h2 class="section-title">Tanzania in Pictures</h2>
            <p class="section-subtitle">Glimpse the beauty that awaits you</p>
        </div>
        @if(isset($homepageGallery) && $homepageGallery->count() > 0)
        <div class="gallery-grid" id="homepageGallery">
            @foreach($homepageGallery as $index => $image)
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}" 
                 data-gallery-id="{{ $image['id'] }}"
                 data-image-url="{{ $image['image_url'] }}"
                 data-image-title="{{ $image['title'] }}"
                 data-image-caption="{{ $image['caption'] ?? '' }}">
                <img src="{{ $image['thumbnail_url'] }}" 
                     alt="{{ $image['alt_text'] }}" 
                     loading="lazy"
                     onerror="this.src='{{ asset('images/safari_home-1.jpg') }}'">
                <div class="gallery-overlay">
                    <i class="fas fa-search-plus"></i>
                    @if($image['caption'])
                    <p class="gallery-caption">{{ Str::limit($image['caption'], 50) }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <!-- Fallback gallery if no images in database -->
        <div class="gallery-grid">
            <div class="gallery-item" data-aos="fade-up">
                <img src="{{ asset('images/Serengetei-NP-2.jpeg') }}" alt="Serengeti Wildlife">
                <div class="gallery-overlay">
                    <i class="fas fa-search-plus"></i>
                </div>
            </div>
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="100">
                <img src="{{ asset('images/hero-slider/kilimanjaro-climbing.jpg') }}" alt="Kilimanjaro">
                <div class="gallery-overlay">
                    <i class="fas fa-search-plus"></i>
                </div>
            </div>
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="200">
                <img src="{{ asset('images/zanzibar_home.jpg') }}" alt="Zanzibar Beach">
                <div class="gallery-overlay">
                    <i class="fas fa-search-plus"></i>
                </div>
            </div>
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="300">
                <img src="{{ asset('images/Tarangire-NP-1.jpeg') }}" alt="Tarangire">
                <div class="gallery-overlay">
                    <i class="fas fa-search-plus"></i>
                </div>
            </div>
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="400">
                <img src="{{ asset('images/Mara-River-3-1536x1024.jpg') }}" alt="Mara River">
                <div class="gallery-overlay">
                    <i class="fas fa-search-plus"></i>
                </div>
            </div>
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="500">
                <img src="{{ asset('images/safari_home-1.jpg') }}" alt="Safari">
                <div class="gallery-overlay">
                    <i class="fas fa-search-plus"></i>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Lightbox Modal for Gallery -->
<div class="gallery-lightbox" id="galleryLightbox">
    <div class="lightbox-overlay"></div>
    <div class="lightbox-content">
        <button class="lightbox-close" id="lightboxClose">
            <i class="fas fa-times"></i>
        </button>
        <button class="lightbox-prev" id="lightboxPrev">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="lightbox-next" id="lightboxNext">
            <i class="fas fa-chevron-right"></i>
        </button>
        <div class="lightbox-image-container">
            <img id="lightboxImage" src="" alt="">
            <div class="lightbox-caption">
                <h4 id="lightboxTitle"></h4>
                <p id="lightboxDescription"></p>
            </div>
        </div>
    </div>
</div>

<!-- Travel Tips Section -->
<section class="travel-tips-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge"><i class="fas fa-lightbulb"></i> Travel Tips</span>
            <h2 class="section-title">Essential Travel Information</h2>
                    </div>
        <div class="tips-grid">
            <div class="tip-card" data-aos="fade-up">
                <div class="tip-icon"><i class="fas fa-passport"></i></div>
                <h3>Visa Requirements</h3>
                <p>Most visitors need a visa. Apply online or get on arrival at the airport.</p>
                </div>
            <div class="tip-card" data-aos="fade-up" data-aos-delay="100">
                <div class="tip-icon"><i class="fas fa-syringe"></i></div>
                <h3>Health & Vaccinations</h3>
                <p>Yellow fever vaccination recommended. Consult your doctor before travel.</p>
            </div>
            <div class="tip-card" data-aos="fade-up" data-aos-delay="200">
                <div class="tip-icon"><i class="fas fa-money-bill-wave"></i></div>
                <h3>Currency</h3>
                <p>Tanzanian Shilling (TZS). USD widely accepted. Credit cards in major areas.</p>
            </div>
            <div class="tip-card" data-aos="fade-up" data-aos-delay="300">
                <div class="tip-icon"><i class="fas fa-language"></i></div>
                <h3>Language</h3>
                <p>Swahili and English are official languages. English widely spoken.</p>
            </div>
             </div>
        </div>
    </section>

<!-- Google & TripAdvisor Reviews Carousel -->
<section class="reviews-carousel-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge"><i class="fas fa-star"></i> Reviews</span>
            <h2 class="section-title">What Our Guests Say</h2>
            <p class="section-subtitle">Real reviews from Google and TripAdvisor</p>
        </div>
        <div class="reviews-carousel-wrapper">
            <div class="reviews-carousel-track" id="reviewsCarousel">
                <!-- Google Reviews -->
                <div class="review-card">
                    <div class="review-platform">
                        <i class="fab fa-google"></i>
                        <span>Google</span>
                    </div>
                    <div class="review-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="review-text">"Amazing safari experience! The guides were knowledgeable and we saw the Big Five. Highly recommend Lau Paradise Adventures!"</p>
                    <div class="review-author">
                        <strong>Sarah Johnson</strong>
                        <span>United States</span>
                    </div>
                </div>
                <div class="review-card">
                    <div class="review-platform">
                        <i class="fab fa-google"></i>
                        <span>Google</span>
                    </div>
                    <div class="review-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="review-text">"Best travel company in Tanzania! They organized everything perfectly. The Kilimanjaro climb was unforgettable."</p>
                    <div class="review-author">
                        <strong>Michael Chen</strong>
                        <span>Australia</span>
                    </div>
                </div>
                <div class="review-card">
                    <div class="review-platform">
                        <i class="fab fa-tripadvisor"></i>
                        <span>TripAdvisor</span>
                    </div>
                    <div class="review-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="review-text">"Outstanding service from start to finish. The Serengeti safari exceeded all expectations. Professional team and great value!"</p>
                    <div class="review-author">
                        <strong>Emma Williams</strong>
                        <span>United Kingdom</span>
                    </div>
                </div>
                <div class="review-card">
                    <div class="review-platform">
                        <i class="fab fa-tripadvisor"></i>
                        <span>TripAdvisor</span>
                    </div>
                    <div class="review-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="review-text">"Zanzibar beach holiday was perfect! Great communication, excellent accommodations, and beautiful locations."</p>
                    <div class="review-author">
                        <strong>David Martinez</strong>
                        <span>Spain</span>
                    </div>
                </div>
                <div class="review-card">
                    <div class="review-platform">
                        <i class="fab fa-google"></i>
                        <span>Google</span>
                    </div>
                    <div class="review-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="review-text">"The Great Migration tour was incredible! We witnessed thousands of wildebeest crossing. A once-in-a-lifetime experience!"</p>
                    <div class="review-author">
                        <strong>Lisa Anderson</strong>
                        <span>Canada</span>
                    </div>
                </div>
                <div class="review-card">
                    <div class="review-platform">
                        <i class="fab fa-tripadvisor"></i>
                        <span>TripAdvisor</span>
                    </div>
                    <div class="review-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="review-text">"Professional, reliable, and friendly team. Our custom safari itinerary was perfectly tailored to our interests."</p>
                    <div class="review-author">
                        <strong>James Brown</strong>
                        <span>South Africa</span>
                    </div>
                </div>
                <!-- Duplicate cards for seamless loop -->
                <div class="review-card">
                    <div class="review-platform">
                        <i class="fab fa-google"></i>
                        <span>Google</span>
                    </div>
                    <div class="review-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="review-text">"Amazing safari experience! The guides were knowledgeable and we saw the Big Five. Highly recommend Lau Paradise Adventures!"</p>
                    <div class="review-author">
                        <strong>Sarah Johnson</strong>
                        <span>United States</span>
                    </div>
                </div>
                <div class="review-card">
                    <div class="review-platform">
                        <i class="fab fa-google"></i>
                        <span>Google</span>
                    </div>
                    <div class="review-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="review-text">"Best travel company in Tanzania! They organized everything perfectly. The Kilimanjaro climb was unforgettable."</p>
                    <div class="review-author">
                        <strong>Michael Chen</strong>
                        <span>Australia</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="reviews-platform-badges" data-aos="fade-up">
            <div class="platform-badge">
                <i class="fab fa-google"></i>
                <div class="platform-info">
                    <strong>4.9/5</strong>
                    <span>Google Reviews</span>
                </div>
            </div>
            <div class="platform-badge">
                <i class="fab fa-tripadvisor"></i>
                <div class="platform-info">
                    <strong>4.8/5</strong>
                    <span>TripAdvisor</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Simple CTA Section -->
<section class="simple-cta-section">
        <div class="container">
        <div class="simple-cta-content" data-aos="fade-up">
            <h2 class="simple-cta-title">Ready to Start Your Tanzania Adventure?</h2>
            <p class="simple-cta-text">Get a free, personalized quote for your dream trip</p>
            <div class="simple-cta-buttons">
                <a href="{{ route('booking') }}" class="btn-primary btn-large">
                    <i class="fas fa-calendar-check"></i> Book Your Trip
                </a>
                <a href="{{ route('contact') }}" class="btn-secondary btn-large">
                    <i class="fas fa-envelope"></i> Contact Us
                </a>
                </div>
            </div>
        </div>
    </section>


@push('styles')
    @vite(['resources/css/pages/home.css'])
@endpush

@push('scripts')
    @vite(['resources/js/pages/home.js'])
@endpush
@endsection
