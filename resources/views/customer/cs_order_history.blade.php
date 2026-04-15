<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders | Sweetflakes</title>
    <x-tailwind />
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,700&family=Inter:wght@400;600;800&display=swap');
        body { background-color: #0d0a09; color: #f5f5f5; font-family: 'Inter', sans-serif; }
        .glass-card {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.03), rgba(255, 255, 255, 0.01));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(210, 166, 121, 0.1);
            transition: all 0.3s ease;
        }
        .glass-card:hover { border-color: rgba(210, 166, 121, 0.4); transform: translateY(-2px); }
    </style>
</head>
<body class="min-h-screen pb-20">
    <x-cs_header />

    <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[60%] md:w-[40%] h-[40%] rounded-full bg-caramel-500/10 blur-[80px] md:blur-[120px]"></div>
        <div class="absolute bottom-[10%] -right-[5%] w-[50%] md:w-[30%] h-[30%] rounded-full bg-caramel-500/5 blur-[80px] md:blur-[100px]"></div>
    </div>

    <main class="max-w-4xl mx-auto px-6 py-10" 
      x-data="{ 
        search: '', 
        statusFilter: 'all',
        showHistory: {{ request()->has('phone') ? 'true' : 'false' }},
        evaluateVisibility(orderNo, status) {
            const matchesSearch = orderNo.toLowerCase().includes(this.search.toLowerCase());
            const matchesStatus = this.statusFilter === 'all' || status.toLowerCase() === this.statusFilter;
            return matchesSearch && matchesStatus;
        }
      }">
    
        <div class="mb-6 text-center md:text-left">
            <h1 class="font-serif text-5xl italic text-cream">Order History</h1>
            <p class="text-caramel-400/60 uppercase tracking-[0.3em] text-[10px] font-black mt-2">Access your delicacies with your mobile number</p>
        </div>

        <div class="mb-6 bg-white/[0.02] border border-white/5 p-6 md:p-8 rounded-[2.5rem] shadow-2xl">
            <form action="{{ route('customer.cs_orders') }}" method="GET" class="flex flex-col md:flex-row items-end gap-4">
                <div class="flex-1 w-full space-y-2">
                    <label class="text-[10px] font-black text-caramel-400/50 uppercase tracking-[0.2em] ml-2">Enter Mobile Number</label>
                    <input type="tel" name="phone" value="{{ request('phone') }}" placeholder="E.g. 60123456789" required
                        class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-sm text-cream outline-none focus:border-caramel-500/50 transition-all placeholder:text-white/10">
                </div>
                <button type="submit" class="w-full md:w-auto bg-caramel-400 text-cocoa-950 px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:scale-[1.02] transition-all shadow-lg shadow-caramel-400/10">
                    View History
                </button>
            </form>
        </div>

        <template x-if="showHistory">
            <div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="relative flex-1">
                        <input type="text" x-model="search" placeholder="Filter by Order #..." 
                            class="w-full bg-white/5 border border-white/10 rounded-2xl py-3 px-5 text-[10px] text-cream outline-none focus:border-caramel-500/50 transition-all uppercase tracking-widest">
                    </div>
                    <select x-model="statusFilter" 
                            class="bg-cocoa-950 border border-white/10 rounded-2xl px-6 py-3 text-[10px] font-black uppercase tracking-widest text-white/60 outline-none focus:border-caramel-500/50">
                        <option value="all">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    @forelse($orders as $order)
                        @php
                            $statusColor = match($order->status) {
                                'completed' => 'text-emerald-400 bg-emerald-400/10 border-emerald-400/20',
                                'pending'   => 'text-amber-400 bg-amber-400/10 border-amber-400/20',
                                'cancelled' => 'text-red-400 bg-red-400/10 border-red-400/20',
                                default     => 'text-blue-400 bg-blue-400/10 border-blue-400/20',
                            };
                        @endphp
                        <div x-show="evaluateVisibility('{{ $order->order_number }}', '{{ $order->status }}')"
                            x-transition.opacity
                            class="glass-card group relative p-5 rounded-[2rem] border border-white/10 hover:border-caramel-500/40 bg-white/[0.01] hover:bg-white/[0.03] transition-all duration-500">
                            
                            <div class="flex items-center justify-between mb-6">
                                <span class="text-[11px] font-black text-white/60 tracking-wider">#{{ $order->order_number }}</span>
                                
                                <span class="px-3 py-1 rounded-lg {{ $statusColor }} border text-[8px] font-black uppercase tracking-widest animate-pulse">
                                    {{ $order->status }}
                                </span>
                            </div>

                            <div class="mb-4 pb-4 border-b border-white/5 grid grid-cols-2 gap-4">
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

                            <div class="flex items-end justify-between mb-6">
                                <div class="space-y-1">
                                    <p class="text-[10px] font-black text-caramel-400 uppercase tracking-widest">{{ $order->delivery_method }}</p>
                                    <p class="text-[8px] font-bold text-white/40 uppercase tracking-widest">
                                        {{ $order->created_at->format('d M Y') }} • {{ $order->created_at->format('h:i A') }}
                                    </p>
                                </div>
                                @if($order->status === 'completed')
                                    <div class="text-right text-emerald-400">
                                        <p class="text-[7px] font-black text-white/20 uppercase tracking-[0.2em] mb-0.5">Complete Payment</p>
                                        <span class="text-lg font-black tracking-tighter">RM {{ number_format($order->total_price, 2) }}</span>
                                    </div>
                                @elseif($order->status === 'pending')
                                    <div class="text-right text-amber-400">
                                        <p class="text-[7px] font-black text-white/20 uppercase tracking-[0.2em] mb-0.5">Total Paid</p>
                                        <p class="text-lg font-black tracking-tighter">RM{{ number_format($order->amount_paid, 2) }}</p>
                                    </div>
                                @elseif($order->status === 'cancelled')
                                    <div class="text-right text-red-400/50">
                                        <p class="text-[7px] font-black text-white/20 uppercase tracking-[0.2em] mb-0.5">Order Revoked</p>
                                        <p class="text-xs font-black tracking-widest uppercase italic">Payment Discarded</p>
                                    </div>
                                @endif
                            </div>

                            <a href="{{ route('customer.cs_order_complete', $order->order_number) }}" 
                            class="flex items-center justify-center w-full bg-white/[0.03] py-3.5 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] text-white/40 hover:text-cocoa-950 hover:bg-caramel-400 transition-all border border-white/5 hover:border-caramel-400">
                                View Details
                            </a>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-16 bg-white/[0.02] rounded-[2.5rem] border border-dashed border-white/10">
                            <p class="text-[10px] uppercase tracking-widest font-black text-white/20">No orders found for this number</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </template>

        <template x-if="!showHistory">
            <div class="text-center py-20 border border-dashed border-white/5 rounded-[3rem]">
                <span class="text-4xl block mb-4 opacity-20">🍰</span>
                <p class="text-[10px] uppercase tracking-[0.3em] font-black text-white/20">Enter your number to retrieve your orders</p>
            </div>
        </template>
    </main>
</body>
</html>