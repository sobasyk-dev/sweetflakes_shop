<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sweetflakes Dessert | Artisan Boutique</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=Inter:wght@400;500;600;900&display=swap" rel="stylesheet">
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        serif: ['"Playfair Display"', 'serif'],
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        cocoa: { 950: "#0f0a07", 900: "#1a120b", 800: "#2a1b12", 700: "#3a271a" },
                        caramel: { 400: "#e2bc95", 500: "#d2a679", 600: "#b08968" },
                        cream: "#f6efe8"
                    },
                    animation: {
                        'marquee': 'marquee 30s linear infinite',
                        'marquee2': 'marquee2 30s linear infinite',
                    },
                    keyframes: {
                        marquee: { '0%': { transform: 'translateX(0%)' }, '100%': { transform: 'translateX(-100%)' } },
                        marquee2: { '0%': { transform: 'translateX(100%)' }, '100%': { transform: 'translateX(0%)' } },
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .glass-panel { background: rgba(26, 18, 11, 0.4); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.05); }
    </style>
</head>

<body class="min-h-screen text-cream font-sans bg-cocoa-950 selection:bg-caramel-500/30 overflow-x-hidden">
    
    <x-loader/>

    <main>
        <section class="relative pt-20 pb-16 md:pt-48 md:pb-24 px-6 text-center overflow-hidden">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-3xl h-96 bg-caramel-500/10 blur-[120px] -z-10"></div>

            <div class="mx-auto max-w-4xl flex flex-col items-center justify-center"> <div class="mb-10 group cursor-default flex flex-col items-center"> 
        
                <div class="relative h-24 w-24 md:h-32 md:w-32 rounded-full border-2 border-caramel-500/20 p-1.5 shadow-2xl transition-all duration-1000 group-hover:border-caramel-500/50 mx-auto">
                    <img src="{{ asset('assets/sweet.jpg') }}" alt="Logo" class="h-full w-full rounded-full object-cover grayscale-[0.2] group-hover:grayscale-0 transition-all duration-700 group-hover:rotate-[360deg]">
                </div>

                <div class="mt-6 text-center">
                    <h1 class="font-serif text-3xl md:text-5xl tracking-[0.15em] text-caramel-400 font-bold italic block">Sweetflakes</h1>
                    <p class="text-[9px] md:text-[11px] uppercase tracking-[0.5em] text-cream/30 mt-2">Boutique Patisserie</p>
                    <div class="h-px w-10 bg-caramel-500/20 mx-auto mt-4"></div>
                </div>
            </div>

            <div class="relative z-10 max-w-3xl text-center"> <h2 class="font-serif italic text-5xl md:text-8xl text-cream leading-[1.1] mb-8">
                    Artisan <span class="text-caramel-400">Delights</span> <br> 
                    <span class="text-3xl md:text-6xl tracking-tight opacity-90">For Every Craving</span>
                </h2>
                
                <p class="text-cream/50 text-sm md:text-base tracking-widest uppercase mb-12 max-w-md mx-auto leading-relaxed">
                    Handcrafted with premium cocoa & the finest seasonal ingredients.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center gap-5 justify-center">
                    <a href="{{ route('customer.cs_welcome') }}" class="w-full sm:w-auto px-12 py-5 bg-caramel-500 text-cocoa-900 font-black rounded-full shadow-xl shadow-caramel-500/20 hover:bg-caramel-400 active:scale-95 transition-all uppercase tracking-widest text-xs flex items-center gap-2">
                        Enter Boutique 
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
        </section>

        <section class="py-12 border-y border-white/5 bg-white/[0.01]">
            <div class="flex items-center gap-4 px-6 mb-8 justify-center">
                <span class="h-px w-12 bg-caramel-500/40"></span>
                <h3 class="font-serif italic text-caramel-400 text-lg">Current Favourites</h3>
                <span class="h-px w-12 bg-caramel-500/40"></span>
            </div>

            <div class="relative flex overflow-x-hidden group select-none">
                <div class="flex animate-marquee whitespace-nowrap gap-6 py-4 group-hover:[animation-play-state:paused]">
                    @foreach(['Biscoff Cheesecake', 'Dark Cocoa Tart', 'Salted Caramel Macaron', 'Pistachio Eclair', 'Red Velvet Dream'] as $item)
                        <div class="w-56 md:w-72 flex-shrink-0 glass-panel p-5 rounded-[2rem] hover:border-caramel-500/30 transition-all duration-500">
                            <div class="h-40 w-full rounded-2xl bg-cocoa-800 mb-4 overflow-hidden relative">
                                <img src="{{ asset('assets/sweet.jpg') }}" class="w-full h-full object-cover opacity-60 group-hover:scale-110 transition-transform duration-700">
                                <span class="absolute top-3 right-3 bg-caramel-500 text-cocoa-950 text-[8px] font-black px-2 py-1 rounded-full uppercase tracking-tighter">Hot</span>
                            </div>
                            <h4 class="font-serif italic text-cream text-xl mb-1">{{ $item }}</h4>
                            <p class="text-[9px] text-caramel-500/60 uppercase tracking-[0.2em] font-bold">Limited Edition</p>
                        </div>
                    @endforeach
                </div>
                <div class="flex absolute top-0 animate-marquee2 whitespace-nowrap gap-6 py-4 group-hover:[animation-play-state:paused]">
                    @foreach(['Biscoff Cheesecake', 'Dark Cocoa Tart', 'Salted Caramel Macaron', 'Pistachio Eclair', 'Red Velvet Dream'] as $item)
                        <div class="w-56 md:w-72 flex-shrink-0 glass-panel p-5 rounded-[2rem] hover:border-caramel-500/30 transition-all duration-500">
                            <div class="h-40 w-full rounded-2xl bg-cocoa-800 mb-4 overflow-hidden relative">
                                <img src="{{ asset('assets/sweet.jpg') }}" class="w-full h-full object-cover opacity-60">
                                <span class="absolute top-3 right-3 bg-caramel-500 text-cocoa-950 text-[8px] font-black px-2 py-1 rounded-full uppercase tracking-tighter">Hot</span>
                            </div>
                            <h4 class="font-serif italic text-cream text-xl mb-1">{{ $item }}</h4>
                            <p class="text-[9px] text-caramel-500/60 uppercase tracking-[0.2em] font-bold">Limited Edition</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </main>

    <x-footer />

    <script>
        // Smooth Loader Logic
        window.addEventListener('load', () => {
            const loader = document.getElementById('loading-screen');
            if(loader) {
                loader.style.transition = 'opacity 0.8s ease-out';
                loader.style.opacity = '0';
                setTimeout(() => loader.remove(), 800);
            }
        });
    </script>
</body>
</html>