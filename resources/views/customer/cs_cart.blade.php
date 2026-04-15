<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sweetflakes | Your Basket</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=Inter:wght@400;500;600;800&display=swap" rel="stylesheet">

    <x-tailwind />
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        body { background-color: #0d0a09; }
        
        .glass-vault {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.03), rgba(255, 255, 255, 0.01));
            backdrop-filter: blur(20px);
            border: 1px solid rgba(210, 166, 121, 0.1);
        }

        .active-tab {
            background: #d2a679 !important;
            color: #0d0a09 !important;
            border-color: #d2a679 !important;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(210, 166, 121, 0.2);
        }

        .input-premium {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.3s ease;
        }

        .input-premium:focus {
            border-color: #d2a679;
            background: rgba(255, 255, 255, 0.05);
            outline: none;
        }

        .ambient-glow {
            position: fixed;
            width: 40vw;
            height: 40vw;
            border-radius: 50%;
            filter: blur(100px);
            z-index: -1;
            opacity: 0.1;
        }
    </style>
</head>

<body class="min-h-screen text-cream font-sans overflow-x-hidden">
    <x-cs_header />
    <x-alerts />


    <main class="mx-auto max-w-6xl px-4 sm:px-6 py-8 sm:py-12">
        @php
            $method = session('order_method', 'delivery'); 
            $deliveryFee = ($method === 'pickup') ? 0.00 : 5.00;
            $subtotal = $cartItems->sum(fn($i) => $i->unit_price * $i->quantity);
            $total = $subtotal + $deliveryFee;
            $hasItems = $cartItems->count() > 0;
        @endphp

        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="text-center md:text-left">
                <span class="text-[10px] uppercase tracking-[0.5em] text-caramel-500 font-black">Secure Checkout</span>
                <h1 class="font-serif text-4xl sm:text-6xl text-cream italic mt-2">The Basket</h1>
                <p id="cart-count-text" class="text-[10px] text-cream/30 mt-3 uppercase tracking-[0.3em] font-bold">
                    {{ $cartItems->sum('quantity') }} Items Selected
                </p>
            </div>
            <div class="flex items-center justify-center gap-3 bg-white/5 border border-white/5 px-5 py-3 rounded-2xl backdrop-blur-md">
                <span class="text-[10px] font-bold text-cream/40 uppercase tracking-[0.2em]">Method:</span>
                <span class="text-xs font-black text-caramel-400 uppercase tracking-widest italic">{{ $method }}</span>
            </div>
        </div>

        <div class="grid {{ $hasItems ? 'lg:grid-cols-12' : 'grid-cols-1' }} gap-8 lg:gap-12">
            
            <div class="{{ $hasItems ? 'lg:col-span-7' : '' }} space-y-4 sm:space-y-6">
                @forelse ($cartItems as $item)
                    @php $product = $item->variant->product; @endphp
                    <div id="item-row-{{ $item->id }}" class="group relative flex flex-row items-center gap-4 sm:gap-6 glass-vault p-3 sm:p-5 rounded-[2rem] sm:rounded-[2.5rem] transition-all bg-[#1c1613] border border-caramel-500/20">
                        
                        <div class="relative h-20 w-20 sm:h-36 sm:w-36 flex-shrink-0">
                            <img src="{{ asset('assets/' . $product->image) }}" class="h-full w-full object-cover rounded-[1.5rem] sm:rounded-[2rem] shadow-xl group-hover:scale-105 transition-transform duration-700">
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start">
                                <div class="truncate">
                                    <h3 class="font-serif text-lg sm:text-2xl text-cream italic leading-tight truncate">{{ $product->name }}</h3>
                                    <p class="text-[9px] sm:text-[10px] text-caramel-500/70 font-bold uppercase tracking-widest mt-0.5">{{ $item->variant->name }}</p>
                                </div>
                                <button onclick="removeItem({{ $item->id }})" class="h-6 w-6 sm:h-8 sm:w-8 flex items-center justify-center rounded-full bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all text-[10px]">✕</button>
                            </div>

                            <div class="mt-4 sm:mt-6 flex justify-between items-end">
                                <div class="flex items-center bg-black/40 rounded-xl sm:rounded-2xl p-0.5 sm:p-1 border border-white/5">
                                    <button onclick="updateQty({{ $item->id }}, 'decrease')" class="h-7 w-7 sm:h-9 sm:w-9 text-cream hover:bg-white/10 rounded-lg sm:rounded-xl transition-all">−</button>
                                    <span id="qty-{{ $item->id }}" class="w-7 sm:w-10 text-center font-black text-cream text-xs sm:text-sm">{{ $item->quantity }}</span>
                                    <button onclick="updateQty({{ $item->id }}, 'increase')" class="h-7 w-7 sm:h-9 sm:w-9 bg-caramel-500 text-cocoa-900 rounded-lg sm:rounded-xl font-bold shadow-lg shadow-caramel-500/20">+</button>
                                </div>

                                <p class="font-serif text-lg sm:text-2xl text-cream mr-3">
                                    <span class="text-[10px] font-sans font-bold text-caramel-500 align-top mr-1">RM</span>
                                    <span id="item-total-{{ $item->id }}" class="font-black">{{ number_format($item->unit_price * $item->quantity, 2) }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20 sm:py-32 glass-vault rounded-[3rem] border-dashed border-white/10">
                        <div class="text-5xl sm:text-7xl mb-6 opacity-20">🥞</div>
                        <p class="text-cream/40 italic font-serif text-xl sm:text-2xl">Your basket is waiting...</p>
                        <a href="{{ route('customer.cs_menu') }}" class="mt-8 inline-block bg-caramel-500 text-cocoa-900 px-8 py-3 rounded-full font-black uppercase tracking-widest text-xs hover:scale-105 transition-transform">
                            Explore Menu
                        </a>
                    </div>
                @endforelse
            </div>

            @if($hasItems)
            <div class="lg:col-span-5">
                <div class="sticky top-28 bg-[#1c1613] border border-caramel-500/20 rounded-[2.5rem] sm:rounded-[3.5rem] p-6 sm:p-10 shadow-2xl">
                    <h2 class="font-serif text-2xl sm:text-3xl mb-6 sm:mb-8 font-bold italic text-caramel-400 border-b border-white/5 pb-4">Summary</h2>
                    
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between items-center text-xs">
                            <span class="opacity-50 font-bold uppercase tracking-widest text-[9px]">Subtotal</span>
                            <span class="font-black text-cream" id="summary-subtotal">RM {{ number_format($subtotal, 2) }}</span>
                        </div>

                        <div class="flex justify-between items-center text-xs pb-4 border-b border-white/5">
                            <span class="opacity-50 font-bold uppercase tracking-widest text-[9px]">{{ $method === 'pickup' ? 'Pickup' : 'Delivery Fee' }}</span>
                            <span class="font-black {{ $method === 'pickup' ? 'text-emerald-500' : 'text-cream' }}">
                                {{ $method === 'pickup' ? 'FREE' : '+ RM ' . number_format($deliveryFee, 2) }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-10 text-center">
                        <p class="text-[9px] uppercase font-bold text-caramel-600 tracking-[0.4em] mb-1">Total Amount</p>
                        <div class="text-4xl sm:text-6xl font-black tracking-tighter text-cream" id="display-total">
                            <span class="text-xl sm:text-2xl align-top mr-1 font-medium">RM</span>{{ number_format($total, 2) }}
                        </div>
                    </div>

                    <form action="{{ route('customer.cs_payment.prepare') }}" method="POST">
                        @csrf
                        {{-- Defaulting to full since plan selection is removed --}}
                        <input type="hidden" name="payment_type" value="full">
                        
                        <button type="submit" class="group relative w-full bg-caramel-500 text-cocoa-900 overflow-hidden py-4 sm:py-6 rounded-2xl sm:rounded-3xl font-black uppercase tracking-[0.2em] text-xs sm:text-sm shadow-xl transition-all active:scale-95">
                            <span class="relative z-10">Proceed to Checkout</span>
                            <div class="absolute inset-0 bg-white translate-y-[101%] group-hover:translate-y-0 transition-transform duration-500"></div>
                        </button>
                        
                        <a href="{{ route('customer.cs_menu') }}" class="block w-full text-center mt-6 text-white/20 hover:text-caramel-400 font-black uppercase tracking-widest text-[9px] transition-colors">
                            ← Continue Browsing
                        </a>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </main>

    <script>
        let fullTotal = {{ $total }}; 
        
        function setPaymentMode(mode) {
            const btnFull = document.getElementById('btn-full');
            const btnDep = document.getElementById('btn-deposit');
            const note = document.getElementById('deposit-note');
            const label = document.getElementById('total-label');
            const hiddenInput = document.getElementById('payment_type_input');
            
            hiddenInput.value = mode;
            
            if(mode === 'deposit') {
                btnDep.classList.add('active-payment-btn');
                btnDep.classList.remove('text-white/30');
                btnFull.classList.remove('active-payment-btn');
                btnFull.classList.add('text-white/30');
                note.classList.remove('hidden');
                label.innerText = 'Deposit Payable';
            } else {
                btnFull.classList.add('active-payment-btn');
                btnFull.classList.remove('text-white/30');
                btnDep.classList.remove('active-payment-btn');
                btnDep.classList.add('text-white/30');
                note.classList.add('hidden');
                label.innerText = 'Total Amount';
            }
            refreshDisplay();
        }

        function refreshDisplay() {
            const display = document.getElementById('display-total');
            display.innerHTML = `<span class="text-xl sm:text-2xl align-top mr-1 font-medium">RM</span>${fullTotal.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            })}`;
        }

        async function updateQty(itemId, action) {
            try {
                const response = await fetch(`/customer/cart/update/${itemId}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ action: action })
                });
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById(`qty-${itemId}`).innerText = data.new_quantity;
                    document.getElementById(`item-total-${itemId}`).innerText = data.item_total;
                    document.getElementById('summary-subtotal').innerText = 'RM ' + data.subtotal;
                    
                    // Update global total and UI
                    fullTotal = parseFloat(data.total.replace(/,/g, ''));
                    refreshDisplay();
                    
                    document.getElementById('cart-count-text').innerText = `${data.item_count} Items Selected`;
                }
            } catch (error) { console.error("AJAX Error:", error); }
        }

        async function removeItem(itemId) {
            if(!confirm('Remove this delicacy?')) return;
            const response = await fetch(`/customer/cart/remove/${itemId}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            if (response.ok) window.location.reload(); 
        }
    </script>
</body>
</html>