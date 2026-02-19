@extends('layouts.app')

@section('title', 'Tanzania Safari Tours - Lau Paradise Adventures')
@section('description', 'Experience the ultimate Tanzania safari adventure. Witness the Big Five, Great Migration, and Africa\'s most iconic wildlife.')

@section('content')

{{-- Premium Hero --}}
<x-ui.hero 
    title="Tanzania <span class='text-gold'>Safari</span> Collections"
    subtitle="Witness the Big Five, the Great Migration, and Africa's most iconic wildlife in the world's greatest national parks."
    image="{{ asset('images/safari_home-1.jpg') }}"
    badge="Unrivaled Wildlife Experiences"
>
    <a href="#safari-packages" class="btn-primary">Explore Packages</a>
    <a href="{{ route('contact') }}" class="btn-secondary">Custom Journey</a>
</x-ui.hero>

{{-- Intro / Philosophy --}}
<section class="safari-intro-section">
    <div class="container">
        <x-ui.section-header 
            badge="The Safari Experience"
            title="A Journey Into The Wild"
            subtitle="Tanzania is the soul of the African safari. From the endless plains of the Serengeti to the 'Garden of Eden' that is the Ngorongoro Crater, we take you deeper into the wilderness."
        />

        <div class="safari-types-grid">
            <div class="safari-type-card" data-aos="fade-up">
                <div class="type-icon"><i class="fas fa-binoculars"></i></div>
                <h3 class="font-heading text-xl font-bold mb-4 uppercase text-primary-green">Northern Circuit</h3>
                <p class="text-gray mb-6">The crown jewel of Africa, featuring the Serengeti and Ngorongoro. Perfect for witnessing the Great Migration.</p>
                <div class="h-1 w-12 bg-gold"></div>
            </div>
            
            <div class="safari-type-card" data-aos="fade-up" data-aos-delay="100">
                <div class="type-icon"><i class="fas fa-leaf"></i></div>
                <h3 class="font-heading text-xl font-bold mb-4 uppercase text-primary-green">Southern Circuit</h3>
                <p class="text-gray mb-6">Vast, remote, and untamed. Selous and Ruaha offer an exclusive, off-the-beaten-path safari experience.</p>
                <div class="h-1 w-12 bg-gold"></div>
            </div>

            <div class="safari-type-card" data-aos="fade-up" data-aos-delay="200">
                <div class="type-icon"><i class="fas fa-umbrella-beach"></i></div>
                <h3 class="font-heading text-xl font-bold mb-4 uppercase text-primary-green">Safari & Blue</h3>
                <p class="text-gray mb-6">The ultimate contrast. Combine high-adrenaline game drives with the soulful turquoise waters of Zanzibar.</p>
                <div class="h-1 w-12 bg-gold"></div>
            </div>
        </div>
    </div>
</section>

{{-- Iconic Parks --}}
<section class="parks-section">
    <div class="container">
        <x-ui.section-header 
            badge="Sanctuaries of Life"
            title="The Great Parks"
            subtitle="Explore our curated selection of Tanzania's most spectacular protected areas."
        />

        <div class="park-list">
            {{-- Park 1 --}}
            <div class="park-card" data-aos="fade-up">
                <div class="park-image-wrap">
                    <img src="{{ asset('images/Serengetei-NP-2.jpeg') }}" alt="Serengeti">
                </div>
                <div class="park-content">
                    <div class="park-meta">
                        <span><i class="fas fa-map-marker-alt"></i> Northern Circuit</span>
                        <span><i class="fas fa-expand"></i> 14,750 km²</span>
                    </div>
                    <h3 class="park-title">Serengeti National Park</h3>
                    <p class="park-desc">Home to the greatest wildlife show on Earth - the Great Migration. Witness millions of wildebeest and zebras traverse the endless plains under the watchful eyes of Africa's apex predators.</p>
                    <div class="park-tags">
                        <span class="tag">The Big Five</span>
                        <span class="tag">Migration</span>
                        <span class="tag">UNESCO Site</span>
                    </div>
                </div>
            </div>

            {{-- Park 2 --}}
            <div class="park-card" data-aos="fade-up">
                <div class="park-image-wrap">
                    <img src="{{ asset('images/safari_home-1.jpg') }}" alt="Ngorongoro">
                </div>
                <div class="park-content">
                    <div class="park-meta">
                        <span><i class="fas fa-map-marker-alt"></i> Conservation Area</span>
                        <span><i class="fas fa-crown"></i> Natural Wonder</span>
                    </div>
                    <h3 class="park-title">Ngorongoro Crater</h3>
                    <p class="park-desc">Often called the 'eighth wonder of the world', this intact volcanic caldera hosts 30,000 animals in a self-contained ecosystem of unparalleled beauty and density.</p>
                    <div class="park-tags">
                        <span class="tag">Rhino Sanctuary</span>
                        <span class="tag">Dense Wildlife</span>
                        <span class="tag">Maasai Culture</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Featured Tours --}}
@if($safariTours->count() > 0)
<section id="safari-packages" class="py-24 bg-off-white">
    <div class="container">
        <x-ui.section-header 
            badge="Curated Adventures"
            title="Our Signature Safaris"
            subtitle="Hand-picked itineraries that balance luxury, adventure, and authentic wildlife encounters."
        />

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($safariTours as $tour)
                <x-ui.tour-card :tour="$tour" />
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA --}}
<section class="relative py-32 overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/safari_home-1.jpg') }}" class="w-full h-full object-cover grayscale opacity-20" alt="">
        <div class="absolute inset-0 bg-primary-green opacity-90"></div>
    </div>
    
    <div class="container relative z-10 text-center text-white">
        <h2 class="text-4xl md:text-6xl font-heading font-black mb-8 uppercase tracking-tighter" data-aos="fade-up">
            Your Soul's Adventure <br><span class="text-gold">Awaits In Tanzania</span>
        </h2>
        <p class="text-xl opacity-70 mb-12 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
            Let our experts design a bespoke safari that matches your rhythm and passion.
        </p>
        <div class="flex justify-center gap-6" data-aos="fade-up" data-aos-delay="200">
            <a href="{{ route('contact') }}" class="btn-primary !bg-gold !text-white !border-gold">Inquire Now</a>
            <a href="{{ route('tours.index') }}" class="btn-secondary !border-white !text-white">All Itineraries</a>
        </div>
    </div>
</section>

@endsection

@push('styles')
    @vite(['resources/css/pages/safaris.css'])
@endpush
