<footer class="footer-premium bg-primary-green pt-24 pb-12 text-white overflow-hidden relative">
    {{-- Background Pattern --}}
    <div class="absolute inset-0 opacity-5 pointer-events-none">
        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
            <path d="M0 100 L50 0 L100 100 Z" fill="currentColor"></path>
        </svg>
    </div>

    <div class="container relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 mb-20">
            {{-- Brand Column --}}
            <div class="lg:col-span-4" data-aos="fade-up">
                <a href="{{ route('home') }}" class="inline-block mb-10">
                    <img src="{{ asset('lau-adventuress.png') }}" alt="Lau Paradise" class="h-20 w-auto object-contain brightness-0 invert">
                </a>
                <p class="text-white/60 text-lg leading-relaxed mb-10">
                    Tanzania's premier soul-journey architect. We curate life-altering experiences through the untamed beauty of East Africa.
                </p>
                <div class="flex gap-4">
                    <a href="#" class="w-12 h-12 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-gold hover:border-gold transition-all duration-300">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="w-12 h-12 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-gold hover:border-gold transition-all duration-300">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-12 h-12 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-gold hover:border-gold transition-all duration-300">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>

            {{-- Links --}}
            <div class="lg:col-span-2" data-aos="fade-up" data-aos-delay="100">
                <h4 class="text-gold font-heading font-black text-sm uppercase tracking-widest mb-8">Collections</h4>
                <ul class="flex flex-col gap-4 text-white/70 font-bold uppercase text-xs tracking-wider">
                    <li><a href="{{ route('safaris') }}" class="hover:text-white transition-colors">Safaris</a></li>
                    <li><a href="{{ route('tours.index') }}" class="hover:text-white transition-colors">Expeditions</a></li>
                    <li><a href="{{ route('destinations.index') }}" class="hover:text-white transition-colors">Destinations</a></li>
                    <li><a href="{{ route('tours.category', 'beach') }}" class="hover:text-white transition-colors">Zanzibar</a></li>
                </ul>
            </div>

            <div class="lg:col-span-2" data-aos="fade-up" data-aos-delay="200">
                <h4 class="text-gold font-heading font-black text-sm uppercase tracking-widest mb-8">Legacy</h4>
                <ul class="flex flex-col gap-4 text-white/70 font-bold uppercase text-xs tracking-wider">
                    <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">Our Story</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-white transition-colors">Contact</a></li>
                    <li><a href="{{ route('careers') }}" class="hover:text-white transition-colors">Careers</a></li>
                    <li><a href="{{ route('sustainability') }}" class="hover:text-white transition-colors">Impact</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div class="lg:col-span-4" data-aos="fade-up" data-aos-delay="300">
                <h4 class="text-gold font-heading font-black text-sm uppercase tracking-widest mb-8">Arusha Sanctuary</h4>
                <div class="flex gap-6 mb-8">
                    <div class="w-10 h-10 rounded-xl bg-gold/10 flex items-center justify-center text-gold shrink-0">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <span class="block text-white font-bold uppercase text-xs tracking-widest mb-1">Office Location</span>
                        <p class="text-white/60 text-sm">Sekei Area, Arusha, Tanzania</p>
                    </div>
                </div>
                <div class="flex gap-6">
                    <div class="w-10 h-10 rounded-xl bg-gold/10 flex items-center justify-center text-gold shrink-0">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div>
                        <span class="block text-white font-bold uppercase text-xs tracking-widest mb-1">Direct Line</span>
                        <a href="tel:+255683163219" class="text-white/60 text-sm hover:text-white transition-colors">+255 683 163 219</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="text-white/40 text-[10px] uppercase font-bold tracking-widest">
                &copy; {{ date('Y') }} LAU PARADISE ADVENTURES. ALL RIGHTS RESERVED.
            </p>
            <div class="flex gap-8 text-white/40 text-[10px] uppercase font-bold tracking-widest">
                <a href="{{ route('privacy') }}" class="hover:text-gold transition-colors">Privacy</a>
                <a href="{{ route('terms') }}" class="hover:text-gold transition-colors">Terms</a>
                <span class="text-white/20">Designed by <span class="text-gold opacity-60">Ngungila D</span></span>
            </div>
        </div>
    </div>
</footer>

<style>
.footer-premium h4 {
    position: relative;
    display: inline-block;
}

.footer-premium h4::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 0;
    width: 20px;
    height: 1px;
    background: var(--gold);
}
</style>
