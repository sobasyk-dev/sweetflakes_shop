<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inventory | Sweetflakes Dessert</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:italic,wght@700&family=Inter:wght@400;600;900&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cocoa: { 950: "#0a0705", 900: "#120d0a", 850: "#18110d", 800: "#1c1410", 700: "#2d2018" },
                        caramel: { 400: "#e3bc94", 500: "#d2a679" },
                        coral: "#f85858",
                        cream: "#f6efe8"
                    },
                    fontFamily: {
                        serif: ['Playfair Display', 'serif'],
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .glass-header { background: rgba(10, 7, 5, 0.8); backdrop-filter: blur(12px); }
        .glass-card {
            background: rgba(36, 26, 18, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .inventory-row { transition: all 0.2s ease; }
        .inventory-row:hover { background: rgba(255, 255, 255, 0.02); }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: #d2a679; border-radius: 10px; }
        
        /* Ensures the slide-up animation for toasts works */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-up { animation: slideUp 0.4s ease-out forwards; }
    </style>
</head>

<body class="min-h-screen text-cream font-sans bg-cocoa-950 selection:bg-caramel-500/30">

    <x-ad_header />
    <x-alerts />

    <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[60%] md:w-[40%] h-[40%] rounded-full bg-caramel-500/10 blur-[80px] md:blur-[120px]"></div>
        <div class="absolute bottom-[10%] -right-[5%] w-[50%] md:w-[30%] h-[30%] rounded-full bg-caramel-500/5 blur-[80px] md:blur-[100px]"></div>
    </div>

    <main class="mx-auto max-w-6xl px-4 md:px-6 py-6">

        <x-ad_pill_link
            title1="Admin"
            title2="Inventory"
        />

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
            <div class="flex flex-1 max-w-md items-center gap-4">
                <div class="relative flex-1 group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-white/20 group-focus-within:text-caramel-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" id="inventorySearch" placeholder="Search product or category..." 
                        class="w-full bg-cocoa-900/60 border border-white/10 rounded-full py-2.5 pl-11 pr-4 text-xs text-white placeholder:text-white/20 focus:outline-none focus:ring-1 focus:ring-caramel-500/50 transition-all">
                </div>

                <a href="{{ route('admin.ad_create') }}" class="shrink-0 flex items-center gap-2 bg-caramel-500 px-5 py-2.5 rounded-full transition-transform active:scale-95 shadow-lg shadow-caramel-500/10">
                    <span class="text-[10px] font-black uppercase tracking-widest text-cocoa-950">+ New Item</span>
                </a>
            </div>
        </div>

        {{-- Dynamic Status Notifications --}}
        <div class="space-y-4 mb-6">
            @php
                $notifications = [
                    ['key' => 'login_success', 'color' => 'green-500', 'icon' => '✅', 'label' => 'Login Successful'],
                    ['key' => 'success', 'color' => 'green-500', 'icon' => '✨', 'label' => 'Product Created'],
                    ['key' => 'update', 'color' => 'caramel-500', 'icon' => '🛠️', 'label' => 'Product Updated'],
                    ['key' => 'deleted', 'color' => 'coral', 'icon' => '🗑️', 'label' => 'Product Removed']
                ];
            @endphp

            @foreach($notifications as $notif)
                @if(session($notif['key']))
                    <div id="{{ $notif['key'] }}-toast" 
                        class="group bg-cocoa-900 border border-{{ $notif['color'] }}/20 p-4 rounded-2xl flex justify-between items-center animate-slide-up shadow-lg">
                        <div class="flex items-center gap-4">
                            <span class="text-lg">{{ $notif['icon'] }}</span>
                            <div>
                                <p class="text-[9px] font-black uppercase tracking-[0.2em] text-{{ $notif['color'] }}">
                                    {{ $notif['label'] }}
                                </p>
                                <p class="text-xs text-white/70 italic">{{ session($notif['key']) }}</p>
                            </div>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-white/10 group-hover:text-white/40 transition-colors px-2">✕</button>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="bg-cocoa-900/40 border border-white/5 rounded-[2rem] overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-caramel-500/20 bg-white/[0.02]">
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-white/30">Item</th>
                            <th class="px-4 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-white/30">Category</th>
                            <th class="px-4 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-white/30">Stock</th>
                            <th class="px-4 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-white/30">Price</th>
                            <th class="px-4 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-white/30 text-center">Status</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-white/30 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="inventoryTableBody" class="divide-y divide-caramel-500/10">
                        @forelse($products as $product)
                            @php 
                                $totalStock = $product->variants->sum('stock');
                                $minPrice = $product->variants->min('price');
                            @endphp
                            
                            <tr class="inventory-row bg-white/[0.02]">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-12 w-12 rounded-xl overflow-hidden border border-white/10 shrink-0 shadow-lg">
                                            <img src="{{ asset('assets/' . ($product->image ?? 'placeholder.jpg')) }}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="min-w-0">
                                            <p class="product-name font-serif italic text-lg text-white leading-none truncate">{{ $product->name }}</p>
                                            <p class="text-[9px] text-caramel-500/50 uppercase font-black mt-1 tracking-widest">Master Product</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="category-name text-[8px] font-bold text-caramel-500/80 uppercase tracking-widest border border-caramel-500/20 px-2 py-0.5 rounded-md bg-caramel-500/5">
                                        {{ $product->category->name ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-black {{ $totalStock < 10 ? 'text-coral' : 'text-white/40' }}">
                                            {{ $totalStock }} Total
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-white/20 text-[10px] font-bold">
                                    FROM RM{{ number_format($minPrice, 2) }}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @if($product->is_active)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-500/10 border border-green-500/20">
                                            <span class="h-1.5 w-1.5 rounded-full bg-green-500 shadow-[0_0_8px_#22c55e]"></span>
                                            <span class="text-[8px] font-black uppercase tracking-widest text-green-500">Live</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/5 border border-white/10">
                                            <span class="h-1.5 w-1.5 rounded-full bg-white/20"></span>
                                            <span class="text-[8px] font-black uppercase tracking-widest text-white/30">Offline</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end items-center gap-2">
                                        <a href="{{ route('admin.ad_edit', $product) }}" class="p-2 rounded-lg bg-white/5 text-caramel-500 hover:bg-caramel-500 hover:text-cocoa-950 transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </a>
                                        <form action="{{ route('admin.ad_delete', $product) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('Remove entire creation and all variants?')" class="p-2 rounded-lg bg-white/5 text-coral/80 hover:bg-coral hover:text-white transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            @foreach($product->variants as $variant)
                            <tr class="inventory-row group/variant border-l-2 border-caramel-500/20">
                                <td class="px-6 py-2 pl-16">
                                    <div class="flex items-center gap-2">
                                        <span class="text-white/20 text-xs">└</span>
                                        <p class="text-xs font-bold text-white/70 uppercase tracking-widest">{{ $variant->name }}</p>
                                    </div>
                                </td>
                                <td class="px-4 py-2">
                                    </td>
                                <td class="px-4 py-2">
                                    <span class="text-[10px] font-black {{ $variant->stock < 5 ? 'text-coral' : 'text-white/40' }}">
                                        {{ $variant->stock }} <span class="text-[8px] font-normal opacity-50">in stock</span>
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    <span class="text-[10px] font-bold text-caramel-400">RM{{ number_format($variant->price, 2) }}</span>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    @if($variant->is_active)
                                        <div class="h-1.5 w-1.5 rounded-full bg-green-500 mx-auto" title="Variant Visible"></div>
                                    @else
                                        <div class="h-1.5 w-1.5 rounded-full bg-red-500/20 mx-auto" title="Variant Hidden"></div>
                                    @endif
                                </td>
                                <td class="px-6 py-2"></td>
                            </tr>
                            @endforeach

                        @empty
                            <tr id="emptyState">
                                <td colspan="6" class="py-20 text-center text-white/20 uppercase text-[10px] tracking-widest font-black">Pantry is empty</td>
                            </tr>
                        @endforelse
                        
                        <tr id="noResults" class="hidden">
                            <td colspan="6" class="py-20 text-center text-white/20 uppercase text-[10px] tracking-widest font-black">No matching desserts found</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('[id$="-toast"]');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = "all 0.8s cubic-bezier(0.4, 0, 0.2, 1)";
                    alert.style.opacity = "0";
                    alert.style.transform = "translateX(20px)";
                    setTimeout(() => alert.remove(), 800);
                }, 5000);
            });
        });

        // Search Logic
        const searchInput = document.getElementById('inventorySearch');
        const tableBody = document.getElementById('inventoryTableBody');
        const noResults = document.getElementById('noResults');

        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            const rows = tableBody.querySelectorAll('.inventory-row');
            let hasVisibleRows = false;

            rows.forEach(row => {
                const productName = row.querySelector('.product-name').textContent.toLowerCase();
                const categoryName = row.querySelector('.category-name').textContent.toLowerCase();

                if (productName.includes(searchTerm) || categoryName.includes(searchTerm)) {
                    row.style.display = "";
                    hasVisibleRows = true;
                } else {
                    row.style.display = "none";
                }
            });

            if (!hasVisibleRows && searchTerm !== "") {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }
        });
    </script>
</body>
</html>