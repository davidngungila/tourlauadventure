@extends('layouts.app')

@section('title', 'About Us - Lau Paradise Adventures')
@section('description', 'Learn about Lau Paradise Adventures, a Tanzania-based tour company dedicated to providing authentic, sustainable, and unforgettable Tanzania travel experiences.')

@section('content')

@php
    $heroSection = $sections->get('hero') ?? null;
    $heroImage = $heroSection && $heroSection->image_url ? (str_starts_with($heroSection->image_url, 'http') ? $heroSection->image_url : asset($heroSection->image_url)) : asset('images/safari_home-1.jpg');
    $heroData = $heroSection ? ($heroSection->data ?? []) : [];
@endphp

<!-- Advanced Hero Section -->
<section class="premium-hero">
    <div class="hero-parallax-bg" style="background-image: url('{{ $heroImage }}');"></div>
    <div class="premium-hero-overlay"></div>
    <div class="container">
        <div class="premium-hero-content" data-aos="fade-up">
            <div class="premium-badge">
                <span class="badge-dot"></span>
                <span class="badge-text">{{ $heroData['badge_text'] ?? 'Our Journey' }}</span>
            </div>
            <h1 class="premium-hero-title">
                @php
                    $title = $heroData['title'] ?? 'About Lau Paradise Adventures';
                    $words = explode(' ', $title);
                    $lastWord = array_pop($words);
                    $firstPart = implode(' ', $words);
                @endphp
                {{ $firstPart }} <span class="text-gradient">{{ $lastWord }}</span>
            </h1>
            <p class="premium-hero-subtitle">{{ $heroData['subtitle'] ?? "Tanzania's premier tour operator offering authentic safaris, Kilimanjaro climbs, and beach holidays." }}</p>
            <div class="hero-scroll-indicator">
                <span class="mouse">
                    <span class="wheel"></span>
                </span>
                <span class="scroll-label">Scroll to Explore</span>
            </div>
        </div>
    </div>
</section>

<!-- Story Section with Glassmorphism -->
@php
    $storySection = $sections->get('story') ?? null;
    $storyData = $storySection ? ($storySection->data ?? []) : [];
    $storyImage = $storySection && $storySection->image_url ? (str_starts_with($storySection->image_url, 'http') ? $storySection->image_url : asset($storySection->image_url)) : asset('images/safari_home-1.jpg');
    $foundedYear = $storyData['founded_year'] ?? '2025';
@endphp
<section class="adv-story-section">
    <div class="container">
        <div class="adv-story-grid">
            <div class="adv-story-image-wrapper" data-aos="fade-right">
                <div class="image-stack">
                    <div class="main-image">
                        <img src="{{ $storyImage }}" alt="Our Story">
                    </div>
                    <div class="experience-badge">
                        <div class="exp-number">15+</div>
                        <div class="exp-text">Years of <br>Expertise</div>
                    </div>
                </div>
            </div>
            <div class="adv-story-content" data-aos="fade-left">
                <div class="section-tag">Since {{ $foundedYear }}</div>
                <h2 class="adv-section-title">{{ $storyData['title'] ?? 'Born in Tanzania, Dedicated to Tanzania' }}</h2>
                <div class="adv-text-block">
                    @if($storySection && $storySection->content)
                        {!! nl2br(e($storySection->content)) !!}
                    @else
                        <p>Lau Paradise Adventures was founded with a simple mission: to share the incredible beauty and wonder of Tanzania with the world. Born from a deep love for our homeland, we started as a small local company in Arusha, with a passion for showcasing the best of what Tanzania has to offer.</p>
                        <p>As a Tanzania-based company, we understand our country like no one else. Our team consists of local Tanzanians who have grown up exploring these lands. We ensure every journey is authentic, sustainable, and deeply personal.</p>
                    @endif
                </div>
                <div class="adv-story-stats">
                    @foreach($statistics->take(3) as $index => $stat)
                    <div class="adv-min-stat">
                        <span class="min-stat-num" data-count="{{ $stat->value }}">0</span>
                        <span class="min-stat-label">{{ $stat->label }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values & Strategy Section -->
<section class="strategy-section">
    <div class="container">
        <div class="strategy-header" data-aos="fade-up">
            <h2 class="centered-title">Our Core <span class="text-gradient">Philosophy</span></h2>
            <p class="centered-subtitle">The principles that drive our commitment to excellence and sustainable tourism in Tanzania.</p>
        </div>
        
        <div class="strategy-grid">
            @foreach($values as $index => $value)
            <div class="strategy-card" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <div class="strategy-icon-box">
                    <i class="{{ $value->icon ?? 'fas fa-star' }}"></i>
                </div>
                <h3>{{ $value->title }}</h3>
                <p>{{ $value->description }}</p>
                <div class="card-hover-bg"></div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Vision & Mission Side-by-Side -->
@php
    $missionSection = $sections->get('mission') ?? null;
    $visionSection = $sections->get('vision') ?? null;
    $missionData = $missionSection ? ($missionSection->data ?? []) : [];
    $visionData = $visionSection ? ($visionSection->data ?? []) : [];
@endphp
<section class="mission-vision-advanced">
    <div class="container">
        <div class="mv-split-layout">
            <div class="mv-block mission" data-aos="fade-up">
                <div class="mv-header">
                    <div class="mv-icon"><i class="{{ $missionData['icon'] ?? 'fas fa-bullseye' }}"></i></div>
                    <h3>{{ $missionData['title'] ?? 'Our Mission' }}</h3>
                </div>
                <div class="mv-body">
                    <p>{{ $missionSection && $missionSection->content ? $missionSection->content : ($missionData['text'] ?? 'To provide authentic, sustainable, and transformative Tanzania travel experiences that connect travelers with nature.') }}</p>
                </div>
            </div>
            <div class="mv-block vision" data-aos="fade-up" data-aos-delay="100">
                <div class="mv-header">
                    <div class="mv-icon"><i class="{{ $visionData['icon'] ?? 'fas fa-eye' }}"></i></div>
                    <h3>{{ $visionData['title'] ?? 'Our Vision' }}</h3>
                </div>
                <div class="mv-body">
                    <p>{{ $visionSection && $visionSection->content ? $visionSection->content : ($visionData['text'] ?? 'To be Tanzania\'s most trusted tour operator, recognized globally for excellence and authentic cultural experiences.') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section with Interactive Cards -->
<section class="premium-team">
    <div class="container">
        <div class="section-header-compact" data-aos="fade-up">
            <span class="pre-title">The Experts</span>
            <h2 class="main-title">The People <span class="text-gradient">Behind</span> the Scenes</h2>
        </div>
        
        <div class="premium-team-grid">
            @foreach($teamMembers as $index => $member)
            <div class="team-card-v2" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <div class="team-img-container">
                    <img src="{{ $member->image_url ? (str_starts_with($member->image_url, 'http') ? $member->image_url : asset($member->image_url)) : 'https://randomuser.me/api/portraits/men/' . (32 + $index) . '.jpg' }}" alt="{{ $member->name }}">
                    <div class="team-card-overlay">
                        <div class="team-social-links">
                            @if($member->social_links)
                                @foreach($member->social_links as $platform => $url)
                                    @if($url)
                                        <a href="{{ $url }}" target="_blank" class="social-circle"><i class="fab fa-{{ $platform }}"></i></a>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="team-info-v2">
                    <h4 class="member-name-v2">{{ $member->name }}</h4>
                    <span class="member-role-v2">{{ $member->role }}</span>
                    <p class="member-excerpt">{{ Str::limit($member->bio, 80) }}</p>
                    <div class="member-expertise-tags">
                        @if($member->expertise)
                            @foreach(array_slice($member->expertise, 0, 2) as $expertise)
                                <span class="exp-tag">{{ $expertise }}</span>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Dynamic Timeline -->
<section class="adv-timeline-section">
    <div class="container">
        <div class="timeline-header" data-aos="fade-up">
            <h2 class="centered-title">Our <span class="text-gradient">Milestones</span></h2>
        </div>
        
        <div class="adv-timeline-container">
            @foreach($timelineItems as $index => $item)
            <div class="adv-timeline-item" data-aos="{{ $index % 2 == 0 ? 'fade-right' : 'fade-left' }}">
                <div class="time-marker">
                    <div class="marker-dot"></div>
                    <div class="marker-line"></div>
                </div>
                <div class="timeline-card">
                    <div class="time-year">{{ $item->year }}</div>
                    <h3>{{ $item->title }}</h3>
                    <p>{{ $item->description }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Recognition & Trust -->
<section class="recognition-adv">
    <div class="container">
        <div class="trust-flex">
            <div class="trust-content" data-aos="fade-right">
                <span class="pre-title">Trusted & Certified</span>
                <h2 class="title-v3">Global Recognition <br>& <span class="text-gradient">Certifications</span></h2>
                <p>We are proud members of various international tourism bodies and local associations, ensuring we adhere to the highest standards of safety and service.</p>
                <div class="trust-badges-row">
                    <i class="fas fa-certificate"></i>
                    <i class="fas fa-medal"></i>
                    <i class="fas fa-award"></i>
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="recognition-grid-v2" data-aos="fade-left">
                @foreach($recognitions->take(4) as $index => $recognition)
                <div class="rec-card-v2">
                    <div class="rec-icon-v2"><i class="{{ $recognition->icon ?? 'fas fa-award' }}"></i></div>
                    <div class="rec-info-v2">
                        <h4>{{ $recognition->title }}</h4>
                        <span>{{ $recognition->year ?? 'Annual' }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="adv-cta">
    <div class="cta-inner">
        <div class="container">
            <div class="cta-content-v2" data-aos="zoom-in">
                <h2>Plan Your <span class="text-gradient">Perfect</span> Story</h2>
                <p>Your Tanzania adventure starts here. Let us help you create memories that last a lifetime.</p>
                <div class="cta-action-btns">
                    <a href="{{ route('contact') }}" class="btn-premium">Start Planning</a>
                    <a href="{{ route('tours.index') }}" class="btn-outline-prem">Explore Tours</a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
    :root {
        --prem-green: #1a4d3a;
        --prem-accent: #3ea572;
        --prem-bg: #fdfdfd;
        --prem-text: #2d3436;
        --prem-gradient: linear-gradient(135deg, #1a4d3a 0%, #3ea572 100%);
        --glass: rgba(255, 255, 255, 0.8);
        --glass-border: rgba(255, 255, 255, 0.3);
        --shadow-soft: 0 10px 30px rgba(0,0,0,0.05);
        --shadow-strong: 0 20px 40px rgba(0,0,0,0.1);
    }

    .text-gradient {
        background: var(--prem-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Hero Styles */
    .premium-hero {
        position: relative;
        height: 80vh;
        min-height: 600px;
        display: flex;
        align-items: center;
        overflow: hidden;
    }

    .hero-parallax-bg {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 110%;
        background-size: cover;
        background-position: center;
        z-index: 0;
        transform: translateY(0);
        transition: transform 0.1s linear;
    }

    .premium-hero-overlay {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(to right, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.2) 100%);
        z-index: 1;
    }

    .premium-hero-content {
        position: relative;
        z-index: 2;
        max-width: 800px;
        color: white;
    }

    .premium-badge {
        display: inline-flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        padding: 8px 20px;
        border-radius: 50px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 25px;
    }

    .badge-dot {
        width: 8px; height: 8px;
        background: var(--prem-accent);
        border-radius: 50%;
        margin-right: 10px;
        box-shadow: 0 0 10px var(--prem-accent);
    }

    .premium-hero-title {
        font-size: 4.5rem;
        font-weight: 800;
        line-height: 1.1;
        margin-bottom: 20px;
    }

    .premium-hero-subtitle {
        font-size: 1.4rem;
        opacity: 0.9;
        margin-bottom: 40px;
        font-weight: 300;
    }

    /* Story Section */
    .adv-story-section {
        padding: 120px 0;
        background: white;
    }

    .adv-story-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 80px;
        align-items: center;
    }

    .image-stack {
        position: relative;
        padding-bottom: 40px;
    }

    .main-image {
        border-radius: 30px;
        overflow: hidden;
        box-shadow: var(--shadow-strong);
    }

    .main-image img { width: 100%; display: block; }

    .experience-badge {
        position: absolute;
        bottom: 0; right: -20px;
        background: var(--prem-accent);
        color: white;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 15px 30px rgba(62, 165, 114, 0.3);
        text-align: center;
    }

    .exp-number { font-size: 2.5rem; font-weight: 800; line-height: 1; }
    .exp-text { font-size: 0.9rem; font-weight: 600; margin-top: 5px; }

    .section-tag {
        color: var(--prem-accent);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 15px;
    }

    .adv-section-title {
        font-size: 3rem;
        color: var(--prem-green);
        margin-bottom: 30px;
        font-weight: 800;
    }

    .adv-text-block p {
        font-size: 1.15rem;
        color: #636e72;
        line-height: 1.8;
        margin-bottom: 25px;
    }

    .adv-story-stats {
        display: flex;
        gap: 50px;
        margin-top: 40px;
    }

    .adv-min-stat { display: flex; flex-direction: column; }
    .min-stat-num { font-size: 2.2rem; font-weight: 800; color: var(--prem-green); }
    .min-stat-label { color: #b2bec3; font-weight: 600; font-size: 0.9rem; }

    /* Strategy Grid */
    .strategy-section {
        padding: 100px 0;
        background: #f8fcfb;
    }

    .strategy-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        margin-top: 60px;
    }

    .strategy-card {
        background: white;
        padding: 50px 40px;
        border-radius: 25px;
        box-shadow: var(--shadow-soft);
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease;
        z-index: 1;
    }

    .strategy-card:hover { transform: translateY(-10px); }

    .strategy-icon-box {
        width: 70px; height: 70px;
        background: var(--prem-bg);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: var(--prem-accent);
        margin-bottom: 30px;
        transition: 0.3s;
    }

    .strategy-card:hover .strategy-icon-box { background: var(--prem-accent); color: white; }

    .strategy-card h3 { font-size: 1.5rem; color: var(--prem-green); margin-bottom: 15px; font-weight: 700; }
    .strategy-card p { color: #636e72; line-height: 1.6; }

    /* Team Section */
    .premium-team { padding: 120px 0; background: white; }
    
    .premium-team-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 40px;
        margin-top: 60px;
    }

    .team-card-v2 {
        background: white;
        border-radius: 25px;
        overflow: hidden;
        box-shadow: var(--shadow-soft);
        transition: 0.4s;
    }

    .team-card-v2:hover { box-shadow: var(--shadow-strong); transform: translateY(-5px); }

    .team-img-container {
        position: relative;
        height: 350px;
        overflow: hidden;
    }

    .team-img-container img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
    .team-card-v2:hover img { transform: scale(1.1); }

    .team-card-overlay {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(26, 77, 58, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: 0.3s;
    }

    .team-card-v2:hover .team-card-overlay { opacity: 1; }

    .social-circle {
        width: 45px; height: 45px;
        background: white;
        color: var(--prem-green);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 5px;
        text-decoration: none;
        transition: 0.3s;
    }

    .social-circle:hover { background: var(--prem-accent); color: white; transform: rotate(360deg); }

    .team-info-v2 { padding: 30px; }
    .member-name-v2 { font-size: 1.4rem; color: var(--prem-green); font-weight: 700; margin-bottom: 5px; }
    .member-role-v2 { color: var(--prem-accent); font-weight: 600; font-size: 0.9rem; display: block; margin-bottom: 15px; }
    .member-excerpt { font-size: 0.95rem; color: #636e72; margin-bottom: 20px; }

    .exp-tag {
        background: #f1f7f5;
        color: var(--prem-accent);
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-right: 5px;
    }

    /* Timeline Styles */
    .adv-timeline-section { padding: 120px 0; background: #fafafa; }
    .adv-timeline-container { position: relative; max-width: 900px; margin: 60px auto 0; padding-left: 50px; }
    
    .adv-timeline-container::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 2px;
        background: #e1e8e5;
    }

    .adv-timeline-item { position: relative; margin-bottom: 80px; }

    .time-marker {
        position: absolute;
        left: -51px; top: 10px;
        width: 10px; height: 100%;
    }

    .marker-dot {
        width: 16px; height: 16px;
        background: var(--prem-accent);
        border: 4px solid white;
        border-radius: 50%;
        box-shadow: 0 0 0 4px #e8f5f0;
    }

    .timeline-card {
        background: white;
        padding: 40px;
        border-radius: 20px;
        box-shadow: var(--shadow-soft);
        position: relative;
    }

    .timeline-card::after {
        content: '';
        position: absolute;
        left: -10px; top: 20px;
        width: 20px; height: 20px;
        background: white;
        transform: rotate(45deg);
    }

    .time-year { font-size: 1.8rem; font-weight: 800; color: var(--prem-accent); margin-bottom: 10px; }

    /* CTA Section */
    .adv-cta { padding: 80px 0; }
    .cta-inner {
        background: var(--prem-green);
        border-radius: 40px;
        padding: 100px 60px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .cta-inner::before {
        content: '';
        position: absolute;
        top: -50%; right: -20%;
        width: 600px; height: 600px;
        background: rgba(62, 165, 114, 0.1);
        border-radius: 50%;
    }

    .cta-content-v2 h2 { font-size: 3.5rem; color: white; font-weight: 800; margin-bottom: 25px; }
    .cta-content-v2 p { font-size: 1.3rem; color: rgba(255,255,255,0.8); margin-bottom: 40px; }

    .btn-premium {
        background: var(--prem-accent);
        color: white;
        padding: 18px 40px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
        transition: 0.3s;
        display: inline-block;
        margin: 10px;
    }

    .btn-premium:hover { background: white; color: var(--prem-green); transform: scale(1.05); }

    .btn-outline-prem {
        border: 2px solid rgba(255,255,255,0.3);
        color: white;
        padding: 18px 40px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
        transition: 0.3s;
        display: inline-block;
        margin: 10px;
    }

    .btn-outline-prem:hover { border-color: white; background: rgba(255,255,255,0.1); }

    @media (max-width: 992px) {
        .adv-story-grid { grid-template-columns: 1fr; gap: 40px; }
        .premium-hero-title { font-size: 3rem; }
        .cta-content-v2 h2 { font-size: 2.5rem; }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Simple Parallax Effect
        window.addEventListener('scroll', function() {
            const scroll = window.pageYOffset;
            const heroBg = document.querySelector('.hero-parallax-bg');
            if (heroBg) {
                heroBg.style.transform = `translateY(${scroll * 0.4}px)`;
            }
        });

        // Statistics Counter
        const stats = document.querySelectorAll('.min-stat-num');
        const observerOptions = { threshold: 0.5 };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const target = parseInt(entry.target.getAttribute('data-count'));
                    animateValue(entry.target, 0, target, 2000);
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        stats.forEach(stat => observer.observe(stat));

        function animateValue(obj, start, end, duration) {
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                obj.innerHTML = Math.floor(progress * (end - start) + start);
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }
    });
</script>
@endpush
