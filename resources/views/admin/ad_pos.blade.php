<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sweetflakes | Mobile POS</title>
    <x-tailwind />
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .glass-card { background: rgba(36, 26, 18, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        
        @media (min-width: 1024px) {
            .desktop-sticky {
                position: sticky;
                top: 2rem;
                max-height: calc(100vh - 4rem);
            }
        }
        
        .payment-tab svg { color: currentColor; }

        .active-tab {
            background: #d2a679 !important;
            color: #0d0a09 !important;
            border: 1px solid rgba(210, 166, 121, 0.5);
            transform: translateY(-1px);
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-cocoa-950 text-cream overflow-x-hidden selection:bg-caramel-500/30" x-data="posSystem()">
    <x-ad_header />

    <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[60%] h-[40%] rounded-full bg-caramel-500/10 blur-[120px]"></div>
        <div class="absolute bottom-[10%] -right-[5%] w-[50%] h-[30%] rounded-full bg-caramel-500/5 blur-[100px]"></div>
    </div>

    <main class="max-w-[1600px] mx-auto p-4 md:p-8">
        <div class="mb-6">
            <x-ad_pill_link title1="Point of" title2="Sales System" />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <section class="order-2 lg:order-1 lg:col-span-7 xl:col-span-8">
                
                <div class="flex items-center gap-2 overflow-x-auto scrollbar-hide pb-6">
                    <button @click="tab = 'All'" 
                        :class="tab === 'All' ? 'bg-caramel-500 text-cocoa-950 font-black' : 'text-white/40 border border-white/5'"
                        class="glass-card flex-shrink-0 px-8 py-3 rounded-full text-[10px] uppercase tracking-[0.2em] transition-all">
                        All
                    </button>
                    @foreach($categories as $catName)
                    <button @click="tab = '{{ $catName }}'" 
                        :class="tab === '{{ $catName }}' ? 'bg-caramel-500 text-cocoa-950 font-black shadow-lg shadow-caramel-500/20' : 'text-white/40 font-bold border border-white/5'"
                        class="glass-card flex-shrink-0 px-8 py-3 rounded-full text-[10px] uppercase tracking-[0.2em] transition-all duration-300">
                        {{ $catName }}
                    </button>
                    @endforeach
                </div>

                <div class="mb-8 relative">
                    <input type="text" x-model="search" placeholder="Search product name..." 
                        class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-8 text-sm text-cream focus:border-caramel-500/50 outline-none transition-all placeholder:text-white/20">
                    <div class="absolute right-7 top-1/2 -translate-y-1/2 text-white/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($products as $product)
                        @php 
                            $pCat = is_array($product->category) || is_object($product->category) 
                                    ? ($product->category['name'] ?? $product->category->name ?? 'Uncategorized') 
                                    : $product->category;
                        @endphp

                        @foreach($product->variants as $variant)
                        <template x-if="(tab === 'All' || tab === '{{ $pCat }}') && '{{ strtolower($product->name . ' ' . $variant->name) }}'.includes(search.toLowerCase())">
                            <button 
                                @click="addToCart({{ $variant->id }}, '{{ $product->name }}', '{{ $variant->name }}', {{ $variant->price }}, {{ $variant->stock }})"
                                :disabled="{{ (!$variant->is_active || $variant->stock <= 0) ? 'true' : 'false' }}"
                                class="glass-card relative p-5 rounded-[2rem] text-center transition-all group
                                {{ (!$variant->is_active || $variant->stock <= 0) ? 'opacity-30 grayscale cursor-not-allowed' : 'active:scale-95 hover:border-caramel-500/40' }}">
                                
                                <span class="absolute top-4 right-5 text-[8px] font-black {{ $variant->stock < 5 ? 'text-red-400' : 'text-white/10' }}">
                                    {{ $variant->stock }} UNIT
                                </span>

                                <h4 class="font-serif italic text-base mt-2 leading-tight group-hover:text-caramel-400 transition-colors">{{ $product->name }}</h4>
                                <p class="text-caramel-500 text-[9px] uppercase font-black tracking-widest mt-1">{{ $variant->name }}</p>
                                <div class="mt-4 text-base font-black text-cream">RM {{ number_format($variant->price, 2) }}</div>
                                
                                @if(!$variant->is_active || $variant->stock <= 0)
                                    <div class="absolute inset-0 bg-cocoa-950/80 rounded-[2rem] flex items-center justify-center">
                                        <span class="bg-red-500 text-white text-[9px] font-black px-3 py-1 rounded-full uppercase">Sold Out</span>
                                    </div>
                                @endif
                            </button>
                        </template>
                        @endforeach
                    @endforeach
                </div>
            </section>

            <section class="order-1 lg:order-2 lg:col-span-5 xl:col-span-4 desktop-sticky flex flex-col glass-card rounded-[2.5rem] p-6 border-t border-white/10 shadow-2xl">
                
                <div class="space-y-2 mb-4">
                    <template x-if="successMessage">
                        <div x-transition class="p-3 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center gap-3">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <p class="text-[11px] text-emerald-200 font-bold" x-text="successMessage"></p>
                        </div>
                    </template>

                    <template x-if="errorMessage">
                        <div x-transition class="p-3 rounded-2xl bg-red-500/10 border border-red-500/20 flex items-center gap-3">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-[11px] text-red-200 font-bold" x-text="errorMessage"></p>
                        </div>
                    </template>
                </div>

                <div class="flex items-center justify-between mb-6">
                    <h2 class="font-serif text-xl italic text-cream">Order Details</h2>
                    <button @click="cart = []" x-show="cart.length > 0" class="text-[9px] uppercase tracking-widest text-red-400 font-bold px-3 py-1 bg-red-400/10 rounded-lg">Clear All</button>
                </div>

                <div class="flex-1 overflow-y-auto space-y-3 mb-6 pr-2 scrollbar-hide max-h-[350px] lg:max-h-none">
                    <template x-if="cart.length === 0">
                        <div class="flex flex-col items-center justify-center py-12 opacity-20">
                            <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <p class="text-[10px] uppercase tracking-widest">Awaiting selection</p>
                        </div>
                    </template>
                    
                    <template x-for="item in cart" :key="item.id">
                        <div class="flex items-center gap-3 bg-white/[0.03] p-4 rounded-2xl border border-white/5">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold truncate text-cream" x-text="item.p_name"></p>
                                <p class="text-caramel-500 text-[8px] font-black uppercase tracking-widest" x-text="item.v_name"></p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button @click="updateQty(item.id, -1)" class="w-7 h-7 rounded-lg bg-white/5 text-cream flex items-center justify-center">-</button>
                                <span class="text-xs font-black w-4 text-center" x-text="item.qty"></span>
                                <button @click="updateQty(item.id, 1)" class="w-7 h-7 rounded-lg bg-caramel-500 text-cocoa-950 flex items-center justify-center">+</button>
                            </div>
                            <p class="text-xs font-black w-16 text-right text-cream" x-text="'RM' + (item.price * item.qty).toFixed(2)"></p>
                        </div>
                    </template>
                </div>

                <div class="mt-auto pt-6 border-t border-white/10">
                    <div class="mb-5">
                        <label class="text-[9px] uppercase tracking-widest text-white/30 font-black block mb-2">Customer Name</label>
                        <input type="text" x-model="customer_name" placeholder="Guest Name (Optional)" 
                            @input="customer_name = $event.target.value.replace(/\b\w/g, l => l.toUpperCase())"
                            :disabled="cart.length === 0 || loading"
                            class="w-full bg-white/5 border border-white/10 rounded-xl py-3 px-4 text-xs text-cream focus:border-caramel-500/50 outline-none transition-all placeholder:text-white/10 disabled:opacity-20">
                    </div>

                    <div class="flex justify-between items-center mb-6">
                        <span class="text-[10px] uppercase tracking-[0.2em] text-white/40 font-black">Grand Total</span>
                        <span class="text-2xl font-black text-caramel-500 tracking-tighter" x-text="'RM ' + total.toFixed(2)"></span>
                    </div>
                    
                    <div class="grid grid-cols-3 bg-white/5 p-1.5 rounded-[2rem] gap-2 mb-6 border border-white/5">
                        <button type="button" @click="payment_method = 'qr'" 
                            :class="payment_method === 'qr' ? 'active-tab' : 'text-cream/30 hover:bg-white/5'"
                            class="group flex flex-col items-center justify-center py-4 rounded-[1.5rem] transition-all duration-300">
                            <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            <span class="text-[9px] font-black uppercase tracking-widest">QR</span>
                        </button>

                        <button type="button" @click="payment_method = 'transfer'" 
                            :class="payment_method === 'transfer' ? 'active-tab' : 'text-cream/30 hover:bg-white/5'"
                            class="group flex flex-col items-center justify-center py-4 rounded-[1.5rem] transition-all duration-300">
                            <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            <span class="text-[9px] font-black uppercase tracking-widest">Bank</span>
                        </button>

                        <button type="button" @click="payment_method = 'cash'" 
                            :class="payment_method === 'cash' ? 'active-tab' : 'text-cream/30 hover:bg-white/5'"
                            class="group flex flex-col items-center justify-center py-4 rounded-[1.5rem] transition-all duration-300">
                            <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span class="text-[9px] font-black uppercase tracking-widest">Cash</span>
                        </button>
                    </div>

                    <button @click="checkout()" 
                        :disabled="cart.length === 0 || loading"
                        class="w-full bg-caramel-500 disabled:bg-white/5 disabled:text-white/20 text-cocoa-950 font-black py-4 rounded-2xl uppercase text-[10px] tracking-[0.2em] transition-all hover:scale-[1.02] active:scale-95 shadow-xl shadow-caramel-500/10">
                        <span x-show="!loading" x-text="'Process ' + payment_method.toUpperCase()"></span>
                        <span x-show="loading" x-cloak>Processing...</span>
                    </button>
                </div>
            </section>
        </div>
    </main>

    <script>
        function posSystem() {
            return {
                errorMessage: null,
                successMessage: null,
                loading: false,
                search: '',
                tab: 'All',
                cart: [],
                customer_name: '',
                payment_method: 'qr',

                get total() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                },

                addToCart(id, p_name, v_name, price, stock) {
                    let found = this.cart.find(i => i.id === id);
                    if (found) {
                        if (found.qty < stock) {
                            found.qty++;
                        } else {
                            this.errorMessage = "Stock limit reached.";
                            setTimeout(() => this.errorMessage = null, 2000);
                        }
                    } else {
                        this.cart.push({ id, p_name, v_name, price, qty: 1, max: stock });
                    }
                },

                updateQty(id, delta) {
                    let found = this.cart.find(i => i.id === id);
                    if (found) {
                        found.qty += delta;
                        if (found.qty <= 0) this.cart = this.cart.filter(i => i.id !== id);
                        if (found.qty > found.max) found.qty = found.max;
                    }
                },

                async checkout() {
                    if (this.cart.length === 0 || this.loading) return;
                    if (!confirm(`Confirm ${this.payment_method.toUpperCase()} payment for RM ${this.total.toFixed(2)}?`)) return;

                    this.loading = true;
                    this.errorMessage = null;
                    this.successMessage = null;
                    
                    try {
                        const response = await fetch("{{ route('admin.ad_pos.checkout') }}", {
                            method: 'POST',
                            headers: { 
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                payment_method: this.payment_method,
                                items: this.cart,
                                total: this.total,
                                customer_name: this.customer_name || 'Walk-in Customer'
                            })
                        });

                        const result = await response.json();

                        if (response.ok && result.success) {
                            this.successMessage = `Order #${result.order_number} Success!`;
                            this.cart = [];
                            this.customer_name = '';
                            setTimeout(() => this.successMessage = null, 5000);
                        } else {
                            this.errorMessage = result.message || "Transaction failed.";
                        }
                    } catch (error) {
                        this.errorMessage = "Server connection error.";
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
</body>
</html>