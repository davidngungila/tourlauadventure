@props([
    'title', 
    'subtitle' => null, 
    'image' => null, 
    'badge' => null,
    'height' => '70vh',
    'overlay' => 'linear-gradient(135deg, rgba(10, 46, 36, 0.8) 0%, rgba(0, 0, 0, 0.4) 100%)'
])

<section class="premium-hero" style="height: {{ $height }}; --hero-overlay: {{ $overlay }}; --hero-bg: url('{{ $image }}');">
    <div class="hero-parallax-bg"></div>
    <div class="container relative z-10 flex flex-col items-center justify-center h-full text-center text-white">
        @if($badge)
            <span class="premium-badge mb-6" data-aos="fade-down">
                {{ $badge }}
            </span>
        @endif
        
        <h1 class="premium-title mb-6 leading-tight" data-aos="fade-up" data-aos-delay="100">
            {!! $title !!}
        </h1>
        
        @if($subtitle)
            <p class="premium-subtitle mb-8 max-w-2xl text-lg opacity-90" data-aos="fade-up" data-aos-delay="200">
                {{ $subtitle }}
            </p>
        @endif
        
        <div class="hero-actions flex gap-4" data-aos="fade-up" data-aos-delay="300">
            {{ $slot }}
        </div>
    </div>
    
    <div class="scroll-indicator">
        <div class="mouse"></div>
    </div>
</section>

<style>
.premium-hero {
    position: relative;
    width: 100%;
    overflow: hidden;
    background-color: var(--primary-green);
}

.hero-parallax-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 120%; /* Extra height for parallax */
    background: var(--hero-overlay), var(--hero-bg);
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    z-index: 1;
    transform: translateY(0);
}

.premium-title {
    font-family: var(--font-heading);
    font-size: clamp(2.5rem, 5vw, 4.5rem);
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: -1px;
    text-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.premium-badge {
    background: var(--glass);
    backdrop-filter: blur(10px);
    border: 1px solid var(--glass-border);
    color: var(--primary-green);
    padding: 8px 18px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 2px;
}

.premium-subtitle {
    font-family: var(--font-body);
    font-weight: 400;
    line-height: 1.6;
}

.scroll-indicator {
    position: absolute;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
}

.mouse {
    width: 24px;
    height: 40px;
    border: 2px solid rgba(255,255,255,0.5);
    border-radius: 20px;
    position: relative;
}

.mouse::before {
    content: '';
    width: 4px;
    height: 8px;
    background: white;
    position: absolute;
    left: 50%;
    margin-left: -2px;
    top: 8px;
    border-radius: 2px;
    animation: scrollMove 2s infinite;
}

@keyframes scrollMove {
    0% { transform: translateY(0); opacity: 0; }
    50% { transform: translateY(10px); opacity: 1; }
    100% { transform: translateY(15px); opacity: 0; }
}

@media (max-width: 768px) {
    .hero-parallax-bg {
        background-attachment: scroll; /* Disable fixed bg on mobile for performance */
    }
}
</style>
