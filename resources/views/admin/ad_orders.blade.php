<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Orders | Sweetflakes Dessert</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:italic,wght@700&family=Inter:wght@400;600;900&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cocoa: { 950: "#0a0705", 900: "#120d0a", 850: "#18110d", 800: "#1c1410", 700: "#2d2018" },
                        caramel: { 400: "#e3bc94", 500: "#d2a679" },
                        cream: "#f6efe8"
                    },
                    fontFamily: { serif: ['Playfair Display', 'serif'], sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        .glass-card { background: rgba(36, 26, 18, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.05); }
        [x-cloak] { display: none !important; }
        select option { background-color: #120d0a; color: #f6efe8; }
        .inventory-row:hover { background: rgba(255, 255, 255, 0.03); }
    </style>
</head>

<body class="min-h-screen text-cream font-sans bg-cocoa-950" x-data="orderManagement()">
    <x-ad_header />
    
    <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[60%] md:w-[40%] h-[40%] rounded-full bg-caramel-500/10 blur-[80px] md:blur-[120px]"></div>
        <div class="absolute bottom-[10%] -right-[5%] w-[50%] md:w-[30%] h-[30%] rounded-full bg-caramel-500/5 blur-[80px] md:blur-[100px]"></div>
    </div>

    <main class="mx-auto max-w-4xl px-4 py-6">

        @if(session('success'))
            <div x-data="{ show: true }" 
                x-show="show" 
                x-init="setTimeout(() => show = false, 5000)"
                x-transition:leave="transition ease-in duration-500"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="mb-4 p-4 bg-emerald-500/20 border border-emerald-500/50 rounded-2xl flex items-center gap-3">
                
                <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span class="text-xs text-emerald-200">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div x-data="{ show: true }" 
                x-show="show" 
                x-init="setTimeout(() => show = false, 5000)"
                x-transition:leave="transition ease-in duration-500"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="mb-4 p-4 bg-red-500/20 border border-red-500/50 rounded-2xl flex items-center gap-3">
                
                <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <ul class="text-xs text-red-200">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <x-ad_pill_link title1="Orders" title2="Management" />

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <input type="text" x-model="search" placeholder="Search customer or #..." 
                class="bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-sm text-cream outline-none focus:border-caramel-500/50">
            
            <select x-model="filterStatus" class="bg-cocoa-900 border border-white/10 rounded-2xl px-6 py-4 text-[10px] font-black uppercase tracking-widest text-white/60 outline-none">
                <option value="all">All Status</option>
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-8">
            <template x-for="order in paginatedOrders" :key="order.id">
                <div class="glass-card group relative p-5 rounded-[2rem] border border-white/10 hover:border-caramel-500/40 bg-white/[0.04] hover:bg-white/[0.06] transition-all duration-500">
                    
                    <div class="absolute -right-4 -top-4 w-20 h-20 bg-caramel-500/5 blur-3xl group-hover:bg-caramel-500/10 transition-all"></div>

                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-2">
                            <span class="text-[12px] font-black text-white/60 tracking-wider"
                                x-text="'#' + order.order_number">
                            </span>
                        </div>
                        
                        <div :class="getStatusPillClass(order.status)" 
                            class="px-3 py-1 rounded-lg border text-[8px] font-black uppercase tracking-widest animate-pulse">
                            <span x-text="order.status"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4 pb-4 border-b border-white/5">
                        <div class="min-w-0">
                            <p class="text-[7px] font-black uppercase text-caramel-400/60 tracking-[0.2em] mb-1.5">Customer Name</p>
                            <div class="flex items-center gap-2">
                                <p class="text-[10px] font-black text-cream truncate uppercase tracking-wider" 
                                x-text="order.customer_name || 'Walk-in Customer'"></p>
                            </div>
                        </div>

                        <div x-show="order.phone" x-cloak>
                            <p class="text-[7px] font-black uppercase text-caramel-400/60 tracking-[0.2em] mb-1.5">Phone Number</p>
                            <div class="flex items-center gap-2">
                                <p class="text-[10px] font-black text-cream tracking-widest" 
                                x-text="order.phone"></p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-end justify-between gap-4 mb-4 pt-3 border-t border-white/5">
                        <div class="min-w-0">
                            <span class="text-[12px] font-black text-cream uppercase tracking-widest" x-text="order.delivery_method || 'Standard'"></span>
                            <div class="flex items-center gap-1.5 mt-1">
                                <span class="text-[7px] font-black text-caramel-500/60 uppercase tracking-widest" x-text="order.formatted_date.split(',')[0]"></span>
                                <div class="w-0.5 h-0.5 rounded-full bg-white/20"></div>
                                <span class="text-[7px] font-black text-caramel-500/60 uppercase tracking-widest" x-text="order.formatted_date.split(',')[1]"></span>
                            </div>
                        </div>

                        <div class="text-right shrink-0">
                            <p class="text-[7px] font-black text-white/20 uppercase tracking-[0.2em] mb-0.5"
                            x-text="order.status === 'completed' ? 'Complete Payment' : 
                                    (order.status === 'pending' ? 'Total Paid' : 'Order Revoked')">
                            </p>

                            <div :class="{
                                    'text-emerald-400': order.status === 'completed',
                                    'text-amber-400': order.status === 'pending',
                                    'text-red-400/50': order.status === 'cancelled'
                                }">
                                
                                <template x-if="order.status !== 'cancelled'">
                                    <p class="text-lg font-black tracking-tighter" 
                                    x-text="'RM' + parseFloat(order.status === 'completed' ? order.total_price : order.amount_paid).toFixed(2)">
                                    </p>
                                </template>

                                <template x-if="order.status === 'cancelled'">
                                    <p class="text-xs font-black tracking-widest uppercase italic">
                                        Payment Discarded
                                    </p>
                                </template>
                            </div>
                        </div>
                    </div>

                    <button @click="openModal(order)" 
                        class="flex items-center justify-center w-full bg-white/[0.04] hover:bg-caramel-500 py-3.5 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] text-white/40 hover:text-cocoa-950 transition-all group border border-white/5 hover:border-caramel-500">
                        <span>Manage Order</span>
                        <svg class="w-2 h-2 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </template>
        </div>

        <div x-show="filteredOrders.length === 0" class="glass-card rounded-[2.5rem] py-20 text-center mb-8">
            <div class="flex flex-col items-center opacity-40">
                <svg class="w-10 h-10 mb-4 text-caramel-500/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <p class="text-[10px] uppercase tracking-[0.3em] font-black text-cream">No orders matching your search</p>
            </div>
        </div>

        <div class="flex items-center justify-between px-2 mb-12" x-show="totalPages > 1">
            <button @click="page--" :disabled="page === 1" 
                    class="p-3 rounded-2xl bg-white/5 disabled:opacity-10 disabled:cursor-not-allowed hover:bg-white/10 transition-all text-white/60">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-white/30">Page</span>
                <span class="text-[10px] font-black text-caramel-400" x-text="page"></span>
                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-white/30">of</span>
                <span class="text-[10px] font-black text-white/60" x-text="totalPages"></span>
            </div>

            <button @click="page++" :disabled="page === totalPages" 
                    class="p-3 rounded-2xl bg-white/5 disabled:opacity-10 disabled:cursor-not-allowed hover:bg-white/10 transition-all text-white/60">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>

        <div x-show="selectedOrder" x-cloak 
            class="fixed inset-0 z-[100] flex items-end md:items-center justify-center p-0 md:p-4 bg-[#1a1614] backdrop-blur-md"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100">
            
            <div @click.away="selectedOrder = null" 
                class="glass-card w-full max-w-lg rounded-t-[2.5rem] md:rounded-[2.5rem] shadow-2xl bg-[#1a1614] border border-white/10 overflow-hidden flex flex-col max-h-[90vh]">

                <div class="px-8 py-4 bg-white/[0.03] flex justify-between items-center border-b border-white/5">
                    <div class="flex items-center gap-2 animate-pulse">
                        <span class="h-1.5 w-1.5 rounded-full bg-current" :class="getStatusColor(selectedOrder?.status)"></span>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em]" :class="getStatusColor(selectedOrder?.status)" x-text="selectedOrder?.status"></span>
                    </div>
                    <button @click="selectedOrder = null" class="text-white/20 hover:text-white transition-colors p-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Receipt Preview Modal -->
                <div class="p-8 overflow-y-auto custom-scrollbar">
                    <div class="text-center mb-6">
                        <p class="text-[7px] font-black text-white/20 uppercase tracking-[0.3em] mb-1">Reference Number</p>
                        <h2 class="text-2xl font-black text-white/80 tracking-tighter" x-text="'#' + selectedOrder?.order_number"></h2>
                    </div>

                    <div class="mb-4 p-5 rounded-2xl bg-white/[0.03] border border-white/10 shadow-inner">
                        <div class="flex items-center justify-between gap-4">
                            <div class="space-y-0.5 min-w-0">
                                <p class="text-[7px] font-black uppercase text-caramel-400/40 tracking-[0.2em]">Customer Name</p>
                                <h3 class="text-sm font-black text-cream uppercase tracking-wider truncate" x-text="selectedOrder?.customer_name"></h3>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-[7px] font-black uppercase text-caramel-400/40 tracking-[0.2em]">Phone Number</p>
                                <p class="text-[10px] font-bold text-cream tracking-widest" x-text="selectedOrder?.phone"></p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="px-4 py-3 rounded-2xl bg-white/[0.03] border border-white/10 text-center">
                            <p class="text-[7px] font-black uppercase text-caramel-400/40 tracking-widest mb-1">Method</p>
                            <p class="text-[10px] font-bold capitalize text-cream" x-text="selectedOrder?.delivery_method"></p>
                        </div>
                        <div class="px-4 py-3 rounded-2xl bg-white/[0.03] border border-white/10 text-center">
                            <p class="text-[7px] font-black uppercase text-caramel-400/40 tracking-widest mb-1">Payment</p>
                            <p class="text-[10px] font-bold uppercase text-cream" x-text="selectedOrder?.payment_method"></p>
                        </div>
                    </div>

                    <div class="mb-6 px-5 py-4 rounded-2xl border flex justify-between items-center gap-4 group"
                        :class="selectedOrder?.payment_receipt ? 'bg-white/[0.02] border border-white/10' : 'bg-white/[0.02] border border-white/10'">
                        
                        <div class="flex flex-col min-w-0 flex-1">
                            <p class="text-[7px] font-black uppercase text-caramel-400/60 tracking-[0.2em] mb-1">Proof of Payment</p>
                            
                            <div class="flex items-center gap-2">
                                <template x-if="selectedOrder?.payment_receipt">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="text-[10px] font-bold text-cream tracking-wide">✓ Receipt Attached</span>
                                        <span class="h-1 w-1 rounded-full bg-emerald-500 shrink-0 shadow-[0_0_5px_#10b981]"></span>
                                    </div>
                                </template>

                                <template x-if="!selectedOrder?.payment_receipt">
                                    <span class="text-[10px] font-bold text-white/20 tracking-wide italic">No receipt attached</span>
                                </template>
                            </div>
                        </div>

                        <template x-if="selectedOrder?.payment_receipt">
                            <button @click="previewImage = '{{ asset('storage') }}/' + selectedOrder.payment_receipt" 
                                    class="shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl bg-white/[0.05] border border-white/10 text-[9px] font-black uppercase tracking-widest text-cream hover:bg-caramel-400 hover:text-cocoa-950 transition-all active:scale-95 shadow-lg">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View
                            </button>
                        </template>
                    </div>

                    <template x-if="selectedOrder?.address && !['pickup', 'walk-in'].includes(selectedOrder?.delivery_method?.toLowerCase())">
                        <div class="mb-6 p-4 rounded-2xl bg-white/[0.02] border border-white/10">
                            <p class="text-[7px] font-black uppercase text-caramel-400/40 tracking-widest mb-1">Delivery Address</p>
                            <p class="text-xs text-cream leading-relaxed" x-text="selectedOrder?.address"></p>
                        </div>
                    </template>

                    <div class="space-y-3 mb-6">
                        <label class="text-[8px] font-black uppercase tracking-widest text-caramel-500/50 block">Kitchen Slip / Items</label>
                        <template x-for="item in selectedOrder?.items" :key="item.id">
                            <div class="flex justify-between items-center bg-white/[0.02] border border-white/10 p-3 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <div class="bg-caramel-500 text-cocoa-950 h-7 w-7 flex items-center justify-center rounded-lg font-black text-[10px]" x-text="item.quantity"></div>
                                    <div>
                                        <p class="font-bold text-cream text-[11px] leading-tight" x-text="item.product_name"></p>
                                        <p class="text-[9px] text-caramel-400/50 font-medium uppercase tracking-tighter" x-text="item.variant_name"></p>
                                    </div>
                                </div>
                                <p class="text-[10px] font-bold text-white/60" x-text="'RM ' + (parseFloat(item.unit_price) * item.quantity).toFixed(2)"></p>
                            </div>
                        </template>
                    </div>

                    <div class="border-t-2 border-dotted border-white/10 my-6"></div>
                    <div class="space-y-3 mb-8 px-1">
                        <div class="flex justify-between items-center text-[10px] font-bold text-cream/40 uppercase tracking-widest">
                            <span>Subtotal</span>
                            <span x-text="'RM ' + (parseFloat(selectedOrder?.total_price || 0) - (!['pickup', 'walk-in'].includes(selectedOrder?.delivery_method?.toLowerCase()) ? 5 : 0)).toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between items-center text-[10px] font-bold uppercase tracking-widest">
                            <span class="text-cream/40">Delivery Fee</span>
                            <span class="text-cream/40" x-text="['pickup', 'walk-in'].includes(selectedOrder?.delivery_method?.toLowerCase()) ? 'RM 0.00' : 'RM 5.00'"></span>
                        </div>
                        <div class="flex justify-between items-center py-3 px-4 rounded-xl bg-white/[0.03] border border-white/5">
                            <span class="text-[8px] font-black uppercase tracking-widest"
                                :class="{
                                    'text-emerald-400': selectedOrder?.status === 'completed',
                                    'text-red-500': selectedOrder?.status === 'cancelled',
                                    'text-amber-400': selectedOrder?.status === 'pending'
                                }"
                                x-text="selectedOrder?.status === 'completed' ? 'Completed Payment' : (selectedOrder?.status === 'cancelled' ? 'Order Voided' : 'Deposit Paid')">
                            </span>

                            <span class="text-lg font-black tracking-tighter"
                                :class="{
                                    'text-emerald-400': selectedOrder?.status === 'completed',
                                    'text-red-500/50 line-through': selectedOrder?.status === 'cancelled',
                                    'text-amber-400': selectedOrder?.status === 'pending'
                                }"
                                x-text="selectedOrder?.status === 'completed' 
                                        ? 'RM ' + parseFloat(selectedOrder?.total_price || 0).toFixed(2) 
                                        : (selectedOrder?.status === 'cancelled' ? 'RM 0.00' : 'RM ' + parseFloat(selectedOrder?.amount_paid || 0).toFixed(2))">
                            </span>
                        </div>
                    </div>

                    <form :action="'{{ url('admin/orders') }}/' + selectedOrder?.id" method="POST" class="mt-4 pt-6 border-t border-white/5">
                        @csrf @method('PATCH')
                        <div class="flex flex-col gap-4">
                            <label class="text-[8px] font-black uppercase tracking-widest text-white/30 text-center">Update Order Status</label>
                            <div class="flex gap-2">
                                <select name="status" x-model="newStatus" class="flex-1 bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-[10px] font-bold uppercase tracking-widest text-cream outline-none appearance-none">
                                    <option value="pending" class="bg-cocoa-900">Pending</option>
                                    <option value="completed" class="bg-cocoa-900">Completed</option>
                                    <option value="cancelled" class="bg-cocoa-900">Cancelled</option>
                                </select>
                                <button type="submit" class="bg-caramel-500 text-cocoa-950 px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-caramel-400 transition-all">Confirm</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="previewImage" 
            class="fixed inset-0 z-[1100] flex items-center justify-center p-4 sm:p-6"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            x-cloak
            style="display: none;">
            
            <div class="absolute inset-0 bg-[#0a0908]/95 backdrop-blur-xl" @click="previewImage = null"></div>

            <div class="relative w-full max-w-lg transform transition-all"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90 translate-y-8"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                @click.away="previewImage = null">
                
                <div class="absolute -top-12 left-0 right-0 flex justify-between items-end px-2">
                    <div>
                        <h3 class="font-serif italic text-xl text-caramel-400">Proof of Payment</h3>
                        <p class="text-[7px] font-black uppercase text-white/30 tracking-[0.3em]" 
                        x-text="selectedOrder ? 'Order ID: #' + selectedOrder.order_number : ''"></p>
                    </div>
                    <button @click.stop="previewImage = null" 
                            class="h-8 w-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white/40 hover:text-white hover:bg-white/10 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>

                <div class="relative overflow-hidden rounded-[2.5rem] bg-[#1a1614] border border-white/10 shadow-2xl">
                    <div class="p-3">
                        <img :src="previewImage" 
                            class="w-full h-auto rounded-[1.8rem] shadow-2xl object-contain max-h-[70vh] bg-black/20"
                            alt="Payment Receipt">
                    </div>
                    
                    <div class="px-8 py-4 bg-white/[0.02] border-t border-white/5 flex justify-between items-center">
                        <span class="text-[9px] font-black text-white/20 uppercase tracking-[0.3em]">Official Receipt Preview</span>
                        <a :href="previewImage" 
                        download 
                        class="text-[9px] font-black text-caramel-400 uppercase tracking-widest hover:text-cream transition-colors">
                            Download
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function orderManagement() {
            return {
                orders: @json($orders),
                search: '',
                filterStatus: 'all',
                selectedOrder: null,
                previewImage: null,
                newStatus: '',
                
                // Pagination State
                page: 1,
                itemsPerPage: 10,

                // Filtered Orders (Base logic)
                get filteredOrders() {
                    const allOrders = Array.isArray(this.orders) ? this.orders : [];
                    return allOrders.filter(o => {
                        const s = this.search.toLowerCase();
                        const matchesSearch = o.order_number?.toString().toLowerCase().includes(s) || 
                                            o.customer_name?.toLowerCase().includes(s);
                        const matchesStatus = this.filterStatus === 'all' || o.status === this.filterStatus;
                        return matchesSearch && matchesStatus;
                    });
                },

                // Paginated Getter (The 10 items to show)
                get paginatedOrders() {
                    const start = (this.page - 1) * this.itemsPerPage;
                    return this.filteredOrders.slice(start, start + this.itemsPerPage);
                },

                // Total Pages Helper
                get totalPages() {
                    return Math.ceil(this.filteredOrders.length / this.itemsPerPage) || 1;
                },

                // Initialize and watch for filter changes to reset page
                init() {
                    this.$watch('search', () => this.page = 1);
                    this.$watch('filterStatus', () => this.page = 1);
                },

                openModal(order) {
                    this.selectedOrder = order;
                    this.newStatus = order.status;
                },

                getStatusColor(status) {
                    return {
                        'pending': 'text-amber-400',
                        'completed': 'text-emerald-400',
                        'cancelled': 'text-red-400'
                    }[status] || 'text-caramel-400';
                },
                getStatusPillClass(status) {
                    return {
                        'pending': 'bg-amber-500/10 border-amber-500/20 text-amber-400',
                        'completed': 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400',
                        'cancelled': 'bg-red-500/10 border-red-500/20 text-red-400'
                    }[status] || 'border-white/10';
                }
            }
        }
    </script>
</body>
</html>