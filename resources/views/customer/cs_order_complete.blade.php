<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #{{ $order->order_number }} | Sweetflakes</title>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cocoa: { 950: "#0d0a09", 900: "#1a1512", 800: "#261e1a" },
                        caramel: { 400: "#d2a679", 500: "#b08968" },
                        cream: "#f5f5f5"
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,700&family=Inter:wght@400;600;800&display=swap');
        body { background-color: #0d0a09; font-family: 'Inter', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }
        
        .receipt-card {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.03), rgba(255, 255, 255, 0.01));
            backdrop-filter: blur(20px);
            border: 1px solid rgba(210, 166, 121, 0.1);
        }

        @keyframes subtle-float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }
        .float-icon { animation: subtle-float 4s ease-in-out infinite; }
        
        .dotted-line { border-top: 2px dotted rgba(210, 166, 121, 0.2); }
    </style>
</head>
<body x-data="{ previewImage: null }" class="min-h-screen text-cream flex items-center justify-center p-4">
    
    <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[60%] md:w-[40%] h-[40%] rounded-full bg-caramel-500/10 blur-[80px] md:blur-[120px]"></div>
        <div class="absolute bottom-[10%] -right-[5%] w-[50%] md:w-[30%] h-[30%] rounded-full bg-caramel-500/5 blur-[80px] md:blur-[100px]"></div>
    </div>

    <main class="w-full max-w-xl py-10 px-4 mx-auto">
        <div class="text-center mb-8">
            <div class="float-icon mb-4 flex justify-center">
                <div class="h-16 w-16 rounded-full bg-caramel-400/10 border border-caramel-400/20 flex items-center justify-center shadow-[0_0_30px_rgba(210,166,121,0.1)]">
                    <span class="text-3xl text-caramel-400">✨</span>
                </div>
            </div>
            <h1 class="font-serif text-3xl italic text-cream leading-tight">Sweet Success!</h1>
            <p class="text-caramel-400/60 uppercase tracking-[0.4em] text-[8px] font-black mt-2">Order Confirmed</p>
        </div>

        <div class="mb-6 bg-emerald-500/5 border border-emerald-500/10 rounded-3xl p-5 flex items-start gap-4">
            <div class="h-10 w-10 rounded-2xl bg-emerald-500/10 flex items-center justify-center shrink-0">
                <span class="text-xl">🔑</span>
            </div>
            <div>
                <h4 class="text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-1">Your Tracking Key</h4>
                <p class="text-[11px] text-cream/60 leading-relaxed">
                    Keep your <span class="text-cream font-bold">Order ID</span> and <span class="text-cream font-bold">Mobile Number</span> safe. Use them together to track your treats in the history tab.
                </p>
            </div>
        </div>

        <div class="receipt-card rounded-[2.5rem] overflow-hidden shadow-2xl bg-[#1a1614] border border-white/5">
            <div class="px-6 py-4 bg-white/[0.03] flex justify-between items-center border-b border-white/5">
                @php
                    $statusColors = [
                        'pending' => 'text-amber-500',
                        'processing' => 'text-blue-500',
                        'completed' => 'text-emerald-500',
                        'cancelled' => 'text-red-500',
                    ];
                    $color = $statusColors[$order->status] ?? 'text-cream';
                @endphp
                <div class="flex items-center gap-2 animate-pulse">
                    <span class="h-1.5 w-1.5 rounded-full bg-current {{ $color }}"></span>
                    <span class="text-[9px] font-black uppercase tracking-widest {{ $color }}">{{ $order->status }}</span>
                </div>
                <span class="text-[8px] text-cream/40 uppercase font-black">{{ $order->created_at->format('d M Y • H:i') }}</span>
            </div>

            <div class="p-6 sm:p-8">
                <div class="text-center mb-6">
                    <p class="text-[7px] font-black text-white/20 uppercase tracking-[0.3em] mb-1">Reference Number</p>
                    <h2 class="text-2xl font-black text-white/80 tracking-tighter">#{{ $order->order_number }}</h2>
                </div>

                <div class="mb-3 px-5 py-4 grid grid-cols-2 rounded-2xl bg-white/[0.02] border border-white/10 shadow-inner">
                    <div>
                        <p class="text-[7px] font-black uppercase text-caramel-400/60 tracking-[0.2em] mb-1">Customer Name</p>
                        <h3 class="text-[11px] font-black text-cream uppercase tracking-wider truncate">
                            {{ $order->customer_name }}
                        </h3>
                    </div>

                    <div>
                        <p class="text-[7px] font-black uppercase text-caramel-400/60 tracking-[0.2em] mb-1">Phone Number</p>
                        <p class="text-[10px] font-bold text-cream tracking-widest">
                            {{ $order->phone ?: 'N/A' }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="px-4 py-3 rounded-2xl bg-white/[0.02] border border-white/10">
                        <p class="text-[7px] font-black uppercase text-caramel-400/40 tracking-widest mb-1">Fulfillment</p>
                        <p class="text-[10px] font-bold capitalize text-cream">{{ $order->delivery_method }}</p>
                    </div>
                    <div class="px-4 py-3 rounded-2xl bg-white/[0.02] border border-white/10">
                        <p class="text-[7px] font-black uppercase text-caramel-400/40 tracking-widest mb-1">Payment Method</p>
                        <p class="text-[10px] font-bold uppercase text-cream">{{ $order->payment_method }}</p>
                    </div>
                </div>

                {{-- RECEIPT LINK ROW --}}
                <div class="mb-4 px-5 py-4 rounded-2xl bg-white/[0.02] border border-white/10 flex justify-between items-center gap-4 group">
                    <div class="flex flex-col min-w-0 flex-1">
                        <p class="text-[7px] font-black uppercase text-caramel-400/60 tracking-[0.2em] mb-1">Proof of Payment</p>
                        
                        <div class="flex items-center gap-2">
                            @if($order->payment_receipt)
                                <div class="flex items-center gap-2 min-w-0">
                                    <span class="text-[10px] font-bold text-cream tracking-wide truncate">
                                        ✓ Receipt Attached
                                    </span>
                                    <span class="h-1 w-1 rounded-full bg-emerald-500 shrink-0 shadow-[0_0_5px_#10b981]"></span>
                                </div>
                            @else
                                <span class="text-[10px] font-bold text-white/20 tracking-wide italic">
                                    ✗ No receipt uploaded
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($order->payment_receipt)
                        {{-- We keep @click just for the modal to work --}}
                        <button @click="previewImage = '{{ asset('storage/' . $order->payment_receipt) }}'" 
                                class="shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl bg-white/[0.05] border border-white/10 text-[9px] font-black uppercase tracking-widest text-cream hover:bg-caramel-400 hover:text-cocoa-950 transition-all active:scale-95 shadow-lg">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            View
                        </button>
                    @endif
                </div>

                <div class="mb-4 px-4 py-3 rounded-2xl bg-white/[0.02] border border-white/10">
                    <p class="text-[7px] font-black uppercase text-caramel-400/60 tracking-[0.2em] mb-1">Delivery Address</p>
                    <p class="text-[10px] font-bold capitalize text-cream">{{ $order->address }}</p>
                </div>

                <div class="border-t border-dashed border-white/10 mb-6"></div>

                {{-- ... Items Loop ... --}}
                <div class="space-y-4 mb-8">
                    @foreach($order->items as $item)
                    <div class="flex justify-between items-start group">
                        <div class="flex-1 pr-4">
                            <p class="font-bold text-cream/90 text-[11px] leading-tight group-hover:text-caramel-400 transition-colors">{{ $item->product_name }}</p>
                            <p class="text-[9px] text-cream/30 italic mt-1">
                                {{ $item->variant_name }} 
                                <span class="mx-1 text-caramel-400/20">|</span> 
                                <span class="text-caramel-400/60 font-black">x{{ $item->quantity }}</span>
                            </p>
                        </div>
                        <p class="font-bold text-[11px] text-cream">RM {{ number_format($item->unit_price * $item->quantity, 2) }}</p>
                    </div>
                    @endforeach
                </div>

                <div class="border-t border-dashed border-white/10 mb-6"></div>

                <div class="space-y-3">
                    <div class="flex justify-between text-[10px] font-bold uppercase tracking-wide text-cream/40">
                        <span>Subtotal</span>
                        <span>RM {{ number_format($order->total_price - ($order->delivery_method === 'pickup' ? 0 : 5), 2) }}</span>
                    </div>
                    
                    <div class="flex justify-between text-[10px] font-bold uppercase tracking-wide text-cream/40">
                        <span>{{ $order->delivery_method }} Fee</span>
                        <span>{{ $order->delivery_method === 'pickup' ? 'RM 0.00' : 'RM 5.00' }}</span>
                    </div>

                    <div class="space-y-3 pt-4 border-t border-white/10">
                        <div class="flex justify-between items-center text-white/40 text-[10px] font-bold uppercase tracking-widest px-1">
                            <span>Total Value</span>
                            <span>RM {{ number_format($order->total_price, 2) }}</span>
                        </div>

                        {{-- Final Amount Display --}}
                        <div class="flex justify-between items-center py-3 px-4 rounded-xl bg-white/[0.03] border border-white/5">
                            <span class="text-[8px] font-black uppercase tracking-widest
                                @if($order->status === 'completed') text-emerald-400 
                                @elseif($order->status === 'cancelled') text-red-500 
                                @else text-amber-400 @endif">
                                
                                @if($order->status === 'completed')
                                    Completed Payment
                                @elseif($order->status === 'cancelled')
                                    Order Cancelled
                                @else
                                    Final Total
                                @endif
                            </span>
                            <span class="text-lg font-black 
                                @if($order->status === 'completed') text-emerald-400 
                                @elseif($order->status === 'cancelled') text-red-500 
                                @else text-amber-400 @endif">
                                
                                @if($order->status === 'completed')
                                    RM {{ number_format($order->total_price, 2) }}
                                @elseif($order->status === 'cancelled')
                                    RM 0.00
                                @else
                                    RM {{ number_format($order->amount_paid, 2) }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex flex-col sm:flex-row gap-3">
            <a href="{{ route('customer.cs_orders') }}" class="flex-1 bg-white/5 border border-white/10 text-cream/60 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center hover:bg-white/10 hover:text-cream transition-all">
                Order History
            </a>
            <a href="{{ route('customer.cs_welcome') }}" class="flex-1 bg-caramel-400 text-cocoa-950 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center hover:scale-[1.02] active:scale-[0.98] transition-all shadow-xl shadow-caramel-400/20">
                New Order
            </a>
        </div>
    </main>

    <div x-show="previewImage" 
        class="fixed inset-0 z-[1000] flex items-center justify-center p-4 sm:p-6"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-cloak
        style="display: none;">
        
        <div class="absolute inset-0 bg-[#0a0908]/95 backdrop-blur-xl" @click="previewImage = null"></div>

        <div class="relative w-full max-w-lg transform transition-all"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90 translate-y-8"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="absolute -top-12 left-0 right-0 flex justify-between items-end px-2">
                <div>
                    <h3 class="font-serif italic text-xl text-caramel-400">Receipt Preview</h3>
                    <p class="text-[7px] font-black uppercase text-white/30 tracking-[0.3em]">Official Document</p>
                </div>
                <button @click="previewImage = null" 
                        class="group flex items-center gap-2 text-white/40 hover:text-white transition-colors">
                    <span class="text-[9px] font-black uppercase tracking-widest">Close</span>
                    <div class="h-8 w-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center group-hover:bg-red-500/20 group-hover:border-red-500/50 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                </button>
            </div>

            <div class="relative overflow-hidden rounded-[2rem] bg-[#1a1614] border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.5)]">
                <div class="absolute inset-0 pointer-events-none border border-white/5 rounded-[2rem] z-10"></div>
                
                <div class="p-3">
                    <img :src="previewImage" 
                        @click.stop 
                        class="w-full h-auto rounded-[1.5rem] shadow-2xl object-contain max-h-[70vh] bg-[#14110f]"
                        alt="Payment Receipt">
                </div>
            </div>

            <div class="absolute -bottom-10 left-1/2 -translate-x-1/2 w-1/2 h-1 bg-caramel-400/20 blur-md rounded-full"></div>
        </div>
    </div>
</body>
</html>