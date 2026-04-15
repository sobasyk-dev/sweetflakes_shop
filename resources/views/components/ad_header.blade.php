<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

@props([
    'brand' => 'Sweetflakes Dessert',
    'brandHref' => route('admin.ad_dashboard'),
    'logo' => 'assets/sweet.jpg'
])

<header class="sticky top-0 z-50 border-b border-white/10 bg-[#0d0a09]/80 backdrop-blur-xl">
    <div class="ambient-glow top-0 right-0 bg-caramel-400/5"></div>
    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
        
        <a href="{{ $brandHref }}" class="flex items-center gap-2 group">
            <div class="h-10 w-10 md:h-12 md:w-12 rounded-full border border-caramel-500/30 p-0.5 shadow-lg shadow-caramel-500/20 group-hover:border-caramel-400 transition-all">
                <img src="{{ asset($logo) }}" alt="Logo" class="h-full w-full rounded-full object-cover">
            </div>
            <div class="flex flex-col">
                <span class="font-serif text-lg md:text-2xl tracking-tight text-caramel-400 font-bold italic">{{ $brand }}</span>
                <span class="text-[8px] uppercase tracking-[0.3em] text-white/40 -mt-1 font-black pl-1">Management Portal</span>
            </div>
        </a>

        <div x-data="{ open: false }" class="relative" @click.outside="open = false">
            <button
                type="button"
                class="flex items-center gap-2 rounded-full border border-caramel-500/20 bg-cocoa-800/50 px-4 py-2 text-sm font-bold text-caramel-400 backdrop-blur-md active:scale-95 transition-all"
                @click="open = !open"
            >
                <span class="opacity-80">⚙️</span>
                <span class="hidden md:inline">{{ Auth::user()->name ?? 'Staff' }}</span>
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
                    <p class="text-[8px] uppercase tracking-[0.3em] text-caramel-500 mb-1 font-bold">Authenticated as</p>
                    <h3 class="font-serif text-lg italic text-cream truncate">
                        {{ Auth::user()->name }}
                    </h3>
                    <p class="text-[8px] text-white/30 uppercase tracking-widest mt-1">{{ Auth::user()->email }}</p>
                </div>

                <div class="space-y-3">

                    <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>

                    <button type="button" 
                            onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();"
                            class="flex w-full items-center justify-center gap-3 rounded-xl bg-red-500/10 py-3 text-[10px] font-black uppercase tracking-widest text-red-400 border border-red-500/20 hover:bg-red-500 hover:text-white transition-all shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Sign Out
                    </button>

                    <button @click="open = false" 
                        class="w-full py-1 text-[8px] font-bold uppercase tracking-[0.4em] text-white/20 hover:text-caramel-500 transition-colors mt-2">
                        Close Menu
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>