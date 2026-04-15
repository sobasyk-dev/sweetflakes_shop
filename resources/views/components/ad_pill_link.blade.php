@props([
    'title1' => 'Business',
    'title2' => 'Intelligence',
])

@php
    // Define the navigation items to keep the HTML clean
    $navItems = [
        ['name' => 'Dashboard', 'route' => 'admin.ad_dashboard'],
        ['name' => 'POS System', 'route' => 'admin.ad_pos'],
        ['name' => 'Orders', 'route' => 'admin.ad_orders'], // Ensure this route exists
        ['name' => 'Inventory', 'route' => 'admin.ad_inventory'],
    ];

    // CSS Classes for states
    $baseClass = "glass-card px-3 py-1.5 rounded-full text-[8px] uppercase tracking-widest transition-all border";
    $activeClass = "font-black bg-caramel-500 text-cocoa-950 shadow-lg shadow-caramel-500/20 border-caramel-400/20";
    $inactiveClass = "font-bold text-white/40 border-transparent hover:text-caramel-400 hover:border-caramel-500/30";
@endphp

<header class="mb-4 md:mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
    <div class="text-center md:text-left">
        <nav class="flex flex-wrap justify-center md:justify-start gap-2 mb-6">
            @foreach($navItems as $item)
                @php 
                    $isActive = request()->routeIs($item['route']); 
                @endphp
                
                <a href="{{ route($item['route']) }}" 
                   class="{{ $baseClass }} {{ $isActive ? $activeClass : $inactiveClass }}">
                    {{ $item['name'] }}
                </a>
            @endforeach
        </nav>

        <h1 class="font-serif text-3xl md:text-5xl text-cream">
            {{ $title1 }} <span class="italic text-caramel-400">{{ $title2 }}</span>
        </h1>
    </div>
</header>