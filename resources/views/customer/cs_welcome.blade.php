<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sweetflakes | Premium Welcome</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=Inter:wght@400;500;600;800&display=swap" rel="stylesheet">
    
    <x-tailwind />

    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .glass-card {
            background: rgba(36, 26, 18, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        @keyframes subtle-pulse {
            0% { box-shadow: 0 0 0 0 rgba(210, 166, 121, 0.2); }
            70% { box-shadow: 0 0 0 10px rgba(210, 166, 121, 0); }
            100% { box-shadow: 0 0 0 0 rgba(210, 166, 121, 0); }
        }
        .pulse-hover:hover {
            animation: subtle-pulse 2s infinite;
        }
    </style>
</head>

<body class="min-h-screen text-cream font-sans bg-cocoa-950 selection:bg-caramel-500/30">
    
    <x-loader text="Curating your experience..."/>
    <x-cs_header />
    <x-alerts />

    <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] rounded-full bg-caramel-500/10 blur-[120px]"></div>
        <div class="absolute bottom-[10%] -right-[5%] w-[30%] h-[30%] rounded-full bg-cocoa-800/20 blur-[100px]"></div>
    </div>

    <main class="mx-auto max-w-5xl px-6 py-10 md:py-14">
        <header class="mb-8 grid">
            <p class="text-[8px] md:text-[10px] uppercase tracking-[0.5em] text-white/70 font-bold">Welcome back,</p>
            <h1 class="font-serif text-2xl md:text-5xl text-cream">
                <span class="italic text-caramel-400">Customer</span>
            </h1>
        </header>

        <section class="mb-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-serif text-2xl italic text-cream">Chef's Highlights</h2>
                <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent ml-6"></div>
                <span class="text-[9px] uppercase tracking-widest text-cream/30 ml-4 hidden md:block">Scroll to explore</span>
            </div>
            
            <div class="flex gap-5 overflow-x-auto pb-6 scrollbar-hide snap-x select-none">
                <div class="min-w-[300px] md:min-w-[380px] group relative aspect-[16/10] overflow-hidden rounded-[2.5rem] snap-start shadow-2xl">
                    <img src="{{ asset('assets/batik_ind.png') }}" class="h-full w-full object-cover transition-transform duration-1000 group-hover:scale-110" alt="Pudding">
                    <div class="absolute inset-0 bg-gradient-to-t from-cocoa-950 via-cocoa-950/20 to-transparent"></div>
                    <div class="absolute bottom-0 p-8">
                        <span class="inline-block rounded-full bg-red-500 px-4 py-1 text-[9px] font-black uppercase tracking-widest text-white mb-3 shadow-lg shadow-red-500/40">Best Seller</span>
                        <h3 class="text-2xl font-serif text-cream italic">Kek Batik Indulgence</h3>
                    </div>
                </div>
                
                <div class="min-w-[300px] md:min-w-[380px] group relative aspect-[16/10] overflow-hidden rounded-[2.5rem] snap-start shadow-2xl">
                    <img src="{{ asset('assets/batik_biscoff.png') }}" class="h-full w-full object-cover transition-transform duration-1000 group-hover:scale-110" alt="Cheesekut">
                    <div class="absolute inset-0 bg-gradient-to-t from-cocoa-950 via-cocoa-950/20 to-transparent"></div>
                    <div class="absolute bottom-0 p-8">
                        <span class="inline-block rounded-full bg-caramel-500 px-4 py-1 text-[9px] font-black uppercase tracking-widest text-cocoa-950 mb-3 shadow-lg shadow-caramel-500/40">New Taste</span>
                        <h3 class="text-2xl font-serif text-cream italic">Cheesekut Biscoff</h3>
                    </div>
                </div>
                
                <div class="min-w-[300px] md:min-w-[380px] group relative aspect-[16/10] overflow-hidden rounded-[2.5rem] snap-start shadow-2xl">
                    <img src="{{ asset('assets/dessert3.png') }}" class="h-full w-full object-cover transition-transform duration-1000 group-hover:scale-110" alt="Thai Tea">
                    <div class="absolute inset-0 bg-gradient-to-t from-cocoa-950 via-cocoa-950/20 to-transparent"></div>
                    <div class="absolute bottom-0 p-8">
                        <span class="inline-block rounded-full bg-white/10 backdrop-blur-md border border-white/20 px-4 py-1 text-[9px] font-black uppercase tracking-widest text-cream mb-3">Seasonal</span>
                        <h3 class="text-2xl font-serif text-cream italic">Authentic Thai Tea</h3>
                    </div>
                </div>
            </div>
        </section>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6">
            <a href="{{ route('customer.set_method', ['method' => 'delivery']) }}" 
               class="pulse-hover group flex items-center justify-between overflow-hidden rounded-[2rem] bg-cocoa-800/40 p border border-white/5 transition-all hover:bg-caramel-500">
                <div class="flex items-center gap-5 p-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/5 group-hover:bg-cocoa-950/20 transition-all text-2xl">
                        🚚
                    </div>
                    <div class="text-left">
                        <p class="text-md font-black text-cream group-hover:text-cocoa-950 transition-colors">COD Delivery</p>
                        <p class="text-[10px] uppercase tracking-widest text-caramel-500 group-hover:text-cocoa-950/60 transition-colors mt-1 font-bold">Doorstep Service</p>
                    </div>
                </div>
                <div class="pr-8 opacity-0 group-hover:opacity-100 group-hover:translate-x-2 transition-all">
                    <span class="text-cocoa-950 text-xl font-bold">→</span>
                </div>
            </a>

            <a href="{{ route('customer.set_method', ['method' => 'pickup']) }}" 
               class="pulse-hover group flex items-center justify-between overflow-hidden rounded-[2rem] bg-cocoa-800/40 p border border-white/5 transition-all hover:bg-cream">
                <div class="flex items-center gap-5 p-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/5 group-hover:bg-cocoa-950/10 transition-all text-2xl">
                        🛍️
                    </div>
                    <div class="text-left">
                        <p class="text-md font-black text-cream group-hover:text-cocoa-900 transition-colors">Self Pickup</p>
                        <p class="text-[10px] uppercase tracking-widest text-caramel-500 group-hover:text-cocoa-900/60 transition-colors mt-1 font-bold">Collect in Store</p>
                    </div>
                </div>
                <div class="pr-8 opacity-0 group-hover:opacity-100 group-hover:translate-x-2 transition-all">
                    <span class="text-cocoa-900 text-xl font-bold">→</span>
                </div>
            </a>
        </div>

    </main>
    <x-footer />

    </body>
</html>