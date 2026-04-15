<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Creation | Sweetflakes Dessert</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cocoa: { 950: "#0a0705", 900: "#120d0a", 800: "#1c1410" },
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
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:italic,wght@700&family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    
    <style>
        .serif-italic { font-family: 'Playfair Display', serif; font-style: italic; }
        .artisan-input {
            transition: all 0.3s ease;
            border: 1px solid rgba(210, 166, 121, 0.1);
        }
        .artisan-input:focus {
            border-color: #d2a679;
            background-color: #1c1410;
            outline: none;
            box-shadow: 0 0 15px rgba(210, 166, 121, 0.1);
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-up { animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1); }
        [x-cloak] { display: none !important; }
        textarea::-webkit-scrollbar { width: 4px; }
        textarea::-webkit-scrollbar-thumb { background: #d2a679; border-radius: 10px; }
    </style>
</head>

<body class="bg-cocoa-950 text-white font-sans antialiased selection:bg-caramel-500/30 flex flex-col min-h-screen overflow-x-hidden">

    <x-ad_header />
    <x-alerts />

    <main class="max-w-4xl mx-auto px-4 md:px-6 py-8 relative flex-grow w-full">

        {{-- Error Notification --}}
        @if($errors->any())
            <div id="error-toast" class="mb-10 bg-cocoa-900 border border-coral/30 p-6 rounded-[2rem] shadow-2xl animate-slide-up relative overflow-hidden">
                <div class="absolute top-0 left-0 h-full w-1 bg-coral"></div>
                <div class="flex items-start gap-4">
                    <span class="text-coral text-xl">⚠️</span>
                    <div class="flex-1">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-coral mb-2">Recipe Imperfections Detected</h3>
                        <ul class="space-y-1">
                            @foreach($errors->all() as $error)
                                <li class="text-xs text-white/60 font-medium italic">— {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button onclick="document.getElementById('error-toast').remove()" class="text-white/20 hover:text-white transition-colors">✕</button>
                </div>
            </div>
        @endif

        <div class="text-center mb-10">
            <p class="text-caramel-500 text-[10px] font-black uppercase tracking-[0.4em] mb-4">Artisan Patisserie</p>
            <h1 class="serif-italic text-4xl md:text-6xl mb-2">Edit Creation</h1>
            <p class="text-white/30 text-[10px] uppercase tracking-widest mt-2">Refining: {{ $product->name }}</p>
            <div class="h-px w-20 bg-gradient-to-r from-transparent via-caramel-500 to-transparent mx-auto mt-6"></div>
        </div>

        <form action="{{ route('admin.ad_update', ['product' => $product]) }}" method="POST" class="space-y-8 mb-12">
            @csrf
            @method('PUT')

            {{-- Section: Identity --}}
            <div class="bg-cocoa-900 border border-white/5 rounded-[2rem] p-6 md:p-12 shadow-2xl">
                <h2 class="text-xs font-black uppercase tracking-[0.3em] text-caramel-500 mb-10 flex items-center gap-4">
                    <span>Identity</span>
                    <span class="h-px flex-1 bg-white/5"></span>
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] uppercase font-black tracking-widest text-white/60 ml-1">Product Name</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required 
                            oninput="this.value = this.value.replace(/\b\w/g, l => l.toUpperCase())"
                            class="artisan-input w-full bg-cocoa-800 rounded-2xl px-6 py-4 text-sm placeholder:text-white/30 truncate">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] uppercase font-black tracking-widest text-white/60 ml-1">Category</label>
                        <div class="space-y-4">
                            <select name="category_id" id="categorySelect" onchange="toggleNewCategory(this.value)" required
                                class="artisan-input w-full bg-cocoa-800 text-white/70 rounded-2xl px-6 py-4 text-sm appearance-none cursor-pointer">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                                <option value="NEW" class="text-caramel-500 font-bold">+ Define New Category</option>
                            </select>

                            <div id="newCategoryWrapper" class="hidden animate-slide-up">
                                <label class="text-[10px] uppercase font-black tracking-widest text-white/60 ml-1">New Category Name</label>
                                <input type="text" name="new_category_name" id="newCategoryInput"
                                    class="artisan-input w-full bg-cocoa-800 border-caramel-500/30 rounded-2xl px-6 py-4 text-sm mt-1 placeholder:text-white/30">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 space-y-2">
                    <label class="text-[10px] uppercase font-black tracking-widest text-white/60 ml-1">Product Description</label>
                    <textarea name="description" rows="3" class="artisan-input w-full bg-cocoa-800 placeholder:text-white/30 rounded-2xl px-6 py-4 text-sm resize-none">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>

            {{-- Section: Visual Asset --}}
            <div class="bg-cocoa-900 border border-white/5 rounded-[2rem] p-6 md:p-12 shadow-2xl">
                <h2 class="text-xs font-black uppercase tracking-[0.3em] text-caramel-500 mb-10 flex items-center gap-4">
                    <span>Visual Asset</span>
                    <span class="h-px flex-1 bg-white/5"></span>
                </h2>

                <div class="flex flex-col md:flex-row gap-8 items-center">
                    <div class="w-48 h-48 md:w-56 md:h-56 rounded-[2rem] bg-cocoa-800 border border-white/5 flex items-center justify-center overflow-hidden shrink-0 shadow-inner group relative">
                        <img id="imagePreview" src="{{ asset('assets/' . $product->image) }}" 
                            class="w-full h-full object-cover {{ $product->image ? '' : 'hidden' }} transition-opacity duration-500" alt="Preview">
                        
                        <div id="previewPlaceholder" class="{{ $product->image ? 'hidden' : '' }} text-center p-6">
                            <div class="w-12 h-12 bg-caramel-500/10 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="text-xl">🖼️</span>
                            </div>
                            <p class="text-[9px] uppercase font-black tracking-widest text-white/20">Awaiting Asset Path</p>
                        </div>
                    </div>

                    <div class="flex-1 space-y-6 w-full">
                        <div class="space-y-3">
                            <label class="text-[10px] uppercase font-black tracking-widest text-white/40 ml-1">Asset Filename</label>
                            <div class="relative flex items-center bg-cocoa-800 rounded-2xl border border-white/5 artisan-input overflow-hidden">
                                <span class="pl-6 text-white/20 text-sm font-serif italic whitespace-nowrap">assets/</span>
                                <input type="text" name="image" id="imageInput" value="{{ old('image', $product->image) }}"
                                    required oninput="updateAssetPreview(this.value)"
                                    class="w-full bg-transparent px-2 py-5 text-sm placeholder:text-white/10 outline-none border-none truncate">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section: Pricing & Stock (Now with Per-Variant Visibility) --}}
            <div class="bg-cocoa-900 border border-white/5 rounded-[2rem] p-6 md:p-12 shadow-2xl relative overflow-hidden">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-10 gap-4">
                    <h2 class="text-xs font-black uppercase tracking-[0.3em] text-caramel-500 flex items-center gap-4 w-full sm:flex-1">
                        <span>Pricing & Stock</span>
                        <span class="h-px flex-1 bg-white/5"></span>
                    </h2>
                    
                    <button type="button" onclick="addVariant()" 
                        class="w-full sm:w-auto shrink-0 bg-caramel-500/10 hover:bg-caramel-500 text-caramel-500 hover:text-cocoa-950 border border-caramel-500/20 px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-widest transition-all active:scale-95">
                        + Add Tier
                    </button>
                </div>

                <div id="variant-container" class="space-y-6">
                    @foreach(old('variants', $product->variants) as $index => $variant)
                        <div class="relative group animate-slide-up">
                            @if($index > 0)
                                <button type="button" onclick="this.parentElement.remove()" 
                                    class="absolute -top-2 -right-2 z-10 bg-coral text-white h-6 w-6 rounded-full text-[10px] shadow-lg shadow-coral/20 transition-transform active:scale-90 flex items-center justify-center">
                                    ✕
                                </button>
                            @endif

                            @if(isset($variant->id))
                                <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">
                            @endif

                            <div class="bg-cocoa-800 border border-white/5 rounded-[1.5rem] p-6 transition-all hover:border-caramel-500/20">
                                <div class="grid grid-cols-1 sm:grid-cols-12 gap-6 items-end">
                                    {{-- Tier Name --}}
                                    <div class="col-span-1 sm:col-span-12 md:col-span-5 space-y-2">
                                        <label class="text-[10px] uppercase font-black tracking-widest text-white/60">Tier Name</label>
                                        <input type="text" name="variants[{{ $index }}][name]" 
                                            value="{{ is_array($variant) ? $variant['name'] : $variant->name }}" required
                                            oninput="this.value = this.value.replace(/\b\w/g, l => l.toUpperCase())"
                                            class="w-full bg-transparent border-b placeholder:text-white/30 border-white/10 py-2 text-sm focus:border-caramel-500 outline-none transition-all truncate">
                                    </div>
                                    
                                    {{-- Price --}}
                                    <div class="col-span-1 sm:col-span-4 md:col-span-2 space-y-2">
                                        <label class="text-[10px] uppercase font-black tracking-widest text-white/60">Price (RM)</label>
                                        <input type="number" step="0.01" name="variants[{{ $index }}][price]" 
                                            value="{{ is_array($variant) ? $variant['price'] : $variant->price }}" required
                                            class="w-full bg-transparent border-b border-white/10 placeholder:text-white/30 py-2 text-sm focus:border-caramel-500 outline-none transition-all">
                                    </div>

                                    {{-- Stock --}}
                                    <div class="col-span-1 sm:col-span-4 md:col-span-2 space-y-2">
                                        <label class="text-[10px] uppercase font-black tracking-widest text-white/60">Stock</label>
                                        <input type="number" name="variants[{{ $index }}][stock]" 
                                            value="{{ is_array($variant) ? $variant['stock'] : $variant->stock }}" required
                                            oninput="checkVariantStock(this)"
                                            class="w-full bg-transparent border-b border-white/10 placeholder:text-white/30 py-2 text-sm focus:border-caramel-500 outline-none transition-all">
                                    </div>

                                    {{-- Variant Visibility Toggle --}}
                                    <div class="col-span-1 sm:col-span-4 md:col-span-3 flex justify-end pb-2">
                                        <label class="relative inline-flex items-center cursor-pointer group">
                                            {{-- Hidden input ensures a '0' is sent if checkbox is unchecked --}}
                                            <input type="hidden" name="variants[{{ $index }}][is_active]" value="0">
                                            <input type="checkbox" name="variants[{{ $index }}][is_active]" value="1" 
                                                class="variant-status-checkbox sr-only peer" 
                                                {{ (is_array($variant) ? ($variant['is_active'] ?? false) : $variant->is_active) ? 'checked' : '' }}>
                                            
                                            <div class="w-10 h-5 bg-white/10 rounded-full peer peer-checked:bg-caramel-500 transition-all duration-300 
                                                        after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white 
                                                        after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                                            
                                            <span class="ml-3 text-[9px] font-black uppercase tracking-widest text-white/40 group-hover:text-caramel-400">
                                                Active
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="mt-12 pt-8 border-t border-white/5 flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <div class="grid grid-cols-1 sm:grid-cols-2 items-center gap-4">
                    <a href="{{ route('admin.ad_inventory') }}" 
                        class="order-2 sm:order-1 flex items-center justify-center w-full px-8 py-5 rounded-full border border-white/10 bg-white/5 text-[10px] font-black uppercase tracking-[0.3em] text-white/60 hover:bg-coral/10 hover:border-coral/50 hover:text-coral transition-all active:scale-95">
                        Cancel Changes
                    </a>
                    <button type="submit" class="order-1 sm:order-2 w-full p-[1px] bg-gradient-to-r from-caramel-500 to-caramel-400 rounded-full group transition-transform active:scale-95 shadow-lg shadow-caramel-500/10">
                        <div class="bg-cocoa-950 group-hover:bg-transparent px-8 py-5 rounded-full transition-all duration-300 flex items-center justify-center">
                            <span class="text-xs font-black uppercase tracking-[0.25em] text-white group-hover:text-cocoa-950 transition-colors whitespace-nowrap">
                                Save Changes
                            </span>
                        </div>
                    </button>
                </div>
            </div>
        </form>
    </main>

    <script>
        function toggleNewCategory(val) {
            const wrapper = document.getElementById('newCategoryWrapper');
            const input = document.getElementById('newCategoryInput');
            if (val === "NEW") {
                wrapper.classList.remove('hidden');
                input.setAttribute('required', 'required');
                input.focus();
            } else {
                wrapper.classList.add('hidden');
                input.removeAttribute('required');
                input.value = "";
            }
        }

        let vIdx = {{ count(old('variants', $product->variants)) }};
        function addVariant() {
            const container = document.getElementById('variant-container');
            const div = document.createElement('div');
            div.className = "relative group animate-slide-up";
            div.innerHTML = `
                <button type="button" onclick="this.parentElement.remove()" 
                    class="absolute -top-2 -right-2 z-10 bg-coral text-white h-6 w-6 rounded-full text-[10px] shadow-lg shadow-coral/20 transition-transform active:scale-90 flex items-center justify-center">
                    ✕
                </button>
                <div class="bg-cocoa-800 border border-white/5 rounded-[1.5rem] p-6 transition-all hover:border-caramel-500/20">
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-6 items-end">
                        <div class="col-span-1 sm:col-span-12 md:col-span-5 space-y-2">
                            <label class="text-[10px] uppercase font-black tracking-widest text-white/60">Tier Name</label>
                            <input type="text" name="variants[${vIdx}][name]" placeholder="e.g. Medium Pack" required
                                oninput="this.value = this.value.replace(/\\b\\w/g, l => l.toUpperCase())"
                                class="w-full bg-transparent border-b border-white/10 py-2 text-sm focus:border-caramel-500 outline-none transition-all truncate">
                        </div>
                        <div class="col-span-1 sm:col-span-4 md:col-span-2 space-y-2">
                            <label class="text-[10px] uppercase font-black tracking-widest text-white/60">Price (RM)</label>
                            <input type="number" step="0.01" name="variants[${vIdx}][price]" placeholder="0.00" required
                                class="w-full bg-transparent border-b border-white/10 py-2 text-sm focus:border-caramel-500 outline-none transition-all">
                        </div>
                        <div class="col-span-1 sm:col-span-4 md:col-span-2 space-y-2">
                            <label class="text-[10px] uppercase font-black tracking-widest text-white/60">Stock</label>
                            <input type="number" name="variants[${vIdx}][stock]" placeholder="0" required
                                oninput="checkVariantStock(this)"
                                class="w-full bg-transparent border-b border-white/10 py-2 text-sm focus:border-caramel-500 outline-none transition-all">
                        </div>
                        <div class="col-span-1 sm:col-span-4 md:col-span-3 flex justify-end pb-2">
                            <label class="relative inline-flex items-center cursor-pointer group">
                                <input type="hidden" name="variants[${vIdx}][is_active]" value="0">
                                <input type="checkbox" name="variants[${vIdx}][is_active]" value="1" class="variant-status-checkbox sr-only peer" checked>
                                <div class="w-10 h-5 bg-white/10 rounded-full peer peer-checked:bg-caramel-500 transition-all duration-300 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                                <span class="ml-3 text-[9px] font-black uppercase tracking-widest text-white/40 group-hover:text-caramel-400 transition-colors">Visible</span>
                            </label>
                        </div>
                    </div>
                </div>`;
            container.appendChild(div);
            vIdx++;
        }

        function checkVariantStock(inputElement) {
            // Find the specific grid row containing this input
            const variantRow = inputElement.closest('.grid');
            const statusCheckbox = variantRow.querySelector('.variant-status-checkbox');
            
            // Auto-toggle based on stock count
            if (parseInt(inputElement.value) > 0) {
                statusCheckbox.checked = true;
            } else {
                statusCheckbox.checked = false;
            }
        }

        function updateAssetPreview(filename) {
            const preview = document.getElementById('imagePreview');
            const placeholder = document.getElementById('previewPlaceholder');
            if (filename.trim() !== "") {
                const fullPath = `/assets/${filename.replace(/^assets\//, '')}`; 
                preview.src = fullPath;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
                preview.onerror = () => {
                    preview.classList.add('hidden');
                    placeholder.classList.remove('hidden');
                    placeholder.querySelector('p').innerText = "Asset Not Found";
                };
            }
        }

        window.onload = () => {
            if(document.getElementById('categorySelect').value === 'NEW') toggleNewCategory('NEW');
        };
    </script>
</body>
</html>