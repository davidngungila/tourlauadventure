@extends('layouts.app')

@section('title', ($tour->meta_title ?? $tour->name) . ' - Lau Paradise Adventures')
@section('description', $tour->meta_description ?? $tour->short_description ?? $tour->description)

@section('content')

<!-- Tour Hero Section -->
<section class="tour-hero-section" style="background-image: url('{{ $tour->image_url ? (str_starts_with($tour->image_url, 'http') ? $tour->image_url : asset($tour->image_url)) : asset('images/safari_home-1.jpg') }}');">
    <div class="tour-hero-overlay"></div>
    <div class="container">
        <div class="tour-hero-content" data-aos="fade-up">
            <nav class="tour-breadcrumb">
                <a href="{{ route('home') }}"><i class="fas fa-home"></i> Home</a>
                <span>/</span>
                <a href="{{ route('tours.index') }}">Tours</a>
                @if($tour->destination)
                <span>/</span>
                <a href="{{ route('destinations.show', $tour->destination->slug) }}">{{ $tour->destination->name }}</a>
                @endif
            </nav>
            @if($tour->is_featured)
            <span class="tour-badge-featured"><i class="fas fa-star"></i> Featured Tour</span>
            @endif
            <h1 class="tour-hero-title">{{ $tour->name }}</h1>
            <p class="tour-hero-subtitle">{{ $tour->short_description ?: substr($tour->description ?? '', 0, 200) }}</p>
            <div class="tour-hero-meta">
                @if($tour->destination)
                <span><i class="fas fa-map-marker-alt"></i> {{ $tour->destination->name }}</span>
                @endif
                <span><i class="fas fa-clock"></i> {{ $tour->duration_days }} Days</span>
                @if($tour->rating)
                <span><i class="fas fa-star"></i> {{ number_format($tour->rating, 1) }} Rating</span>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Quick Stats Bar -->
<section class="tour-stats-bar">
        <div class="container">
        <div class="stats-grid">
            <div class="stat-item" data-aos="fade-up">
                <i class="fas fa-calendar-alt"></i>
                <div>
                    <strong>{{ $tour->duration_days }} Days</strong>
                    <span>{{ $tour->duration_nights ?? $tour->duration_days - 1 }} Nights</span>
                </div>
            </div>
            <div class="stat-item" data-aos="fade-up" data-aos-delay="100">
                <i class="fas fa-users"></i>
                <div>
                    <strong>Max {{ $tour->max_group_size ?? 12 }}</strong>
                    <span>Group Size</span>
                </div>
            </div>
            <div class="stat-item" data-aos="fade-up" data-aos-delay="200">
                <i class="fas fa-signal"></i>
                <div>
                    <strong>{{ ucfirst($tour->difficulty_level ?? 'Moderate') }}</strong>
                    <span>Difficulty</span>
                </div>
            </div>
            @if($tour->fitness_level)
            <div class="stat-item" data-aos="fade-up" data-aos-delay="300">
                <i class="fas fa-running"></i>
                <div>
                    <strong>{{ ucfirst($tour->fitness_level) }}</strong>
                    <span>Fitness Level</span>
                </div>
            </div>
            @endif
            @if($tour->min_age)
            <div class="stat-item" data-aos="fade-up" data-aos-delay="{{ $tour->fitness_level ? '400' : '300' }}">
                <i class="fas fa-user-check"></i>
                <div>
                    <strong>{{ $tour->min_age }}+ Years</strong>
                    <span>Min Age</span>
                </div>
            </div>
            @endif
            <div class="stat-item" data-aos="fade-up" data-aos-delay="{{ $tour->fitness_level && $tour->min_age ? '500' : ($tour->fitness_level || $tour->min_age ? '400' : '300') }}">
                <i class="fas fa-dollar-sign"></i>
                <div>
                    <strong>From ${{ number_format($tour->starting_price ?? $tour->price) }}</strong>
                    <span>Per Person</span>
                </div>
            </div>
            </div>
        </div>
    </section>

<!-- Main Content Section -->
<section class="tour-main-section">
        <div class="container">
            <div class="tour-layout">
                <!-- Main Content -->
                <div class="tour-content-main">
                <!-- Tabs Navigation -->
                <div class="tour-tabs-wrapper" x-data="{ activeTab: 'overview' }">
                    <div class="tour-tabs-nav">
                        <button @click="activeTab = 'overview'" :class="{ 'active': activeTab === 'overview' }" class="tab-btn">
                            <i class="fas fa-info-circle"></i> Overview
                        </button>
                        <button @click="activeTab = 'itinerary'" :class="{ 'active': activeTab === 'itinerary' }" class="tab-btn">
                            <i class="fas fa-route"></i> Itinerary
                        </button>
                        <button @click="activeTab = 'included'" :class="{ 'active': activeTab === 'included' }" class="tab-btn">
                            <i class="fas fa-check-circle"></i> What's Included
                        </button>
                        <button @click="activeTab = 'highlights'" :class="{ 'active': activeTab === 'highlights' }" class="tab-btn">
                            <i class="fas fa-star"></i> Highlights
                        </button>
                        <button @click="activeTab = 'gallery'" :class="{ 'active': activeTab === 'gallery' }" class="tab-btn">
                            <i class="fas fa-images"></i> Gallery
                        </button>
                        <button @click="activeTab = 'reviews'" :class="{ 'active': activeTab === 'reviews' }" class="tab-btn">
                            <i class="fas fa-comments"></i> Reviews
                        </button>
                        <button @click="activeTab = 'faq'" :class="{ 'active': activeTab === 'faq' }" class="tab-btn">
                            <i class="fas fa-question-circle"></i> FAQ
                        </button>
                        <button @click="activeTab = 'map'" :class="{ 'active': activeTab === 'map' }" class="tab-btn">
                            <i class="fas fa-map-marked-alt"></i> Map
                        </button>
                    </div>

                    <!-- Tab Contents -->
                    <div class="tour-tabs-content">
                        <!-- Overview Tab -->
                        <div x-show="activeTab === 'overview'" x-transition class="tab-content-panel">
                            <div class="content-section">
                                <h2 class="section-title">About This Tour</h2>
                                <div class="tour-description">
                                    {!! nl2br(e($tour->long_description ?: $tour->description)) !!}
                                </div>

                                <!-- Tour Details Grid -->
                                <div class="tour-details-grid">
                                    @if($tour->start_location || $tour->end_location)
                                    <div class="detail-card">
                                        <div class="detail-icon"><i class="fas fa-map-marked-alt"></i></div>
                                        <h3>Tour Locations</h3>
                                        @if($tour->start_location)
                                        <p><strong>Start:</strong> {{ $tour->start_location }}</p>
                                        @endif
                                        @if($tour->end_location)
                                        <p><strong>End:</strong> {{ $tour->end_location }}</p>
                                        @endif
                                    </div>
                                    @endif

                                    @if($tour->tour_type)
                                    <div class="detail-card">
                                        <div class="detail-icon"><i class="fas fa-users"></i></div>
                                        <h3>Tour Type</h3>
                                        <p><strong>{{ $tour->tour_type }}</strong></p>
                                        <p class="detail-note">Maximum group size: {{ $tour->max_group_size ?? 12 }} travelers</p>
                                    </div>
                                    @endif

                                    @if($tour->min_age)
                                    <div class="detail-card">
                                        <div class="detail-icon"><i class="fas fa-user-check"></i></div>
                                        <h3>Age Requirements</h3>
                                        <p>Minimum age: <strong>{{ $tour->min_age }} years</strong></p>
                                    </div>
                                    @endif

                                    @if($tour->fitness_level)
                                    <div class="detail-card">
                                        <div class="detail-icon"><i class="fas fa-running"></i></div>
                                        <h3>Fitness Level</h3>
                                        <p>Recommended: <strong>{{ ucfirst($tour->fitness_level) }}</strong></p>
                                    </div>
                                    @endif

                                    @if($tour->difficulty_level)
                                    <div class="detail-card">
                                        <div class="detail-icon"><i class="fas fa-signal"></i></div>
                                        <h3>Difficulty Level</h3>
                                        <p><strong>{{ ucfirst($tour->difficulty_level) }}</strong></p>
                                    </div>
                                    @endif

                                    @if($tour->duration_days)
                                    <div class="detail-card">
                                        <div class="detail-icon"><i class="fas fa-calendar-alt"></i></div>
                                        <h3>Duration</h3>
                                        <p><strong>{{ $tour->duration_days }} Days</strong></p>
                                        <p class="detail-note">{{ $tour->duration_nights ?? ($tour->duration_days - 1) }} Nights</p>
                                    </div>
                                    @endif
                                </div>

                                @if($tour->important_notes)
                                <div class="important-notes">
                                    <h3><i class="fas fa-exclamation-triangle"></i> Important Notes</h3>
                                    <p>{{ $tour->important_notes }}</p>
                                </div>
                                @endif

                                @if($tour->terms_conditions)
                                <div class="terms-conditions">
                                    <h3><i class="fas fa-file-contract"></i> Terms & Conditions</h3>
                                    <p>{{ $tour->terms_conditions }}</p>
                                </div>
                                @endif

                                @if($tour->cancellation_policy)
                                <div class="cancellation-policy">
                                    <h3><i class="fas fa-undo"></i> Cancellation Policy</h3>
                                    <p>{{ $tour->cancellation_policy }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                            <!-- Itinerary Tab -->
                        <div x-show="activeTab === 'itinerary'" x-transition class="tab-content-panel">
                            <div class="content-section">
                                <h2 class="section-title">Day-by-Day Itinerary</h2>
                                <p class="section-subtitle">A detailed breakdown of your {{ $tour->duration_days }}-day adventure</p>
                                
                                @if($tour->itineraries && $tour->itineraries->count() > 0)
                                    <div class="itinerary-timeline">
                                        @foreach($tour->itineraries->sortBy('day_number') as $itinerary)
                                        <div class="itinerary-day" data-aos="fade-up">
                                            <div class="day-number">Day {{ $itinerary->day_number }}</div>
                                            <div class="day-content">
                                                <h3>{{ $itinerary->title }}</h3>
                                                @if($itinerary->description)
                                                <p>{{ $itinerary->description }}</p>
                                                @endif
                                                @if($itinerary->location)
                                                <p class="day-location"><i class="fas fa-map-marker-alt"></i> {{ $itinerary->location }}</p>
                                                @endif
                                                @if($itinerary->meals_included && is_array($itinerary->meals_included))
                                                <div class="day-meals">
                                                    <strong>Meals:</strong>
                                                    @foreach($itinerary->meals_included as $meal)
                                                    <span class="meal-badge">{{ $meal }}</span>
                                                    @endforeach
                                                </div>
                                                @endif
                                                @if($itinerary->accommodation_name)
                                                <div class="day-accommodation">
                                                    <strong><i class="fas fa-bed"></i> Accommodation:</strong> {{ $itinerary->accommodation_name }}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    
                                    <!-- What to Expect Section -->
                                    <div class="what-to-expect-section">
                                        <h3><i class="fas fa-lightbulb"></i> What to Expect</h3>
                                        <div class="expect-grid">
                                            <div class="expect-item">
                                                <i class="fas fa-clock"></i>
                                                <h4>Daily Schedule</h4>
                                                <p>Early morning game drives (6:00 AM - 10:00 AM), afternoon activities, and evening relaxation. Times may vary based on wildlife activity and weather conditions.</p>
                                            </div>
                                            <div class="expect-item">
                                                <i class="fas fa-utensils"></i>
                                                <h4>Meals</h4>
                                                <p>Enjoy delicious meals prepared with fresh, local ingredients. Special dietary requirements can be accommodated with advance notice.</p>
                                            </div>
                                            <div class="expect-item">
                                                <i class="fas fa-bed"></i>
                                                <h4>Accommodation</h4>
                                                <p>Comfortable accommodations ranging from luxury lodges to authentic tented camps, depending on your tour package.</p>
                                            </div>
                                            <div class="expect-item">
                                                <i class="fas fa-car"></i>
                                                <h4>Transportation</h4>
                                                <p>Travel in comfortable 4x4 safari vehicles with pop-up roofs for optimal wildlife viewing and photography.</p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="no-itinerary">
                                        <i class="fas fa-route"></i>
                                        <p>Detailed itinerary coming soon. Please contact us for more information.</p>
                                        <a href="{{ route('contact') }}" class="btn btn-primary">Contact Us for Details</a>
                                    </div>
                                @endif
                            </div>
                        </div>

                            <!-- Included Tab -->
                        <div x-show="activeTab === 'included'" x-transition class="tab-content-panel">
                            <div class="content-section">
                                <h2 class="section-title">What's Included & Excluded</h2>
                                <p class="section-subtitle">Everything you need to know about what's covered in your tour package</p>
                                
                                <div class="included-excluded-grid">
                                    <div class="included-box">
                                        <div class="box-header">
                                            <i class="fas fa-check-circle"></i>
                                            <h3>What's Included</h3>
                                        </div>
                                        @if($tour->inclusions && is_array($tour->inclusions) && count($tour->inclusions) > 0)
                                        <ul class="included-list">
                                            @foreach($tour->inclusions as $inclusion)
                                            <li>
                                                <i class="fas fa-check"></i>
                                                <span>{{ $inclusion }}</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                        @else
                                        <ul class="included-list">
                                            <li><i class="fas fa-check"></i> <span>Professional English-speaking guide</span></li>
                                            <li><i class="fas fa-check"></i> <span>All park entry fees</span></li>
                                            <li><i class="fas fa-check"></i> <span>Accommodation as specified</span></li>
                                            <li><i class="fas fa-check"></i> <span>All meals during tour</span></li>
                                            <li><i class="fas fa-check"></i> <span>Airport transfers</span></li>
                                            <li><i class="fas fa-check"></i> <span>Drinking water</span></li>
                                        </ul>
                                        @endif
                                    </div>
                                    <div class="excluded-box">
                                        <div class="box-header">
                                            <i class="fas fa-times-circle"></i>
                                            <h3>What's Not Included</h3>
                                        </div>
                                        @if($tour->exclusions && is_array($tour->exclusions) && count($tour->exclusions) > 0)
                                        <ul class="excluded-list">
                                            @foreach($tour->exclusions as $exclusion)
                                            <li>
                                                <i class="fas fa-times"></i>
                                                <span>{{ $exclusion }}</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                        @else
                                        <ul class="excluded-list">
                                            <li><i class="fas fa-times"></i> <span>International flights</span></li>
                                            <li><i class="fas fa-times"></i> <span>Visa fees</span></li>
                                            <li><i class="fas fa-times"></i> <span>Travel insurance</span></li>
                                            <li><i class="fas fa-times"></i> <span>Tips and gratuities</span></li>
                                            <li><i class="fas fa-times"></i> <span>Alcoholic beverages</span></li>
                                            <li><i class="fas fa-times"></i> <span>Personal expenses</span></li>
                                        </ul>
                                        @endif
                                    </div>
                                </div>

                                <!-- Additional Info Section -->
                                <div class="additional-info-section">
                                    <h3><i class="fas fa-info-circle"></i> Additional Information</h3>
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <strong><i class="fas fa-plane"></i> Flights:</strong>
                                            <span>International flights are not included. We can assist with flight bookings upon request.</span>
                                        </div>
                                        <div class="info-item">
                                            <strong><i class="fas fa-passport"></i> Visas:</strong>
                                            <span>Visa requirements vary by nationality. Please check with your local embassy.</span>
                                        </div>
                                        <div class="info-item">
                                            <strong><i class="fas fa-shield-alt"></i> Insurance:</strong>
                                            <span>Travel insurance is highly recommended and can be arranged through us.</span>
                                        </div>
                                        <div class="info-item">
                                            <strong><i class="fas fa-money-bill-wave"></i> Currency:</strong>
                                            <span>US Dollars and Tanzanian Shillings are accepted. Credit cards accepted at most locations.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Highlights Tab -->
                        <div x-show="activeTab === 'highlights'" x-transition class="tab-content-panel">
                            <div class="content-section">
                                <h2 class="section-title">Tour Highlights</h2>
                                <p class="section-subtitle">Discover the amazing experiences that await you on this incredible journey</p>
                                
                                @if($tour->highlights && is_array($tour->highlights) && count($tour->highlights) > 0)
                                <div class="highlights-grid">
                                    @foreach($tour->highlights as $index => $highlight)
                                    <div class="highlight-item" data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}">
                                        <div class="highlight-icon-wrapper">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="highlight-content">
                                            <h4>{{ $highlight }}</h4>
                                            <p>Experience this incredible highlight during your {{ $tour->duration_days }}-day adventure.</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                
                                <!-- Why Choose This Tour Section -->
                                <div class="why-choose-section">
                                    <h3><i class="fas fa-heart"></i> Why Choose This Tour?</h3>
                                    <div class="why-choose-grid">
                                        <div class="why-item">
                                            <i class="fas fa-certificate"></i>
                                            <h4>Expert Guides</h4>
                                            <p>Our professional guides have years of experience and deep knowledge of the area.</p>
                                        </div>
                                        <div class="why-item">
                                            <i class="fas fa-shield-alt"></i>
                                            <h4>Safety First</h4>
                                            <p>Your safety is our top priority with comprehensive safety measures in place.</p>
                                        </div>
                                        <div class="why-item">
                                            <i class="fas fa-leaf"></i>
                                            <h4>Sustainable Tourism</h4>
                                            <p>We support local communities and conservation efforts.</p>
                                        </div>
                                        <div class="why-item">
                                            <i class="fas fa-star"></i>
                                            <h4>Premium Experience</h4>
                                            <p>Carefully selected accommodations and experiences for maximum enjoyment.</p>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="highlights-grid">
                                    <div class="highlight-item" data-aos="fade-up">
                                        <div class="highlight-icon-wrapper">
                                            <i class="fas fa-camera"></i>
                                        </div>
                                        <div class="highlight-content">
                                            <h4>Stunning Photography Opportunities</h4>
                                            <p>Capture breathtaking moments throughout your journey.</p>
                                        </div>
                                    </div>
                                    <div class="highlight-item" data-aos="fade-up" data-aos-delay="100">
                                        <div class="highlight-icon-wrapper">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <div class="highlight-content">
                                            <h4>Expert Local Guides</h4>
                                            <p>Learn from experienced guides who know the area intimately.</p>
                                        </div>
                                    </div>
                                    <div class="highlight-item" data-aos="fade-up" data-aos-delay="200">
                                        <div class="highlight-icon-wrapper">
                                            <i class="fas fa-leaf"></i>
                                        </div>
                                        <div class="highlight-content">
                                            <h4>Eco-Friendly Experience</h4>
                                            <p>Travel responsibly while supporting local conservation efforts.</p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Gallery Tab -->
                        <div x-show="activeTab === 'gallery'" x-transition class="tab-content-panel">
                            <div class="content-section">
                                <h2 class="section-title">Photo Gallery</h2>
                                <p class="section-subtitle">Get a glimpse of what awaits you on this incredible journey</p>
                                @if($tour->gallery_images && is_array($tour->gallery_images) && count($tour->gallery_images) > 0)
                                <div class="tour-gallery-grid">
                                    @foreach($tour->gallery_images as $index => $image)
                                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="{{ ($index % 4) * 100 }}">
                                        <img src="{{ str_starts_with($image, 'http') ? $image : asset($image) }}" alt="{{ $tour->name }} - Image {{ $index + 1 }}">
                                        <div class="gallery-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="tour-gallery-grid">
                                    <div class="gallery-item" data-aos="fade-up">
                                        <img src="{{ asset('images/safari_home-1.jpg') }}" alt="{{ $tour->name }}">
                                        <div class="gallery-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </div>
                                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="100">
                                        <img src="{{ asset('images/Serengetei-NP-2.jpeg') }}" alt="{{ $tour->name }}">
                                        <div class="gallery-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </div>
                                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="200">
                                        <img src="{{ asset('images/Tarangire-NP-1.jpeg') }}" alt="{{ $tour->name }}">
                                        <div class="gallery-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </div>
                                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="300">
                                        <img src="{{ asset('images/safari_home-1.jpg') }}" alt="{{ $tour->name }}">
                                        <div class="gallery-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </div>
                                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="400">
                                        <img src="{{ asset('images/Mara-River-3-1536x1024.jpg') }}" alt="{{ $tour->name }}">
                                        <div class="gallery-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </div>
                                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="500">
                                        <img src="{{ asset('images/hero-slider/kilimanjaro-climbing.jpg') }}" alt="{{ $tour->name }}">
                                        <div class="gallery-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                </div>
                            </div>

                             <!-- Reviews Tab -->
                        <div x-show="activeTab === 'reviews'" x-transition class="tab-content-panel">
                            <div class="content-section">
                                <h2 class="section-title">Traveler Reviews</h2>
                                @if($tour->rating)
                                <div class="tour-rating-summary">
                                    <div class="rating-score">
                                        <span class="score">{{ number_format($tour->rating, 1) }}</span>
                                        <div class="rating-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= round($tour->rating) ? 'active' : '' }}"></i>
                                            @endfor
                                        </div>
                                        <p>Based on traveler reviews</p>
                                    </div>
                                </div>
                                @endif
                                
                                @if($tour->reviews && $tour->reviews->count() > 0)
                                    <div class="reviews-list">
                                        @foreach($tour->reviews->take(5) as $review)
                                        <div class="review-card" data-aos="fade-up">
                                            <div class="review-header">
                                                <div class="reviewer-info">
                                                    <strong>{{ $review->reviewer_name ?? 'Anonymous' }}</strong>
                                     <div class="review-stars">
                                                        @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $review->rating ? 'active' : '' }}"></i>
                                                        @endfor
                                                    </div>
                                                </div>
                                                <span class="review-date">{{ $review->created_at->format('M Y') }}</span>
                                            </div>
                                            <p class="review-text">{{ $review->comment }}</p>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="no-reviews">
                                        <i class="fas fa-comments"></i>
                                        <p>No reviews yet. Be the first to review this tour!</p>
                                    </div>
                                @endif
                                 </div>
                             </div>
                        </div>

                        <!-- FAQ Tab -->
                        <div x-show="activeTab === 'faq'" x-transition class="tab-content-panel">
                            <div class="content-section">
                                <h2 class="section-title">Frequently Asked Questions</h2>
                                <div class="faq-list" x-data="{ openIndex: null }">
                                    <div class="faq-item" data-aos="fade-up">
                                        <button @click="openIndex = openIndex === 0 ? null : 0" class="faq-question">
                                            <span>What is included in the tour price?</span>
                                            <i class="fas fa-chevron-down" :class="{ 'rotate': openIndex === 0 }"></i>
                                        </button>
                                        <div x-show="openIndex === 0" x-transition class="faq-answer">
                                            <p>The tour price includes accommodation, meals as specified, professional guide, park entry fees, airport transfers, and all activities mentioned in the itinerary. Please check the "What's Included" tab for complete details.</p>
                                        </div>
                                    </div>
                                    <div class="faq-item" data-aos="fade-up" data-aos-delay="100">
                                        <button @click="openIndex = openIndex === 1 ? null : 1" class="faq-question">
                                            <span>What should I pack for this tour?</span>
                                            <i class="fas fa-chevron-down" :class="{ 'rotate': openIndex === 1 }"></i>
                                        </button>
                                        <div x-show="openIndex === 1" x-transition class="faq-answer">
                                            <p>We recommend packing comfortable clothing, sturdy walking shoes, a hat, sunscreen, insect repellent, a camera, and any personal medications. A detailed packing list will be provided upon booking confirmation.</p>
                                        </div>
                                    </div>
                                    <div class="faq-item" data-aos="fade-up" data-aos-delay="200">
                                        <button @click="openIndex = openIndex === 2 ? null : 2" class="faq-question">
                                            <span>What is the cancellation policy?</span>
                                            <i class="fas fa-chevron-down" :class="{ 'rotate': openIndex === 2 }"></i>
                                        </button>
                                        <div x-show="openIndex === 2" x-transition class="faq-answer">
                                            @if($tour->cancellation_policy)
                                            <p>{{ $tour->cancellation_policy }}</p>
                                            @else
                                            <p>Free cancellation is available up to 30 days before the tour start date. Cancellations made 15-30 days before will receive a 50% refund. Cancellations made less than 15 days before are non-refundable. Please contact us for more details.</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="faq-item" data-aos="fade-up" data-aos-delay="300">
                                        <button @click="openIndex = openIndex === 3 ? null : 3" class="faq-question">
                                            <span>Is travel insurance required?</span>
                                            <i class="fas fa-chevron-down" :class="{ 'rotate': openIndex === 3 }"></i>
                                        </button>
                                        <div x-show="openIndex === 3" x-transition class="faq-answer">
                                            <p>While not mandatory, we strongly recommend comprehensive travel insurance that covers medical emergencies, trip cancellation, and personal belongings. This ensures peace of mind throughout your journey.</p>
                                        </div>
                                    </div>
                                    <div class="faq-item" data-aos="fade-up" data-aos-delay="400">
                                        <button @click="openIndex = openIndex === 4 ? null : 4" class="faq-question">
                                            <span>What is the group size for this tour?</span>
                                            <i class="fas fa-chevron-down" :class="{ 'rotate': openIndex === 4 }"></i>
                                        </button>
                                        <div x-show="openIndex === 4" x-transition class="faq-answer">
                                            <p>The maximum group size is {{ $tour->max_group_size ?? 12 }} travelers. This ensures personalized attention from our guides and a more intimate experience. Private tours can also be arranged upon request.</p>
                                        </div>
                                    </div>
                                    <div class="faq-item" data-aos="fade-up" data-aos-delay="500">
                                        <button @click="openIndex = openIndex === 5 ? null : 5" class="faq-question">
                                            <span>Can I customize this tour?</span>
                                            <i class="fas fa-chevron-down" :class="{ 'rotate': openIndex === 5 }"></i>
                                        </button>
                                        <div x-show="openIndex === 5" x-transition class="faq-answer">
                                            <p>Yes! We offer customizable itineraries to suit your preferences, interests, and schedule. Contact us to discuss your requirements, and we'll create a personalized tour just for you.</p>
                                        </div>
                                     </div>
                                 </div>
                             </div>
                        </div>

                        <!-- Map Tab -->
                        <div x-show="activeTab === 'map'" x-transition class="tab-content-panel">
                            <div class="content-section">
                                <h2 class="section-title">Tour Map</h2>
                                <p class="section-subtitle">Explore the route and key locations of your adventure</p>
                                <div class="tour-map-container">
                                    <!-- Placeholder for an interactive map -->
                                    <div id="tourMap" style="height: 450px; border-radius: 12px; background-color: #e9ecef;"></div>
                                    <p class="map-placeholder-text">Interactive map coming soon.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Booking Sidebar -->
            <aside class="tour-sidebar" id="tourSidebar">
                <div class="booking-card" data-aos="fade-left" id="bookingCard">
                    <div class="booking-header">
                        <div class="price-display">
                            <span class="price-label">Starting from</span>
                            <span class="price-amount">${{ number_format($tour->starting_price ?? $tour->price) }}</span>
                            <span class="price-note">per person</span>
                        </div>
                        @if($tour->rating)
                        <div class="rating-display">
                            <div class="rating-stars-small">
                                @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= round($tour->rating) ? 'active' : '' }}"></i>
                                @endfor
                            </div>
                            <span>{{ number_format($tour->rating, 1) }} ({{ $tour->reviews->count() ?? 0 }} reviews)</span>
                        </div>
                        @endif
                        <div class="tour-badges">
                            @if($tour->is_featured)
                            <span class="badge-featured"><i class="fas fa-star"></i> Featured</span>
                            @endif
                            <span class="badge-popular"><i class="fas fa-fire"></i> Popular</span>
                        </div>
                    </div>
                    
                    <form action="{{ route('booking') }}" method="GET" class="booking-form">
                        <input type="hidden" name="tour" value="{{ $tour->slug }}" id="tour-slug-input">
                        
                        <div class="form-group">
                            <label><i class="fas fa-calendar-alt"></i> Select Date</label>
                            <input type="date" name="date" class="form-control" min="{{ date('Y-m-d') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label><i class="fas fa-users"></i> Number of Travelers</label>
                            <div class="travelers-selector">
                                <button type="button" class="btn-counter" id="decreaseTravelersBtn">-</button>
                                <input type="number" name="travelers" id="travelersCount" value="2" min="1" max="{{ $tour->max_group_size ?? 12 }}" class="form-control" readonly>
                                <button type="button" class="btn-counter" id="increaseTravelersBtn">+</button>
                            </div>
                        </div>

                        <div class="price-breakdown">
                            <div class="price-item">
                                <span>Base Price:</span>
                                <span id="basePrice">${{ number_format($tour->starting_price ?? $tour->price) }} x 2</span>
                            </div>
                            <div class="price-item total">
                                <span>Total Price:</span>
                                <span id="totalPrice">${{ number_format(($tour->starting_price ?? $tour->price) * 2) }}</span>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-booking-primary">
                            <i class="fas fa-calendar-check"></i> Book Now
                        </button>
                        
                        <div class="booking-features">
                            <div class="feature-item">
                                <i class="fas fa-shield-alt"></i>
                                <span>Free Cancellation</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-clock"></i>
                                <span>Instant Confirmation</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-headset"></i>
                                <span>24/7 Support</span>
                            </div>
                        </div>
                    </form>
                    
                    <div class="booking-footer">
                        <a href="{{ route('contact') }}" class="link-contact">
                            <i class="fas fa-question-circle"></i> Have Questions? Contact Us
                        </a>
                        <a href="https://wa.me/255789456123?text=Hi,%20I'm%20interested%20in%20{{ urlencode($tour->name) }}" target="_blank" class="link-whatsapp">
                            <i class="fab fa-whatsapp"></i> Chat on WhatsApp
                        </a>
                    </div>
                </div>
            </aside>
            </div>
        </div>
    </section>

<!-- Map & Location Section -->
@if($tour->start_location || $tour->end_location)
<section class="tour-location-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title">Tour Location</h2>
            <p class="section-subtitle">Start and end points for your adventure</p>
        </div>
        <div class="location-info-grid">
            @if($tour->start_location)
            <div class="location-card" data-aos="fade-up">
                <div class="location-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3>Start Location</h3>
                <p>{{ $tour->start_location }}</p>
            </div>
            @endif
            @if($tour->end_location)
            <div class="location-card" data-aos="fade-up" data-aos-delay="100">
                <div class="location-icon">
                    <i class="fas fa-flag-checkered"></i>
                </div>
                <h3>End Location</h3>
                <p>{{ $tour->end_location }}</p>
            </div>
            @endif
            @if($tour->destination)
            <div class="location-card" data-aos="fade-up" data-aos-delay="200">
                <div class="location-icon">
                    <i class="fas fa-compass"></i>
                </div>
                <h3>Destination</h3>
                <p>{{ $tour->destination->name }}</p>
                <a href="{{ route('destinations.show', $tour->destination->slug) }}" class="location-link">Explore Destination <i class="fas fa-arrow-right"></i></a>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

<!-- Related Tours Section -->
@if($relatedTours && $relatedTours->count() > 0)
<section class="related-tours-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-badge">More Adventures</span>
            <h2 class="section-title">You Might Also Like</h2>
            <p class="section-subtitle">Explore more amazing tours in Tanzania</p>
        </div>
        <div class="related-tours-grid">
            @foreach($relatedTours as $index => $relatedTour)
            <div class="related-tour-card" data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}">
                <a href="{{ route('tours.show', $relatedTour->slug) }}">
                    <div class="tour-image">
                        <img src="{{ $relatedTour->image_url ? (str_starts_with($relatedTour->image_url, 'http') ? $relatedTour->image_url : asset($relatedTour->image_url)) : asset('images/safari_home-1.jpg') }}" alt="{{ $relatedTour->name }}">
                        <div class="tour-overlay">
                            <span class="tour-badge">View Tour</span>
                        </div>
                    </div>
                    <div class="tour-info">
                        <h3>{{ $relatedTour->name }}</h3>
                        <div class="tour-meta">
                            <span><i class="fas fa-clock"></i> {{ $relatedTour->duration_days }} Days</span>
                            <span><i class="fas fa-dollar-sign"></i> From ${{ number_format($relatedTour->starting_price ?? $relatedTour->price) }}</span>
                        </div>
                        @if($relatedTour->rating)
                        <div class="tour-rating">
                            <i class="fas fa-star"></i> {{ number_format($relatedTour->rating, 1) }}
                        </div>
                        @endif
                    </div>
                </a>
            </div>
            @endforeach
            </div>
        </div>
    </section>
@endif

<!-- Lightbox Modal -->
<div id="lightbox" class="lightbox" style="display:none;">
    <span class="lightbox-close">&times;</span>
    <img class="lightbox-content" id="lightbox-img">
    <a class="lightbox-prev">&#10094;</a>
    <a class="lightbox-next">&#10095;</a>
    <div id="lightbox-caption"></div>
</div>

@endsection

@push('scripts')
<script>
function updatePrice() {
    const travelersCount = parseInt(document.getElementById('travelersCount').value);
    const basePrice = {{ $tour->starting_price ?? $tour->price }};
    const totalPrice = travelersCount * basePrice;

    document.getElementById('basePrice').textContent = `$${basePrice.toLocaleString()} x ${travelersCount}`;
    document.getElementById('totalPrice').textContent = `$${totalPrice.toLocaleString()}`;
}

document.addEventListener('DOMContentLoaded', function() {
    const travelersInput = document.getElementById('travelersCount');
    const increaseBtn = document.getElementById('increaseTravelersBtn');
    const decreaseBtn = document.getElementById('decreaseTravelersBtn');

    increaseBtn.addEventListener('click', () => {
        const max = parseInt(travelersInput.getAttribute('max'));
        let current = parseInt(travelersInput.value);
        if (current < max) {
            travelersInput.value = current + 1;
            updatePrice();
        }
    });

    decreaseBtn.addEventListener('click', () => {
        let current = parseInt(travelersInput.value);
        if (current > 1) {
            travelersInput.value = current - 1;
            updatePrice();
        }
    });

    updatePrice();
    const sidebar = document.getElementById('tourSidebar');
    const bookingCard = document.getElementById('bookingCard');
    
    if (sidebar && bookingCard) {
        const mainContent = document.querySelector('.tour-content-main');
        const footer = document.querySelector('footer'); // Adjust selector if needed
        const headerOffset = 100; // Adjust based on your header's height

        window.addEventListener('scroll', () => {
            if (window.innerWidth > 768) {
                const scrollY = window.scrollY;
                const mainContentRect = mainContent.getBoundingClientRect();
                const sidebarRect = sidebar.getBoundingClientRect();
                const footerRect = footer.getBoundingClientRect();

                if (scrollY > mainContentRect.top + window.scrollY - headerOffset) {
                    if (footerRect.top > window.innerHeight) {
                        bookingCard.style.position = 'fixed';
                        bookingCard.style.top = headerOffset + 'px';
                        bookingCard.style.width = sidebar.offsetWidth + 'px';
                    } else {
                        bookingCard.style.position = 'absolute';
                        bookingCard.style.top = (footer.offsetTop - bookingCard.offsetHeight - 250) + 'px';
                    }
                } else {
                    bookingCard.style.position = 'static';
                    bookingCard.style.width = '100%';
                }
            } else {
                bookingCard.style.position = 'static';
                bookingCard.style.width = '100%';
            }
        });
    }
});

// Lightbox functionality
document.addEventListener('DOMContentLoaded', function() {
    const galleryItems = document.querySelectorAll('.gallery-item');
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const lightboxCaption = document.getElementById('lightbox-caption');
    const closeBtn = document.querySelector('.lightbox-close');
    const prevBtn = document.querySelector('.lightbox-prev');
    const nextBtn = document.querySelector('.lightbox-next');
    let currentIndex = 0;

    const images = Array.from(galleryItems).map(item => ({
        src: item.querySelector('img').src,
        alt: item.querySelector('img').alt
    }));

    function showImage(index) {
        if (index >= 0 && index < images.length) {
            currentIndex = index;
            lightboxImg.src = images[currentIndex].src;
            lightboxCaption.textContent = images[currentIndex].alt;
            lightbox.style.display = 'block';
        }
    }

    galleryItems.forEach((item, index) => {
        item.addEventListener('click', () => {
            showImage(index);
        });
    });

    closeBtn.addEventListener('click', () => {
        lightbox.style.display = 'none';
    });

    prevBtn.addEventListener('click', () => {
        showImage((currentIndex - 1 + images.length) % images.length);
    });

    nextBtn.addEventListener('click', () => {
        showImage((currentIndex + 1) % images.length);
    });

    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) {
            lightbox.style.display = 'none';
        }
    });
});
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('css/tour-show.css') }}">
<style>
    /* Lightbox Styles */
    .lightbox {
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .lightbox-content {
        max-width: 90%;
        max-height: 80%;
        margin: auto;
        display: block;
    }

    .lightbox-close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
    }

    .lightbox-prev, .lightbox-next {
        cursor: pointer;
        position: absolute;
        top: 50%;
        width: auto;
        padding: 16px;
        margin-top: -50px;
        color: white;
        font-weight: bold;
        font-size: 20px;
        transition: 0.6s ease;
        border-radius: 0 3px 3px 0;
        user-select: none;
        -webkit-user-select: none;
    }

    .lightbox-next {
        right: 0;
        border-radius: 3px 0 0 3px;
    }

    .lightbox-prev:hover, .lightbox-next:hover {
        background-color: rgba(0,0,0,0.8);
    }

    #lightbox-caption {
        text-align: center;
        color: #ccc;
        padding: 10px 0;
        height: 150px;
    }
>>>>>>> Stashed changes
    /* ============================================
       PROFESSIONAL & UNIQUE DESIGN SYSTEM
       ============================================ */
    /* ============================================
       PROFESSIONAL & UNIQUE DESIGN SYSTEM
       ============================================ */
    
    :root {
        --primary-color: #3ea572;
        --primary-dark: #2d7a5f;
        --primary-light: #6cbe8f;
        --accent-color: #f59e0b;
        --text-dark: #1a1a1a;
        --text-light: #666;
        --bg-light: #f8f9fa;
        --border-color: #e0e0e0;
        --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
        --shadow-md: 0 4px 16px rgba(0,0,0,0.12);
        --shadow-lg: 0 8px 32px rgba(0,0,0,0.16);
        --gradient-primary: linear-gradient(135deg, #3ea572 0%, #2d7a5f 100%);
        --gradient-accent: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }
    
    /* Enhanced Hero Section */
    .tour-hero-section {
        position: relative;
        min-height: 500px;
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        display: flex;
        align-items: center;
        overflow: hidden;
    }
    
    .tour-hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(30, 30, 30, 0.7) 0%, rgba(62, 165, 114, 0.5) 100%);
        z-index: 1;
    }
    
    .tour-hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.3);
        z-index: 2;
    }
    
    .tour-hero-content {
        position: relative;
        z-index: 3;
        color: #fff;
        max-width: 800px;
        margin: 0 auto;
        text-align: center;
        padding: 3rem 1rem;
    }
    
    .tour-breadcrumb {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
        flex-wrap: wrap;
    }
    
    .tour-breadcrumb a {
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        transition: color 0.3s;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
    }
    
    .tour-breadcrumb a:hover {
        color: #fff;
        background: rgba(255, 255, 255, 0.1);
    }
    
    .tour-breadcrumb span {
        color: rgba(255, 255, 255, 0.6);
    }
    
    .tour-badge-featured {
        display: inline-block;
        background: var(--gradient-accent);
        color: #fff;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 1rem;
        box-shadow: var(--shadow-md);
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    .tour-hero-title {
        font-size: 3rem;
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
        letter-spacing: -0.5px;
    }
    
    .tour-hero-subtitle {
        font-size: 1.25rem;
        line-height: 1.6;
        margin-bottom: 2rem;
        color: rgba(255, 255, 255, 0.95);
        text-shadow: 1px 1px 4px rgba(0,0,0,0.2);
    }
    
    .tour-hero-meta {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 2rem;
        flex-wrap: wrap;
        font-size: 1rem;
    }
    
    .tour-hero-meta span {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .tour-hero-meta i {
        font-size: 1.1rem;
    }
    
    /* Enhanced Stats Bar */
    .tour-stats-bar {
        background: #fff;
        padding: 2rem 0;
        box-shadow: var(--shadow-sm);
        border-bottom: 1px solid var(--border-color);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1.5rem;
    }
    
    .stat-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.5rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 12px;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .stat-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--gradient-primary);
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    
    .stat-item:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary-color);
    }
    
    .stat-item:hover::before {
        transform: scaleY(1);
    }
    
    .stat-item i {
        font-size: 2rem;
        color: var(--primary-color);
        min-width: 40px;
    }
    
    .stat-item strong {
        display: block;
        font-size: 1.1rem;
        color: var(--text-dark);
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    
    .stat-item span {
        display: block;
        font-size: 0.875rem;
        color: var(--text-light);
    }
    
    /* Enhanced Main Content */
    .tour-main-section {
        padding: 4rem 0;
        background: var(--bg-light);
    }
    
    .tour-layout {
        display: flex;
        gap: 3rem;
        align-items: flex-start;
    }
    
    .tour-content-main {
        flex: 1;
        min-width: 0;
    }
    
    /* Enhanced Tabs */
    .tour-tabs-wrapper {
        background: #fff;
        border-radius: 16px;
        box-shadow: var(--shadow-md);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .tour-tabs-nav {
        display: flex;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-bottom: 2px solid var(--border-color);
        padding: 0.5rem;
        gap: 0.5rem;
        overflow-x: auto;
        scrollbar-width: none;
    }
    
    .tour-tabs-nav::-webkit-scrollbar {
        display: none;
    }
    
    .tour-tabs-nav .tab-btn {
        flex: 1;
        min-width: fit-content;
        padding: 1rem 1.5rem;
        background: transparent;
        border: none;
        border-radius: 10px;
        font-size: 0.95rem;
        font-weight: 500;
        color: var(--text-light);
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        position: relative;
    }
    
    .tour-tabs-nav .tab-btn i {
        font-size: 1.1rem;
    }
    
    .tour-tabs-nav .tab-btn:hover {
        background: rgba(62, 165, 114, 0.1);
        color: var(--primary-color);
    }
    
    .tour-tabs-nav .tab-btn.active {
        background: var(--gradient-primary);
        color: #fff;
        box-shadow: var(--shadow-sm);
    }
    
    .tour-tabs-content {
        padding: 2.5rem;
    }
    
    .tab-content-panel {
        animation: fadeIn 0.4s ease;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .content-section {
        margin-bottom: 2rem;
    }
    
    .section-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 1rem;
        position: relative;
        padding-bottom: 0.75rem;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 4px;
        background: var(--gradient-primary);
        border-radius: 2px;
    }
    
    .tour-description {
        font-size: 1.1rem;
        line-height: 1.8;
        color: var(--text-light);
        margin-bottom: 2rem;
    }
    
    /* Enhanced Detail Cards */
    .tour-details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin: 2rem 0;
    }
    
    .detail-card {
        background: #fff;
        padding: 2rem;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .detail-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-primary);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .detail-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
    }
    
    .detail-card:hover::before {
        transform: scaleX(1);
    }
    
    .detail-icon {
        width: 60px;
        height: 60px;
        background: var(--gradient-primary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        box-shadow: var(--shadow-sm);
    }
    
    .detail-icon i {
        font-size: 1.5rem;
        color: #fff;
    }
    
    .detail-card h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.75rem;
    }
    
    .detail-card p {
        color: var(--text-light);
        line-height: 1.6;
        margin-bottom: 0.5rem;
    }
    
    .detail-note {
        font-size: 0.875rem;
        color: var(--text-light);
        font-style: italic;
    }
    
    /* Enhanced Sidebar */
    .tour-sidebar {
        width: 380px;
        position: sticky;
        top: 100px;
    }
    
    .tour-booking-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: var(--shadow-lg);
        padding: 2rem;
        border: 1px solid var(--border-color);
        position: sticky;
        top: 100px;
    }
    
    /* Enhanced Gallery */
    .tour-gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }
    
    .gallery-item {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        aspect-ratio: 4/3;
        cursor: pointer;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
    }
    
    .gallery-item:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
    }
    
    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .gallery-item:hover img {
        transform: scale(1.1);
    }
    
    .gallery-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .gallery-item:hover .gallery-overlay {
        opacity: 1;
    }
    
    .gallery-overlay i {
        color: #fff;
        font-size: 2rem;
    }
    
    /* Enhanced Itinerary */
    .itinerary-timeline {
        position: relative;
        padding-left: 3rem;
        margin-top: 2rem;
    }
    
    .itinerary-timeline::before {
        content: '';
        position: absolute;
        left: 1rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--border-color);
    }
    
    .itinerary-day {
        position: relative;
        margin-bottom: 3rem;
        padding-left: 2rem;
    }
    
    .day-number {
        position: absolute;
        left: -2.5rem;
        top: 0;
        width: 3rem;
        height: 3rem;
        background: var(--gradient-primary);
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1rem;
        box-shadow: var(--shadow-md);
        z-index: 2;
    }
    
    .day-content {
        background: #fff;
        padding: 2rem;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
    }
    
    .day-content:hover {
        box-shadow: var(--shadow-md);
        transform: translateX(8px);
    }
    
    .day-content h3 {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 1rem;
    }
    
    .day-location {
        color: var(--primary-color);
        font-weight: 500;
        margin-top: 0.5rem;
    }
    
    .day-meals {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
    }
    
    .meal-badge {
        display: inline-block;
        background: var(--primary-light);
        color: #fff;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.875rem;
        margin-right: 0.5rem;
        margin-top: 0.5rem;
    }
    
    /* Enhanced Buttons */
    .btn-primary {
        background: var(--gradient-primary);
        border: none;
        padding: 1rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        color: #fff;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-sm);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    /* Responsive Enhancements */
    /* Critical fixes to prevent content overflow on small screens */
    @media (max-width: 768px) {
        html, body {
            overflow-x: hidden !important;
            max-width: 100vw !important;
            width: 100% !important;
        }
        
        main {
            overflow-x: hidden !important;
            max-width: 100vw !important;
            width: 100% !important;
        }
        
        .container {
            width: 100% !important;
            max-width: 100% !important;
            padding-left: 15px !important;
            padding-right: 15px !important;
            box-sizing: border-box !important;
        }
        
        section {
            width: 100% !important;
            max-width: 100vw !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }
        
        * {
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        img, video, iframe {
            max-width: 100% !important;
            height: auto !important;
        }
        
        /* Tour Layout Responsive */
        .tour-layout {
            flex-direction: column !important;
            gap: 2rem !important;
        }
        
        .tour-content-main {
            width: 100% !important;
            order: 1;
        }
        
        .tour-sidebar {
            width: 100% !important;
            order: 2;
            position: relative !important;
            top: auto !important;
        }
        
        /* Stats Grid Responsive */
        .stats-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 1rem !important;
        }
        
        .stat-item {
            padding: 1rem !important;
            font-size: 0.875rem !important;
        }
        
        .stat-item i {
            font-size: 1.5rem !important;
        }
        
        /* Tabs Navigation Responsive */
        .tour-tabs-nav {
            flex-wrap: wrap !important;
            gap: 0.5rem !important;
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch !important;
            scrollbar-width: none !important;
        }
        
        .tour-tabs-nav::-webkit-scrollbar {
            display: none !important;
        }
        
        .tour-tabs-nav .tab-btn {
            flex: 0 0 auto !important;
            padding: 0.5rem 1rem !important;
            font-size: 0.875rem !important;
            white-space: nowrap !important;
        }
        
        .tour-tabs-nav .tab-btn i {
            margin-right: 0.25rem !important;
        }
        
        /* Hero Section Responsive */
        .tour-hero-section {
            min-height: 400px !important;
            padding: 2rem 0 !important;
        }
        
        .tour-hero-content {
            padding: 1rem 0 !important;
            text-align: center !important;
        }
        
        .tour-breadcrumb {
            font-size: 0.75rem !important;
            flex-wrap: wrap !important;
            justify-content: center !important;
            margin-bottom: 1rem !important;
            gap: 0.25rem !important;
        }
        
        .tour-breadcrumb a,
        .tour-breadcrumb span {
            white-space: nowrap !important;
        }
        
        .tour-badge-featured {
            display: inline-block !important;
            margin-bottom: 1rem !important;
            font-size: 0.75rem !important;
            padding: 0.375rem 0.75rem !important;
        }
        
        .tour-hero-title {
            font-size: 1.75rem !important;
            line-height: 1.3 !important;
            margin-bottom: 1rem !important;
            word-wrap: break-word !important;
            hyphens: auto !important;
        }
        
        .tour-hero-subtitle {
            font-size: 1rem !important;
            line-height: 1.5 !important;
            margin-bottom: 1.5rem !important;
            padding: 0 1rem !important;
        }
        
        .tour-hero-meta {
            flex-wrap: wrap !important;
            gap: 0.75rem !important;
            font-size: 0.875rem !important;
            justify-content: center !important;
        }
        
        .tour-hero-meta span {
            display: flex !important;
            align-items: center !important;
            gap: 0.25rem !important;
            white-space: nowrap !important;
        }
        
        .tour-hero-meta i {
            font-size: 0.875rem !important;
        }
        
        /* Tour Details Grid Responsive */
        .tour-details-grid {
            grid-template-columns: 1fr !important;
            gap: 1rem !important;
        }
        
        /* Gallery Grid Responsive */
        .tour-gallery-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 0.75rem !important;
        }
        
        /* Booking Card Responsive */
        .tour-booking-card {
            position: relative !important;
            width: 100% !important;
            margin-top: 2rem !important;
        }
        
        /* Itinerary Timeline Responsive */
        .itinerary-timeline {
            padding-left: 1rem !important;
        }
        
        .itinerary-day {
            padding-left: 2rem !important;
        }
        
        .day-number {
            width: 2.5rem !important;
            height: 2.5rem !important;
            font-size: 0.875rem !important;
            left: -1.25rem !important;
        }
    }
    
    @media (max-width: 480px) {
        .container {
            padding-left: 12px !important;
            padding-right: 12px !important;
        }
        
        * {
            max-width: 100vw !important;
        }
        
        /* Stats Grid Mobile */
        .stats-grid {
            grid-template-columns: 1fr !important;
            gap: 0.75rem !important;
        }
        
        /* Tabs Mobile */
        .tour-tabs-nav {
            padding: 0.5rem 0 !important;
        }
        
        .tour-tabs-nav .tab-btn {
            padding: 0.5rem 0.75rem !important;
            font-size: 0.8rem !important;
        }
        
        .tour-tabs-nav .tab-btn span {
            display: none !important;
        }
        
        /* Hero Mobile */
        .tour-hero-section {
            min-height: 350px !important;
            padding: 1.5rem 0 !important;
        }
        
        .tour-hero-content {
            padding: 0.5rem 0 !important;
        }
        
        .tour-breadcrumb {
            font-size: 0.7rem !important;
            flex-wrap: wrap !important;
            justify-content: center !important;
            margin-bottom: 0.75rem !important;
            gap: 0.25rem !important;
        }
        
        .tour-breadcrumb a {
            padding: 0.25rem 0.5rem !important;
        }
        
        .tour-badge-featured {
            font-size: 0.7rem !important;
            padding: 0.25rem 0.5rem !important;
            margin-bottom: 0.75rem !important;
        }
        
        .tour-hero-title {
            font-size: 1.5rem !important;
            line-height: 1.2 !important;
            margin-bottom: 0.75rem !important;
            padding: 0 0.5rem !important;
            word-wrap: break-word !important;
        }
        
        .tour-hero-subtitle {
            font-size: 0.9rem !important;
            line-height: 1.4 !important;
            margin-bottom: 1rem !important;
            padding: 0 0.5rem !important;
        }
        
        .tour-hero-meta {
            flex-direction: column !important;
            gap: 0.5rem !important;
            font-size: 0.8rem !important;
            align-items: center !important;
        }
        
        .tour-hero-meta span {
            justify-content: center !important;
        }
        
        /* Gallery Mobile */
        .tour-gallery-grid {
            grid-template-columns: 1fr !important;
        }
        
        /* Location Grid Mobile */
        .location-info-grid {
            grid-template-columns: 1fr !important;
            gap: 1rem !important;
        }
        
        /* Related Tours Mobile */
        .related-tours-grid {
            grid-template-columns: 1fr !important;
            gap: 1.5rem !important;
        }
        
        /* Section Headers Mobile */
        .section-header {
            text-align: center !important;
        }
        
        .section-title {
            font-size: 1.5rem !important;
        }
        
        .section-subtitle {
            font-size: 0.9rem !important;
        }
    }
    
    /* Tablet Styles */
    @media (min-width: 481px) and (max-width: 768px) {
        .tour-layout {
            flex-direction: column !important;
        }
        
        .tour-content-main,
        .tour-sidebar {
            width: 100% !important;
        }
        
        .stats-grid {
            grid-template-columns: repeat(3, 1fr) !important;
        }
        
        .tour-gallery-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
        
        .tour-details-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
        
        .location-info-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
        
        .related-tours-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }
</style>
@endpush
