@props([
    'title',
    'subtitle' => null,
    'badge' => null,
    'align' => 'center',
    'dark' => false
])

<div class="section-header-wrap {{ $align === 'center' ? 'mx-auto text-center' : '' }} mb-16 max-w-3xl" data-aos="fade-up">
    @if($badge)
        <span class="inline-block py-1 px-4 mb-4 text-xs font-bold tracking-widest uppercase rounded-full {{ $dark ? 'bg-white/10 text-white' : 'bg-primary-green/5 text-primary-green' }} border {{ $dark ? 'border-white/20' : 'border-primary-green/10' }}">
            {{ $badge }}
        </span>
    @endif
    
    <h2 class="text-3xl md:text-5xl font-black mb-6 leading-tight {{ $dark ? 'text-white' : 'text-primary-green' }} font-heading uppercase tracking-tighter">
        {{ $title }}
    </h2>
    
    @if($subtitle)
        <p class="text-lg leading-relaxed {{ $dark ? 'text-white/70' : 'text-gray' }}">
            {{ $subtitle }}
        </p>
    @endif
    
    <div class="mt-8 flex {{ $align === 'center' ? 'justify-center' : '' }}">
        <div class="h-1 w-20 {{ $dark ? 'bg-gold' : 'bg-primary-green' }} rounded-full"></div>
    </div>
</div>

<style>
.section-header-wrap h2 {
    font-family: var(--font-heading);
}
</style>
