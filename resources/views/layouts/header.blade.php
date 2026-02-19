<header class="header-main fixed top-0 left-0 w-full z-[1000] transition-all duration-500" id="mainHeader">
    <div class="top-bar hidden md:block">
        <div class="container flex justify-between items-center h-10">
            <p class="flex items-center gap-2">
                <i class="fas fa-crown text-gold"></i>
                <span>Tanzania's Premier Luxury Collection</span>
            </p>
            <div class="flex gap-6">
                <a href="mailto:info@lauparadise.com" class="hover:text-gold transition-colors">info@lauparadise.com</a>
                <a href="tel:+255683163219" class="hover:text-gold transition-colors">+255 683 163 219</a>
            </div>
        </div>
    </div>
    
    <nav class="main-nav relative">
        <div class="container flex justify-between items-center py-4 md:py-6 transition-all duration-500" id="navContainer">
            <a href="{{ route('home') }}" class="logo transition-transform duration-500 hover:scale-105">
                <img src="{{ asset('lau-adventuress.png') }}" alt="Lau Paradise" class="h-12 md:h-16 w-auto object-contain">
            </a>
            
            <div class="hidden lg:flex items-center gap-10">
                <ul class="flex gap-10">
                    <li><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                    
                    <li class="relative group">
                        <a href="{{ route('safaris') }}" class="nav-link flex items-center gap-2 {{ request()->routeIs('safaris') ? 'active' : '' }}">
                            Safaris <i class="fas fa-chevron-down text-[10px] transition-transform group-hover:rotate-180"></i>
                        </a>
                        <!-- Mega Dropdown -->
                        <div class="absolute top-full left-1/2 -translate-x-1/2 mt-4 w-[600px] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                            <div class="dropdown-menu">
                                <div class="flex justify-between items-center mb-8 pb-4 border-b border-gray-light">
                                    <h3 class="font-heading text-lg font-black text-primary-green uppercase tracking-tighter">Safari Collections</h3>
                                    <a href="{{ route('safaris') }}" class="text-xs font-bold text-gold uppercase tracking-widest hover:text-primary-green transition-colors">View All &rarr;</a>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    @foreach($navSafaris as $safari)
                                        <a href="{{ route('tours.show', $safari['slug']) }}" class="dropdown-item flex gap-4 p-3 hover:bg-off-white transition-colors group/item">
                                            <div class="w-20 h-20 rounded-xl overflow-hidden shrink-0">
                                                <img src="{{ $safari['image'] }}" class="w-full h-full object-cover transition-transform group-hover/item:scale-110" alt="">
                                            </div>
                                            <div class="flex flex-col justify-center">
                                                <h4 class="text-sm font-bold text-primary-green group-hover/item:text-gold transition-colors">{{ $safari['name'] }}</h4>
                                                <span class="text-[10px] text-gray uppercase tracking-widest mt-1">{{ $safari['duration_days'] }} Days</span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="relative group">
                        <a href="{{ route('tours.index') }}" class="nav-link flex items-center gap-2 {{ request()->routeIs('tours.*') ? 'active' : '' }}">
                            Expeditions <i class="fas fa-chevron-down text-[10px] transition-transform group-hover:rotate-180"></i>
                        </a>
                        <div class="absolute top-full left-1/2 -translate-x-1/2 mt-4 w-[600px] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                            <div class="dropdown-menu">
                                <div class="flex justify-between items-center mb-8 pb-4 border-b border-gray-light">
                                    <h3 class="font-heading text-lg font-black text-primary-green uppercase tracking-tighter">Featured Expeditions</h3>
                                    <a href="{{ route('tours.index') }}" class="text-xs font-bold text-gold uppercase tracking-widest">View All</a>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    @foreach($navTours as $tour)
                                        <a href="{{ route('tours.show', $tour['slug']) }}" class="dropdown-item flex gap-4 p-3 hover:bg-off-white transition-colors group/item">
                                            <div class="w-20 h-20 rounded-xl overflow-hidden shrink-0">
                                                <img src="{{ $tour['image'] }}" class="w-full h-full object-cover transition-transform group-hover/item:scale-110" alt="">
                                            </div>
                                            <div class="flex flex-col justify-center">
                                                <h4 class="text-sm font-bold text-primary-green group-hover/item:text-gold transition-colors">{{ $tour['name'] }}</h4>
                                                <span class="text-[10px] text-gray uppercase tracking-widest mt-1">{{ $tour['duration_days'] }} Days</span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </li>
                    
                    <li><a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">Our Story</a></li>
                </ul>
                
                <a href="{{ route('booking') }}" class="btn-cta">Design Your Trip</a>
            </div>

            <button class="lg:hidden text-white text-2xl" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    {{-- Mobile Nav Fullscreen --}}
    <div id="mobileMenuOverlay" class="fixed inset-0 bg-primary-green z-[2000] translate-x-full transition-transform duration-500 lg:hidden">
        <div class="container py-8 h-full flex flex-col">
            <div class="flex justify-between items-center mb-16">
                <img src="{{ asset('lau-adventuress.png') }}" alt="" class="h-12 w-auto">
                <button id="closeMobileMenu" class="text-white text-3xl">&times;</button>
            </div>
            
            <ul class="flex flex-col gap-8">
                <li><a href="{{ route('home') }}" class="text-3xl font-heading text-white uppercase font-black">Home</a></li>
                <li><a href="{{ route('safaris') }}" class="text-3xl font-heading text-white uppercase font-black">Safaris</a></li>
                <li><a href="{{ route('tours.index') }}" class="text-3xl font-heading text-white uppercase font-black">Expeditions</a></li>
                <li><a href="{{ route('about') }}" class="text-3xl font-heading text-white uppercase font-black">Our Story</a></li>
                <li><a href="{{ route('contact') }}" class="text-3xl font-heading text-white uppercase font-black">Contact</a></li>
            </ul>
            
            <div class="mt-auto pb-12">
                <a href="{{ route('booking') }}" class="block text-center py-5 bg-gold text-white font-black uppercase tracking-widest rounded-xl">Start Your Experience</a>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const header = document.getElementById('mainHeader');
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileOverlay = document.getElementById('mobileMenuOverlay');
        const closeMobileBtn = document.getElementById('closeMobileMenu');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('sticky');
                header.classList.add('bg-white/90', 'backdrop-blur-xl', 'shadow-2xl');
                header.classList.remove('text-white');
            } else {
                header.classList.remove('sticky');
                header.classList.remove('bg-white/90', 'backdrop-blur-xl', 'shadow-2xl');
            }
        });

        mobileMenuBtn.addEventListener('click', () => {
            mobileOverlay.classList.remove('translate-x-full');
        });

        closeMobileBtn.addEventListener('click', () => {
            mobileOverlay.classList.add('translate-x-full');
        });
    });
</script>

<style>
@import url('resources/css/header.css');
</style>
