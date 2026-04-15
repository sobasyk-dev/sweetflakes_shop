<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

@props([
    'brand' => 'Sweetflakes Dessert',
    'brandHref' => '/customer/welcome',
    'logo' => 'assets/sweet.jpg',
    'whatsappNumber' => '60123456789'
])

<header class="sticky top-0 z-50 border-b border-white/10 bg-[#0d0a09] backdrop-blur-xl">
    <div class="ambient-glow top-0 right-0 bg-caramel-400"></div>
    <div class="ambient-glow bottom-0 left-0 bg-cocoa-800"></div>

    <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
        
        <a href="{{ $brandHref }}" class="flex items-center gap-2 group">
            <div class="h-10 w-10 md:h-12 md:w-12 rounded-full border border-caramel-500/30 p-0.5 shadow-lg shadow-caramel-500/20 group-hover:border-caramel-400 transition-all">
                <img src="{{ asset($logo) }}" alt="Logo" class="h-full w-full rounded-full object-cover">
            </div>
            <span class="font-serif text-lg md:text-2xl tracking-tight text-caramel-400 font-bold italic">{{ $brand }}</span>
        </a>

        <div x-data="{ open: false }" class="relative" @click.outside="open = false">
            
            <button
                type="button"
                class="flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-bold text-white/80 hover:bg-white/10 transition-all"
                @click="open = !open"
            >
                <span class="opacity-80">👤</span>
                <span class="hidden md:inline">Customer</span>
                <svg class="w-3 h-3 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-2 scale-95"
                x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 top-full mt-3 z-[130] w-64 rounded-[2rem] bg-[#1a1412] p-6 border border-white/10 shadow-2xl"
                style="display: none;"
            >
                <div class="text-center mb-6 border-b border-white/5 pb-4">
                    <p class="text-[8px] uppercase tracking-[0.3em] text-caramel-500 mb-1 font-bold">Account Settings</p>
                    <h3 class="font-serif text-lg italic text-cream truncate">Customer</h3>
                </div>

                <div class="space-y-4">
                    <a href="{{ route('customer.cs_orders') }}" 
                        class="flex w-full items-center justify-center gap-3 rounded-xl bg-white/5 py-4 text-[10px] font-black uppercase tracking-widest text-cream border border-white/10 hover:bg-caramel-500 hover:text-cocoa-950 transition-all group">
                        <svg class="w-4 h-4 text-caramel-500 group-hover:text-cocoa-950 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Order History
                    </a>
                    <!-- <form method="POST" action="{{ route('customer.logout') }}">
                        @csrf
                        <button type="submit" 
                            class="flex w-full items-center justify-center gap-3 rounded-xl bg-red-500/5 py-4 text-[10px] font-black uppercase tracking-widest text-red-400 border border-red-500/10 hover:bg-red-500 hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Sign Out
                        </button>
                    </form> -->

                    <div class="relative">
                        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-white/5"></div></div>
                        <div class="relative flex justify-center"><span class="bg-[#1a1412] px-2 text-[7px] uppercase tracking-widest text-white/20">Any questions? Contact support:</span></div>
                    </div>

                    <a href="https://wa.me/{{ $whatsappNumber }}?text={{ urlencode('Hi Sweetflakes! I have a question.') }}" 
                        target="_blank"
                        class="flex w-full items-center justify-center gap-3 rounded-xl bg-[#25D366]/10 py-4 text-[10px] font-black uppercase tracking-widest text-[#25D366] border border-[#25D366]/20 hover:bg-[#25D366] hover:text-white transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>