<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Sign In | Sweetflakes Artisan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cocoa: { 950: "#0a0705", 900: "#120d0a", 800: "#1c1410" },
                        caramel: { 500: "#d2a679" },
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-cocoa-950 text-white min-h-screen flex items-center justify-center p-6">

    <div class="max-w-md w-full">
        <div class="text-center mb-6">
            <p class="text-caramel-500 text-[10px] font-black uppercase tracking-[0.4em] mb-4">Member Access</p>
            <h1 class="font-serif italic text-5xl text-cream mb-2">Welcome Back</h1>
            <p class="text-[10px] uppercase tracking-[0.4em] text-white/30 font-bold">Sweet moments await</p>
        </div>

        <x-alerts />

        <form action="{{ route('customer.loginProcess') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="bg-cocoa-900 border border-white/5 rounded-[2.5rem] p-8 shadow-2xl space-y-5">
                <div class="space-y-2">
                    <label class="text-[9px] font-bold tracking-widest text-white/40 ml-1 uppercase">Full Name</label>
                    <input type="text" name="name" required value="{{ old('name') }}"
                        placeholder="Enter your full name"
                        oninput="this.value = this.value.replace(/\b\w/g, l => l.toUpperCase())"
                        class="w-full bg-cocoa-800 border border-white/5 focus:border-caramel-500 rounded-2xl px-6 py-4 text-sm text-white outline-none transition-all placeholder:text-white/10">
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center px-1">
                        <label class="text-[9px] uppercase font-bold tracking-widest text-white/40">Password</label>
                        <!-- <a href="#" class="text-[9px] uppercase font-bold tracking-widest text-caramel-500 hover:text-white transition-colors">Forgot?</a> -->
                    </div>
                    <div class="relative">
                        <input type="password" id="login_pass" name="password" required
                            placeholder="••••••••"
                            class="w-full bg-cocoa-800 border border-white/5 focus:border-caramel-500 rounded-2xl px-6 py-4 text-sm text-white outline-none transition-all pr-14 placeholder:text-white/10">
                        <button type="button" onclick="toggleLoginPass()" class="absolute right-6 top-1/2 -translate-y-1/2 text-white/20 hover:text-caramel-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-caramel-500 hover:bg-white text-cocoa-950 text-[11px] font-black uppercase tracking-[0.3em] py-5 rounded-full transition-all active:scale-95 shadow-lg shadow-caramel-500/10">
                Sign In to Account
            </button>
        </form>

        <p class="text-center mt-10 text-[10px] uppercase tracking-widest text-white/20">
            New to Sweetflakes? <a href="{{ route('customer.cs_signup') }}" class="text-caramel-500 hover:text-white transition-colors underline underline-offset-4 decoration-white/5">Create Account</a>
        </p>
    </div>

    <script>
        function toggleLoginPass() {
            const input = document.getElementById('login_pass');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>