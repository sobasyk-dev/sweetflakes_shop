<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sweetflakes | Premium Menu</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=Inter:wght@400;500;600;800&display=swap" rel="stylesheet">

    <x-tailwind />

    <style>
        /* Luxury Tab States */
        .pill-active {
            background: #f6efe8 !important; /* Cream */
            color: #1a120b !important; /* Cocoa 900 */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
            border-color: transparent !important;
        }

        .pill-hot-active {
            background: #ef4444 !important; /* Premium Red */
            color: #ffffff !important;
            box-shadow: 0 10px 20px rgba(239, 68, 68, 0.2);
            transform: translateY(-2px);
            border-color: transparent !important;
        }
        
        #categoryTabs button:not(.pill-active):not(.pill-hot-active) {
            background: rgba(36, 26, 18, 0.4);
            color: rgba(246, 239, 232, 0.5);
        }

        /* Smooth Section Transitions */
        .category-section { 
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); 
        }
        .hidden-section { 
            display: none !important; 
            opacity: 0;
            transform: translateY(10px);
        }
        
        .scrollbar-hide::-webkit-scrollbar { display: none; }

        /* Modal Animation Control */
        #modalContainer {
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        #modalContainer.show {
            opacity: 1 !important;
            transform: scale(1) translateY(0) !important;
        }
    </style>
</head>

<body class="min-h-screen text-cream font-sans bg-cocoa-950 selection:bg-caramel-500/30">
  
    <x-loader text="Preparing Sweetness..." />
    <x-cs_header />
    <x-alerts />

    <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] rounded-full bg-caramel-500/10 blur-[120px]"></div>
        <div class="absolute bottom-[10%] -right-[5%] w-[30%] h-[30%] rounded-full bg-cocoa-800/20 blur-[100px]"></div>
    </div>

    <main class="mx-auto max-w-5xl px-4 py-8">
        
        <div class="text-center mb-6">
            <span class="text-[10px] uppercase tracking-[0.6em] text-caramel-500 font-bold">Artisan Patisserie</span>
            <h1 class="font-serif text-4xl md:text-5xl italic text-cream mt-2">Le Menu</h1>
            <div class="flex justify-center mt-4">
                <div class="h-1 w-12 bg-gradient-to-r from-transparent via-caramel-500 to-transparent"></div>
            </div>
        </div>

        @php
            $activeCategories = $categories->filter(fn($c) => $c->is_active && $c->products->where('is_active', true)->count() > 0)
                                          ->sortByDesc(fn($c) => (Str::lower($c->name) == 'hot seller' || $c->slug == 'hot-seller'));
        @endphp

        @if($activeCategories->isEmpty())
            <div class="py-20 text-center bg-cocoa-900/40 rounded-[3rem] border border-white/5 backdrop-blur-sm">
                <h3 class="text-xl font-serif italic text-caramel-500 font-bold mb-2">The kitchen is resting</h3>
                <p class="text-cream/40 text-[10px] uppercase tracking-widest">Check back for fresh bakes later</p>
            </div>
        @else
            <nav class="sticky top-20 z-40 mb-4">
                <div class="flex gap-3 overflow-x-auto scrollbar-hide px-2 -mx-2 justify-start md:justify-center" id="categoryTabs">
                    @foreach($activeCategories as $category)
                        @php $isHot = (Str::lower($category->name) == 'hot seller' || $category->slug == 'hot-seller'); @endphp
                        <button class="whitespace-nowrap px-8 py-3 rounded-full text-[11px] uppercase tracking-[0.2em] font-black transition-all duration-500 backdrop-blur-md border border-white/10 shadow-lg" 
                                data-category="{{ $category->slug }}"
                                @if($isHot) id="hotSellerTab" @endif>
                            {{ $isHot ? '🔥 ' : '' }}{{ $category->name }}
                        </button>
                    @endforeach
                </div>
            </nav>

            <div id="productGridContainer">
                @foreach($activeCategories as $category)
                    <div class="category-section hidden-section" data-section="{{ $category->slug }}">
                        <div class="flex items-center mb-4 px-2">
                            <h4 class="uppercase font-serif text-[10px] tracking-[0.4em] text-caramel-500/70 font-bold">{{ $category->name }}</h4>
                            <div class="h-px flex-1 bg-gradient-to-r from-caramel-500/30 to-transparent ml-6"></div>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @foreach($category->products->where('is_active', true) as $product)
                                <div class="product-card group bg-cocoa-900/40 rounded-[2.5rem] p-4 border border-cream/30 cursor-pointer hover:border-caramel-500/30 transition-all duration-700" 
                                    data-product-id="{{ $product->id }}"
                                    data-product-name="{{ $product->name }}"
                                    data-product-image="{{ $product->image ? asset('assets/'.$product->image) : asset('assets/placeholder.jpg') }}"
                                    data-product-variants="{{ json_encode($product->variants->where('is_active', true)->values()) }}"
                                    onclick="handleCardClick(this)">
                                    
                                    <div class="flex gap-5 items-center">
                                        <div class="relative w-28 h-28 shrink-0 overflow-hidden rounded-3xl shadow-2xl bg-cocoa-950">
                                            <img src="{{ $product->image ? asset('assets/'.$product->image) : asset('assets/placeholder.jpg') }}" 
                                                 class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-1000">
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-serif text-lg text-cream group-hover:text-caramel-400 transition-colors truncate uppercase">{{ $product->name }}</h4>
                                            <p class="text-[10px] text-caramel-500/80 font-bold mt-1 uppercase tracking-widest">
                                                From RM{{ number_format($product->variants->where('is_active', true)->min('price') ?? 0, 2) }}
                                            </p>
                                            <div class="mt-3 inline-flex items-center justify-center h-8 w-8 rounded-full border border-white/10 group-hover:bg-caramel-500 group-hover:border-caramel-500 transition-all">
                                                <span class="text-xs group-hover:text-cocoa-900">→</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>

    <div id="productDetailsModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-cocoa-800/70 backdrop-blur-md" onclick="closeModal()"></div>
        <div class="relative w-full max-w-lg scale-90 opacity-0 bg-cocoa-900 rounded-[3rem] overflow-hidden shadow-2xl border border-white/10" id="modalContainer">
            <button onclick="closeModal()" class="absolute top-6 right-6 z-10 h-10 w-10 flex items-center justify-center rounded-full bg-cream text-cocoa-900 shadow-xl">✕</button>
            
            <form id="productForm" action="{{ route('customer.cs_cart_store') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" id="hiddenProductId">

                <div class="p-8 pb-0"> {{-- Increased padding, removed bottom padding --}}
                    <div class="aspect-square w-2/3 mx-auto overflow-hidden rounded-[2rem] shadow-2xl border border-white/5">
                        <img id="modalImage" src="" class="h-full w-full object-cover">
                    </div>
                </div>

                <div class="px-8 pb-10 pt-2 text-center">
                    <h2 id="modalName" class="font-serif text-3xl italic text-cream tracking-tight"></h2>
                    <div id="totalPrice" class="mt-2 inline-block px-6 py-2 bg-caramel-500 rounded-2xl font-black text-cocoa-950 text-lg shadow-lg shadow-caramel-500/20">
                        RM 0.00
                    </div>
                    
                    <div class="my-8 h-px w-full bg-gradient-to-r from-transparent via-white/10 to-transparent"></div>

                    <div class="text-left mb-8">
                        <span class="text-[10px] uppercase tracking-[0.3em] text-caramel-500 font-black mb-4 block text-center">Select your treat</span>
                        <div id="variantOptions" class="space-y-3 max-h-[180px] overflow-y-auto pr-2 scrollbar-hide"></div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="flex items-center bg-white/5 border border-white/10 rounded-2xl p-1.5">
                            <button type="button" onclick="updateQuantity(-1)" class="h-12 w-12 text-cream text-xl font-bold hover:bg-white/5 rounded-xl transition-colors">−</button>
                            <input id="quantityInput" name="quantity" type="number" value="1" readonly class="w-12 bg-transparent text-center font-black text-cream outline-none text-lg">
                            <button type="button" onclick="updateQuantity(1)" class="h-12 w-12 rounded-xl bg-cream text-cocoa-900 text-xl font-bold shadow-lg">+</button>
                        </div>
                        <button type="submit" class="flex-1 rounded-[1rem] bg-caramel-500 py-5 px-2 text-xs font-black uppercase tracking-widest text-cocoa-900 shadow-2xl shadow-caramel-500/30 hover:bg-white hover:text-cocoa-900 transition-all active:scale-95">
                            Add to Basket
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($cart && $cart->items->count() > 0)
    <div class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 w-full max-w-xs px-4">
        <a href="{{ route('customer.cs_cart') }}" 
           class="flex items-center justify-between bg-caramel-500 p-2 pl-6 pr-2 rounded-full shadow-[0_20px_40px_rgba(0,0,0,0.5)] hover:scale-105 active:scale-95 transition-all duration-500">
            <span class="text-[11px] font-black uppercase tracking-widest text-cocoa-900">View Basket</span>
            <div class="flex items-center gap-3 bg-cocoa-900 rounded-full px-5 py-2.5 shadow-xl">
                <span class="text-xs font-black text-caramel-500">RM {{ number_format($cart->items->sum(fn($i) => $i->quantity * $i->unit_price), 2) }}</span>
                <span class="text-sm">🥞</span>
            </div>
        </a>
    </div>
    @endif

    <x-footer />

    <script>
        const tabs = document.querySelectorAll("#categoryTabs button");
        const sections = document.querySelectorAll(".category-section");

        function filterMenu(slug, tabElement) {
            tabs.forEach(t => t.classList.remove('pill-active', 'pill-hot-active'));
            if (tabElement.id === 'hotSellerTab') tabElement.classList.add('pill-hot-active');
            else tabElement.classList.add('pill-active');
            
            sections.forEach(section => {
                if (section.dataset.section === slug) {
                    section.classList.remove('hidden-section');
                    requestAnimationFrame(() => section.style.opacity = "1");
                } else {
                    section.classList.add('hidden-section');
                    section.style.opacity = "0";
                }
            });
            // Center the active tab on mobile
            tabElement.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
        }

        tabs.forEach(tab => {
            tab.onclick = () => filterMenu(tab.dataset.category, tab);
        });

        let selectedVariantPrice = 0; // Ensure this is at the top scope

        function handleCardClick(card) {
            const productId = card.getAttribute('data-product-id');
            const name = card.getAttribute('data-product-name');
            const image = card.getAttribute('data-product-image');
            const variants = JSON.parse(card.getAttribute('data-product-variants'));
            
            if (!variants || variants.length === 0) {
                console.error("No variants found for this product");
                return;
            }

            document.getElementById('hiddenProductId').value = productId;
            document.getElementById('modalName').innerText = name;
            document.getElementById('modalImage').src = image;
            
            const container = document.getElementById('variantOptions');
            container.innerHTML = '';
            
            // FIX 1: Set the initial price explicitly from the first variant
            selectedVariantPrice = parseFloat(variants[0].price);
            
            variants.forEach((v, i) => {
                container.innerHTML += `
                    <label class="group flex items-center justify-between p-4 rounded-2xl border border-white/5 bg-white/5 cursor-pointer hover:bg-white/10 transition-all">
                        <div class="flex items-center gap-4">
                            <input type="radio" name="variant_id" value="${v.id}" ${i === 0 ? 'checked' : ''} 
                                class="w-4 h-4 accent-caramel-500"
                                onchange="updateSelectedPrice(${v.price})">
                            <span class="text-sm font-medium text-cream/90">${v.name}</span>
                        </div>
                        <span class="text-xs font-black text-caramel-500">RM ${parseFloat(v.price).toFixed(2)}</span>
                    </label>`;
            });

            document.getElementById("quantityInput").value = 1;
            updatePrice(); // Trigger display update
            
            const modal = document.getElementById('productDetailsModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => document.getElementById('modalContainer').classList.add('show'), 10);
        }

        // FIX 2: Create a dedicated function for the radio button change
        function updateSelectedPrice(price) {
            selectedVariantPrice = parseFloat(price);
            updatePrice();
        }

        function updatePrice() {
            const qtyInput = document.getElementById("quantityInput");
            const qty = parseInt(qtyInput.value) || 1; 
            const total = selectedVariantPrice * qty;
            
            // FIX 3: Ensure we are targeting the right ID
            document.getElementById("totalPrice").innerText = `RM ${total.toFixed(2)}`;
        }

        function updateQuantity(change) {
            const input = document.getElementById("quantityInput");
            let val = parseInt(input.value) + change;
            if (val >= 1) { input.value = val; updatePrice(); }
        }

        function closeModal() {
            document.getElementById('modalContainer').classList.remove('show');
            setTimeout(() => {
                document.getElementById('productDetailsModal').classList.add('hidden');
                document.getElementById('productDetailsModal').classList.remove('flex');
            }, 400);
        }

        window.onload = () => {
            if(tabs.length > 0) filterMenu(tabs[0].dataset.category, tabs[0]);
        };
    </script>
</body>
</html>