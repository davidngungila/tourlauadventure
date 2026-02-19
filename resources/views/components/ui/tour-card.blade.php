@props(['tour'])

<div class="premium-tour-card group" data-aos="fade-up">
    <div class="card-image-wrap relative overflow-hidden rounded-2xl h-[400px]">
        <img src="{{ $tour['image'] }}" alt="{{ $tour['name'] }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
        
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
        
        <div class="absolute top-4 left-4 flex gap-2">
            @if($tour['is_featured'])
                <span class="bg-gold text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full shadow-lg">Featured</span>
            @endif
        </div>
        
        <div class="absolute bottom-6 left-6 right-6 text-white translate-y-4 transition-transform duration-500 group-hover:translate-y-0">
            <div class="flex items-center gap-2 mb-2 text-gold text-xs font-bold uppercase tracking-widest">
                <i class="fas fa-map-marker-alt"></i> {{ $tour['destination'] }}
            </div>
            
            <h3 class="text-2xl font-bold mb-3 leading-tight font-heading">
                {{ $tour['name'] }}
            </h3>
            
            <div class="flex items-center justify-between opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100">
                <div class="flex items-center gap-4 text-sm font-medium">
                    <span><i class="fas fa-clock text-gold mr-1"></i> {{ $tour['duration_days'] }} Days</span>
                    <span><i class="fas fa-star text-yellow-400 mr-1"></i> {{ $tour['rating'] }}</span>
                </div>
                
                <div class="text-right">
                    <span class="block text-[10px] opacity-70 uppercase tracking-widest">Starting from</span>
                    <span class="text-xl font-bold text-gold">${{ number_format($tour['starting_price'], 0) }}</span>
                </div>
            </div>
            
            <div class="mt-6">
                <a href="{{ route('tours.show', $tour['slug']) }}" class="inline-flex items-center justify-center w-full py-3 bg-white text-primary-green font-bold rounded-xl transition-all duration-300 hover:bg-gold hover:text-white uppercase tracking-widest text-xs">
                    View Adventure <i class="fas fa-arrow-right ml-2 text-[10px]"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.premium-tour-card h3 {
    font-family: var(--font-heading);
}
</style>
