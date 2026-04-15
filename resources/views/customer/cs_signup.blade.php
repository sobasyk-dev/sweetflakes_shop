<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Sweetflakes | Artisan Patisserie</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cocoa: { 950: "#0a0705", 900: "#120d0a", 800: "#1c1410" },
                        caramel: { 400: "#e3bc94", 500: "#d2a679" },
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
    </style>
</head>
<body class="bg-cocoa-950 text-white font-sans antialiased min-h-screen flex items-center justify-center p-6">
    <x-alerts />

    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <p class="text-caramel-500 text-[10px] font-black uppercase tracking-[0.4em] mb-4">Artisan Journey</p>
            <h1 class="serif-italic text-5xl mb-2">Create Account</h1>
            <div class="h-px w-16 bg-gradient-to-r from-transparent via-caramel-500 to-transparent mx-auto mt-6"></div>
        </div>

        <form action="{{ route('customer.customerSignup') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="role" value="customer">
            
            <div class="bg-cocoa-900 border border-white/5 rounded-[2.5rem] p-8 shadow-2xl space-y-4">
                <div class="space-y-1">
                    <label class="text-[9px] font-black tracking-widest text-white/40 ml-1 capitalize">Full Name</label>
                    <input type="text" name="name" required value="{{ old('name') }}" placeholder="E.g. Alexander Sweet"
                        oninput="this.value = this.value.replace(/\b\w/g, l => l.toUpperCase())"
                        class="artisan-input w-full bg-cocoa-800 rounded-2xl px-6 py-4 text-sm text-white placeholder:text-white/10 outline-none">
                </div>

                <div class="space-y-1">
                    <label class="text-[9px] font-black tracking-widest text-white/40 ml-1 uppercase">Mobile Number</label>
                    <input type="tel" name="phone" required value="{{ old('phone') }}" placeholder="e.g. 60123456789"
                        class="artisan-input w-full bg-cocoa-800 rounded-2xl px-6 py-4 text-sm text-white placeholder:text-white/10 outline-none">
                </div>

                <div class="space-y-1">
                    <label class="text-[9px] font-bold tracking-widest text-white/40 ml-1 uppercase">
                        Work Email <span class="lowercase opacity-50">(optional)</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="staff@sweetflakes.com"
                        class="artisan-input w-full bg-cocoa-800 rounded-2xl px-6 py-4 text-sm text-white placeholder:text-white/10 outline-none">
                </div>

                <div class="space-y-1">
                    <label class="text-[9px] font-black tracking-widest text-white/40 ml-1">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required placeholder="••••••••"
                            class="artisan-input w-full bg-cocoa-800 rounded-2xl px-6 py-4 text-sm text-white placeholder:text-white/10 outline-none pr-14">
                        <button type="button" onclick="togglePass('password', this)" class="absolute right-6 top-1/2 -translate-y-1/2 text-white/20 hover:text-caramel-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </button>
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-[9px] font-black tracking-widest text-white/40 ml-1">Confirm Password</label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="••••••••"
                            class="artisan-input w-full bg-cocoa-800 rounded-2xl px-6 py-4 text-sm text-white placeholder:text-white/10 outline-none pr-14">
                        <button type="button" onclick="togglePass('password_confirmation', this)" class="absolute right-6 top-1/2 -translate-y-1/2 text-white/20 hover:text-caramel-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </button>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full p-[1px] bg-gradient-to-r from-caramel-500 to-caramel-400 rounded-full group transition-transform active:scale-95 shadow-lg shadow-caramel-500/10">
                <div class="bg-cocoa-950 group-hover:bg-transparent px-8 py-5 rounded-full transition-all duration-300 flex items-center justify-center">
                    <span class="text-xs font-black uppercase tracking-[0.25em] text-white group-hover:text-cocoa-950 transition-colors">Join the family</span>
                </div>
            </button>
        </form>

        <p class="text-center mt-10 text-[10px] uppercase tracking-widest text-white/20">
            Already registered? <a href="{{ route('customer.cs_login') }}" class="text-caramel-500 hover:text-caramel-400 transition-colors underline underline-offset-8 decoration-white/5">Sign In</a>
        </p>
    </div>

    <script>
        function togglePass(id, btn) {
            const input = document.getElementById(id);
            input.type = input.type === "password" ? "text" : "password";
            btn.classList.toggle('text-caramel-500');
        }
    </script>
</body>
</html>