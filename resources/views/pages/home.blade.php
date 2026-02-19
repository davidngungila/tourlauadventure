@extends('layouts.app')

@section('title', 'Lau Paradise Adventures - Premium Tanzania Tours & Safaris')
@section('description', 'Discover the soul of Tanzania with Lau Paradise Adventures. Expert-guided safaris, Kilimanjaro climbs, and Zanzibar escapes.')

@section('content')

{{-- Multi-Slide Hero --}}
<section class="hero-slider-wrapper">
    <div class="slider-container h-full">
        @foreach($heroSlides as $index => $slide)
            <div class="hero-slide-item {{ $index === 0 ? 'active' : '' }}" style="background-image: url('{{ $slide['image_url'] }}'); background-size: cover; background-position: center;">
                <div class="hero-overlay"></div>
                <div class="container h-full">
                    <div class="hero-item-content text-white">
                        @if($slide['badge_text'])
                        <span class="premium-badge mb-6" data-aos="fade-down">
                            <i class="{{ $slide['badge_icon'] ?? 'fas fa-map-marker-alt' }} mr-2"></i> {{ $slide['badge_text'] }}
                        </span>
                        @endif
                        
                        <h1 class="premium-title mb-6 leading-tight uppercase font-black" data-aos="fade-up">
                            {!! $slide['title'] !!}
                        </h1>
                        
                        <p class="premium-subtitle mb-10 max-w-2xl text-lg opacity-80" data-aos="fade-up" data-aos-delay="100">
                            {{ $slide['subtitle'] }}
                        </p>
                        
                        <div class="flex gap-4" data-aos="fade-up" data-aos-delay="200">
                            <a href="{{ $slide['primary_button_link'] }}" class="btn-primary">
                                {{ $slide['primary_button_text'] }} <i class="{{ $slide['primary_button_icon'] }} ml-2"></i>
                            </a>
                            @if($slide['secondary_button_text'])
                            <a href="{{ $slide['secondary_button_link'] }}" class="btn-secondary">
                                {{ $slide['secondary_button_text'] }} <i class="{{ $slide['secondary_button_icon'] }} ml-2"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="slider-nav absolute bottom-10 right-10 z-20 flex gap-4">
        <button id="prevSlide" class="w-12 h-12 rounded-full border border-white/30 text-white hover:bg-gold hover:border-gold transition-all">
            <i class="fas fa-chevron-left text-sm"></i>
        </button>
        <button id="nextSlide" class="w-12 h-12 rounded-full border border-white/30 text-white hover:bg-gold hover:border-gold transition-all">
            <i class="fas fa-chevron-right text-sm"></i>
        </button>
    </div>
</section>

{{-- Featured Adventures --}}
<section class="featured-section">
    <div class="container">
        <x-ui.section-header 
            badge="Limited Collections"
            title="Curated Masterpieces"
            subtitle="Each journey is a hand-crafted experience designed to stir the soul and reveal the true heart of Africa."
        />

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($featuredTours as $tour)
                <x-ui.tour-card :tour="$tour" />
            @endforeach
        </div>
        
        <div class="text-center mt-16" data-aos="fade-up">
            <a href="{{ route('tours.index') }}" class="inline-flex items-center text-primary-green font-bold uppercase tracking-widest group">
                Discover All Adventures 
                <span class="w-10 h-10 ml-4 rounded-full bg-primary-green text-white flex items-center justify-center transition-transform group-hover:translate-x-2">
                    <i class="fas fa-arrow-right"></i>
                </span>
            </a>
        </div>
    </div>
</section>

{{-- About Summary --}}
<section class="bg-white overflow-hidden">
    <div class="container">
        <div class="about-summary-grid">
            <div data-aos="fade-right">
                <span class="text-gold font-bold uppercase tracking-widest text-xs mb-4 block">The Lau Paradise legacy</span>
                <h2 class="text-4xl md:text-5xl font-black font-heading uppercase tracking-tighter text-primary-green mb-8">
                    More Than Just A Tour. <br><span class="text-gold">A Soul Connection.</span>
                </h2>
                <div class="prose prose-lg text-gray mb-10">
                    <p>At Lau Paradise Adventures, we believe that traveling is about connecting with the rhythm of the land and its people. Our journeys are designed with passion, respect for nature, and a commitment to unrivaled excellence.</p>
                </div>
                
                <div class="grid grid-cols-2 gap-8 mb-10">
                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-gray-light rounded-xl flex items-center justify-center text-primary-green shrink-0">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-primary-green uppercase text-sm mb-1">Safety First</h4>
                            <p class="text-xs text-gray">Certified guides and elite equipment.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-12 h-12 bg-gray-light rounded-xl flex items-center justify-center text-primary-green shrink-0">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-primary-green uppercase text-sm mb-1">Ethical Travel</h4>
                            <p class="text-xs text-gray">Committed to local community growth.</p>
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('about') }}" class="btn-primary !bg-primary-green !text-white !border-primary-green">Read Our Story</a>
            </div>
            
            <div class="about-image-stack" data-aos="fade-left">
                <div class="stack-accent"></div>
                <div class="stack-main">
                    <img src="{{ asset('images/safari_home-1.jpg') }}" class="w-full h-full object-cover" alt="">
                </div>
                <div class="experience-orb">
                    <span>15+</span>
                    <span>Years Experience</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Visual Gallery --}}
<section class="gallery-section">
    <div class="container">
        <x-ui.section-header 
            badge="Tanzania in Pictures"
            title="Captured Moments"
            subtitle="Glimpses into the raw beauty and soulful encounters of our recent expeditions."
            dark="true"
        />

        <div class="gallery-masonry">
            @foreach($homepageGallery as $image)
                <div class="gallery-item" data-aos="zoom-in" data-image-url="{{ $image['image_url'] }}">
                    <img src="{{ $image['thumbnail_url'] }}" alt="{{ $image['alt_text'] }}">
                    <div class="gallery-item-overlay">
                        <i class="fas fa-expand text-white text-3xl"></i>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Newsletter / Impact --}}
<section class="py-24 bg-gold">
    <div class="container flex flex-col md:flex-row items-center justify-between gap-12">
        <div class="text-white max-w-xl">
            <h2 class="text-4xl font-black font-heading uppercase mb-4 tracking-tighter">Stay Connected</h2>
            <p class="text-white/80">Join our inner circle for exclusive updates, travel inspiration, and rare collection reveals.</p>
        </div>
        
        <form id="newsletterForm" class="flex-1 w-full max-w-md bg-white/10 p-2 rounded-2xl flex border border-white/20">
            <input type="email" placeholder="Your email address" class="bg-transparent border-none text-white placeholder-white/50 flex-1 px-4 focus:ring-0 outline-none">
            <button class="bg-white text-gold font-bold py-3 px-8 rounded-xl uppercase tracking-widest text-xs transition-transform hover:scale-105">Join</button>
        </form>
    </div>
</section>

@endsection

@push('styles')
    @vite(['resources/css/pages/home.css'])
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.hero-slide-item');
            const nextBtn = document.getElementById('nextSlide');
            const prevBtn = document.getElementById('prevSlide');
            let currentSlide = 0;

            function showSlide(index) {
                slides[currentSlide].classList.remove('active');
                currentSlide = (index + slides.length) % slides.length;
                slides[currentSlide].classList.add('active');
            }

            nextBtn.addEventListener('click', () => showSlide(currentSlide + 1));
            prevBtn.addEventListener('click', () => showSlide(currentSlide - 1));

            // Auto-advance
            setInterval(() => showSlide(currentSlide + 1), 7000);
        });
    </script>
@endpush
