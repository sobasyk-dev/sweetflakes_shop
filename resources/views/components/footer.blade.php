{{-- resources/views/components/trademark-footer.blade.php --}}
<footer {{ $attributes->merge(['class' => 'w-full py-10 mt-auto flex flex-col items-center justify-center border-t border-white/5 bg-cocoa-950/50 backdrop-blur-sm']) }}>
    
    {{-- Decorative Divider (Now optional since we added border-t) --}}
    <div class="w-24 h-[1px] bg-white/5 mb-8"></div>

    <div class="flex flex-col items-center text-center space-y-2">
        <span class="font-serif text-xl tracking-[0.15em] text-caramel-500/80 italic">
            Sweetflakes Dessert
        </span>
        
        <div class="flex items-center gap-3 text-[9px] uppercase tracking-[0.4em] text-cream/30 font-medium">
            <span>Est. 2024</span>
            <span class="w-1 h-1 rounded-full bg-caramel-500/40"></span>
            <span>&copy; {{ date('Y') }} All Rights Reserved</span>
        </div>

        <p class="pt-2 text-[8px] uppercase tracking-[0.6em] text-cream/10">
            Artisan Baking & Cocoa Craft
        </p>
    </div>
</footer>