<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Creation | Sweetflakes Dessert</title>

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
        .artisan-input.is-invalid {
            border-color: #f85858 !important;
            box-shadow: 0 0 15px rgba(248, 88, 88, 0.1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-up { animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1); }
        [x-cloak] { display: none !important; }
        
        /* Custom scrollbar for textareas to match the theme */
        textarea::-webkit-scrollbar { width: 4px; }
        textarea::-webkit-scrollbar-thumb { background: #d2a679; border-radius: 10px; }
    </style>
</head>

<body class="bg-cocoa-950 text-white font-sans antialiased selection:bg-caramel-500/30 flex flex-col min-h-screen overflow-x-hidden">

    <x-ad_header />

    <main class="max-w-4xl mx-auto px-4 md:px-6 py-8 relative flex-grow w-full">

        <x-alerts />

        <div class="text-center mb-10">
            <p class="text-caramel-500 text-[10px] font-black uppercase tracking-[0.4em] mb-4">Artisan Patisserie</p>
            <h1 class="serif-italic text-4xl md:text-6xl mb-2">New Creation</h1>
            <div class="h-px w-20 bg-gradient-to-r from-transparent via-caramel-500 to-transparent mx-auto mt-6"></div>
        </div>

        <form action="{{ route('admin.ad_store') }}" method="POST" class="space-y-8 mb-12">
            @csrf

            {{-- Section: Identity --}}
            <div class="bg-cocoa-900 border border-white/5 rounded-[2rem] p-6 md:p-12 shadow-2xl">
                <h2 class="text-xs font-black uppercase tracking-[0.3em] text-caramel-500 mb-10 flex items-center gap-4">
                    <span>Identity</span>
                    <span class="h-px flex-1 bg-white/5"></span>
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] uppercase font-black tracking-widest text-white/60 ml-1">Product Name</label>
                        <input type="text" name="name" required placeholder="e.g. Puding Coklat Small"
                            oninput="this.value = this.value.replace(/\b\w/g, l => l.toUpperCase())"
                            class="artisan-input w-full bg-cocoa-800 rounded-2xl px-6 py-4 text-sm placeholder:text-white/30 truncate">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] uppercase font-black tracking-widest text-white/60 ml-1">Category</label>
                        <div class="space-y-4">
                            <select name="category_id" id="categorySelect" onchange="toggleNewCategory(this.value)" required
                                class="artisan-input w-full bg-cocoa-800 text-white/40 placeholder:text-white/30 rounded-2xl px-6 py-4 text-sm appearance-none cursor-pointer">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                                <option value="NEW" class="text-caramel-500 font-bold">+ Define New Category</option>
                            </select>

                            <div id="newCategoryWrapper" class="hidden animate-slide-up">
                                <label class="text-[10px] uppercase font-black tracking-widest text-white/60 ml-1">New Category Name</label>
                                <input type="text" name="new_category_name" id="newCategoryInput" placeholder="e.g. Luxury Tarts"
                                    class="artisan-input w-full bg-cocoa-800 border-caramel-500/30 rounded-2xl px-6 py-4 text-sm mt-1 placeholder:text-white/30">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 space-y-2">
                    <label class="text-[10px] uppercase font-black tracking-widest text-white/60 ml-1">Product Description</label>
                    <textarea name="description" rows="3" placeholder="Describe the craft..."
                        class="artisan-input w-full bg-cocoa-800 placeholder:text-white/30 rounded-2xl px-6 py-4 text-sm resize-none focus:ring-0"></textarea>
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
                        <img id="imagePreview" src="" 
                            class="w-full h-full object-cover hidden transition-opacity duration-500" alt="Preview">
                        
                        <div id="previewPlaceholder" class="text-center p-6">
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
                                <input type="text" name="image" id="imageInput" 
                                    placeholder="dessert-name.jpg" 
                                    required
                                    oninput="updateAssetPreview(this.value)"
                                    class="w-full bg-transparent px-2 py-5 text-sm placeholder:text-white/10 outline-none border-none truncate">
                            </div>
                            <div class="flex items-start gap-2 ml-1">
                                <span class="text-caramel-500 text-[10px]">ℹ</span>
                                <p class="text-[9px] text-white/30 leading-relaxed uppercase tracking-wider">
                                    Enter the filename located in your <code class="text-caramel-400">public/assets/</code> directory. 
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section: Pricing & Stock --}}
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
                    {{-- Initial Variant Row --}}
                    <div class="bg-cocoa-800 border border-white/5 rounded-[1.5rem] p-6 group transition-all">
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-6 items-end">
                            <div class="col-span-1 sm:col-span-12 md:col-span-4 space-y-2">
                                <label class="text-[10px] uppercase font-black tracking-widest text-white/60">Tier Name</label>
                                <input type="text" name="variants[0][name]" placeholder="e.g. Small Pack" required
                                    oninput="this.value = this.value.replace(/\b\w/g, l => l.toUpperCase())"
                                    class="w-full bg-transparent border-b placeholder:text-white/30 border-white/10 py-2 text-sm focus:border-caramel-500 outline-none transition-all truncate">
                            </div>
                            <div class="col-span-1 sm:col-span-5 md:col-span-3 space-y-2">
                                <label class="text-[10px] uppercase font-black tracking-widest text-white/60">Price (RM)</label>
                                <input type="number" step="0.01" name="variants[0][price]" placeholder="0.00" required
                                    class="w-full bg-transparent border-b border-white/10 placeholder:text-white/30 py-2 text-sm focus:border-caramel-500 outline-none transition-all">
                            </div>
                            <div class="col-span-1 sm:col-span-4 md:col-span-2 space-y-2">
                                <label class="text-[10px] uppercase font-black tracking-widest text-white/60">Stock</label>
                                <input type="number" name="variants[0][stock]" placeholder="0" required
                                    class="w-full bg-transparent border-b border-white/10 placeholder:text-white/30 py-2 text-sm focus:border-caramel-500 outline-none transition-all">
                            </div>
                            {{-- Variant Visibility Toggle --}}
                            <div class="col-span-1 sm:col-span-3 md:col-span-3 flex justify-end pb-2">
                                <label class="relative inline-flex items-center cursor-pointer group">
                                    <input type="hidden" name="variants[0][is_active]" value="0">
                                    <input type="checkbox" name="variants[0][is_active]" value="1" class="sr-only peer" checked>
                                    <div class="w-10 h-5 bg-white/10 rounded-full peer peer-checked:bg-caramel-500 transition-all duration-300 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                                    <span class="ml-3 text-[9px] font-black uppercase tracking-widest text-white/40 group-hover:text-caramel-400">Active</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="mt-12 pt-8 border-t border-white/5 flex flex-col gap-6 md:flex-row md:items-center md:justify-between">

                <div class="grid grid-cols-1 sm:grid-cols-2 items-center gap-4">
                    <a href="{{ route('admin.ad_inventory') }}" 
                        class="order-2 sm:order-1 flex items-center justify-center w-full px-8 py-5 rounded-full border border-white/10 bg-white/5 text-[10px] font-black uppercase tracking-[0.3em] text-white/60 hover:bg-coral/10 hover:border-coral/50 hover:text-coral transition-all active:scale-95">
                        Cancel Creation
                    </a>
                    <button type="submit" class="order-1 sm:order-2 w-full p-[1px] bg-gradient-to-r from-caramel-500 to-caramel-400 rounded-full group transition-transform active:scale-95 shadow-lg shadow-caramel-500/10">
                        <div class="bg-cocoa-950 group-hover:bg-transparent px-8 py-5 rounded-full transition-all duration-300 flex items-center justify-center">
                            <span class="text-xs font-black uppercase tracking-[0.25em] text-white group-hover:text-cocoa-950 transition-colors whitespace-nowrap">
                                Finalize Creation
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

        let vIdx = 1;
        function addVariant() {
            const container = document.getElementById('variant-container');
            const div = document.createElement('div');
            div.className = "relative group animate-slide-up";
            div.innerHTML = `
                <button type="button" onclick="this.parentElement.remove()" 
                    class="absolute -top-2 -right-2 z-10 bg-coral text-white h-6 w-6 rounded-full text-[10px] shadow-lg shadow-coral/20 transition-transform active:scale-90 flex items-center justify-center">
                    ✕
                </button>
                <div class="bg-cocoa-800 border border-white/5 rounded-[1.5rem] p-6 group transition-all">
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-6 items-end">
                        <div class="col-span-1 sm:col-span-12 md:col-span-4 space-y-2">
                            <label class="text-[10px] uppercase font-black tracking-widest text-white/60">Tier Name</label>
                            <input type="text" name="variants[${vIdx}][name]" placeholder="e.g. Medium Pack" required
                                oninput="this.value = this.value.replace(/\\b\\w/g, l => l.toUpperCase())"
                                class="w-full bg-transparent border-b placeholder:text-white/30 border-white/10 py-2 text-sm focus:border-caramel-500 outline-none transition-all truncate">
                        </div>
                        <div class="col-span-1 sm:col-span-5 md:col-span-3 space-y-2">
                            <label class="text-[10px] uppercase font-black tracking-widest text-white/60">Price (RM)</label>
                            <input type="number" step="0.01" name="variants[${vIdx}][price]" placeholder="0.00" required
                                class="w-full bg-transparent border-b border-white/10 placeholder:text-white/30 py-2 text-sm focus:border-caramel-500 outline-none transition-all">
                        </div>
                        <div class="col-span-1 sm:col-span-4 md:col-span-2 space-y-2">
                            <label class="text-[10px] uppercase font-black tracking-widest text-white/60">Stock</label>
                            <input type="number" name="variants[${vIdx}][stock]" placeholder="0" required
                                class="w-full bg-transparent border-b border-white/10 placeholder:text-white/30 py-2 text-sm focus:border-caramel-500 outline-none transition-all">
                        </div>
                        <div class="col-span-1 sm:col-span-3 md:col-span-3 flex justify-end pb-2">
                            <label class="relative inline-flex items-center cursor-pointer group">
                                <input type="hidden" name="variants[${vIdx}][is_active]" value="0">
                                <input type="checkbox" name="variants[${vIdx}][is_active]" value="1" class="sr-only peer" checked>
                                <div class="w-10 h-5 bg-white/10 rounded-full peer peer-checked:bg-caramel-500 transition-all duration-300 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                                <span class="ml-3 text-[9px] font-black uppercase tracking-widest text-white/40 group-hover:text-caramel-400">Active</span>
                            </label>
                        </div>
                    </div>
                </div>`;
            container.appendChild(div);
            vIdx++;
        }

        function updateAssetPreview(filename) {
            const preview = document.getElementById('imagePreview');
            const placeholder = document.getElementById('previewPlaceholder');
            if (filename.trim() !== "") {
                const fullPath = `/assets/${filename.replace(/^assets\//, '')}`; 
                preview.src = fullPath;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
                preview.onerror = function() {
                    preview.classList.add('hidden');
                    placeholder.classList.remove('hidden');
                    placeholder.querySelector('p').innerText = "Asset Not Found";
                };
            } else {
                preview.classList.add('hidden');
                placeholder.classList.remove('hidden');
                placeholder.querySelector('p').innerText = "Awaiting Asset Path";
            }
        }
    </script>
</body>
</html>