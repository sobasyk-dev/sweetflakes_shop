{{-- resources/views/components/loader.blade.php --}}
@props(['text' => 'Baking magic...'])

<div id="loading-screen" {{ $attributes->merge(['class' => 'fixed inset-0 z-[100] flex flex-col items-center justify-center bg-cocoa-950 transition-opacity duration-700']) }}>
    <div class="relative">
        <div class="h-24 w-24 rounded-full border-t-2 border-b-2 border-caramel-500 animate-spin"></div>
        
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="h-16 w-16 rounded-full overflow-hidden border border-caramel-500/30 shadow-[0_0_20px_rgba(210,166,121,0.4)]">
                <img src="{{ asset('assets/sweet.jpg') }}" alt="Logo" class="h-full w-full object-cover">
            </div>
        </div>
    </div>
    
    <div class="mt-6 flex flex-col items-center">
        <span class="font-serif text-2xl text-caramel-400 tracking-widest animate-pulse">Sweetflakes</span>
        <div class="mt-2 h-0.5 w-12 bg-gradient-to-r from-transparent via-caramel-500 to-transparent"></div>
        <p class="mt-4 text-[10px] uppercase tracking-[0.3em] text-cream/40 font-medium">
            {{ $text }}
        </p>
    </div>
</div>

<script>
    window.addEventListener('load', function() {
        const loader = document.getElementById('loading-screen');
        if (loader) {
            setTimeout(() => {
                loader.style.opacity = '0';
                setTimeout(() => loader.remove(), 700);
            }, 700);
        }
    });
</script>