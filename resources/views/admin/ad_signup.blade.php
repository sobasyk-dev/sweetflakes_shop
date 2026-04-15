<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Enrollment | Sweetflakes Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cocoa: { 950: "#0a0705", 900: "#120d0a", 800: "#1c1410" },
                        coral: "#f85858",
                    }
                }
            }
        }
    </script>
    <style>
        .artisan-input {
            transition: all 0.3s ease;
            border: 1px solid rgba(248, 88, 88, 0.1);
        }
        .artisan-input:focus {
            border-color: #f85858;
            background-color: #1c1410;
            outline: none;
            box-shadow: 0 0 15px rgba(248, 88, 88, 0.1);
        }
    </style>
</head>
<body class="bg-cocoa-950 text-white min-h-screen flex items-center justify-center p-6">

    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <p class="text-coral text-[10px] font-bold uppercase tracking-[0.5em] mb-2">Internal System</p>
            <h1 class="text-4xl font-serif italic text-white">Staff Enrollment</h1>
        </div>

        <x-alerts />

        <form action="{{ route('admin.adminSignup') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="role" value="admin">
            
            <div class="bg-cocoa-900 border border-white/5 rounded-[2.5rem] p-8 shadow-2xl space-y-4">
                
                <div class="space-y-1">
                    <label class="text-[9px] font-black tracking-widest text-white/60 ml-1 uppercase">Full Name</label>
                    <input type="text" name="name" required value="{{ old('name') }}" placeholder="E.g. Alexander Sweet"
                        oninput="this.value = this.value.replace(/\b\w/g, l => l.toUpperCase())"
                        class="artisan-input w-full bg-cocoa-800 rounded-2xl px-6 py-4 text-sm text-white placeholder:text-white/20 outline-none">
                </div>

                <div class="space-y-1">
                    <label class="text-[9px] font-black tracking-widest text-white/60 ml-1 uppercase">Work Mobile</label>
                    <input type="tel" name="phone" required value="{{ old('phone') }}" placeholder="e.g. 60123456789"
                        class="artisan-input w-full bg-cocoa-800 rounded-2xl px-6 py-4 text-sm text-white placeholder:text-white/20 outline-none">
                </div>

                <div class="space-y-1">
                    <label class="text-[9px] font-bold tracking-widest text-white/60 ml-1 uppercase">Work Email</label>
                    <input type="email" name="email" required value="{{ old('email') }}" placeholder="staff@sweetflakes.com"
                        class="artisan-input w-full bg-cocoa-800 rounded-2xl px-6 py-4 text-sm text-white placeholder:text-white/20 outline-none">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[9px] font-black tracking-widest text-white/60 ml-1 uppercase">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required placeholder="••••••••"
                                class="artisan-input w-full bg-cocoa-800 rounded-2xl px-6 py-4 text-sm text-white placeholder:text-white/20 outline-none pr-12">
                            <button type="button" onclick="togglePass('password', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-white/20 hover:text-coral transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </button>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black tracking-widest text-white/60 ml-1 uppercase">Confirm</label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="••••••••"
                                class="artisan-input w-full bg-cocoa-800 rounded-2xl px-6 py-4 text-sm text-white placeholder:text-white/20 outline-none pr-12">
                            <button type="button" onclick="togglePass('password_confirmation', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-white/20 hover:text-coral transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-white/5 space-y-1">
                    <label class="text-[9px] uppercase font-bold tracking-widest text-coral ml-1">Staff Authorization Key</label>
                    <input type="password" name="admin_secret" required placeholder="Enter Secret Code"
                        class="artisan-input w-full bg-cocoa-800 border-coral/20 rounded-2xl px-6 py-4 text-sm text-white placeholder:text-coral/20 outline-none">
                    <p class="text-[8px] text-white/30 italic ml-2 mt-1">* This account requires administrative clearance.</p>
                </div>
            </div>

            <button type="submit" class="w-full bg-white border-2 border-[red] text-cocoa-950 text-[11px] font-black uppercase tracking-[0.3em] py-5 rounded-full transition-all active:scale-95 shadow-lg">
                Authorize & Enroll Staff
            </button>
        </form>
    </div>

    <script>
        function togglePass(inputId, btn) {
            const input = document.getElementById(inputId);
            const eyeIcon = btn.querySelector('svg');
            
            if (input.type === "password") {
                input.type = "text";
                btn.classList.add('text-coral');
                btn.classList.remove('text-white/20');
            } else {
                input.type = "password";
                btn.classList.remove('text-coral');
                btn.classList.add('text-white/20');
            }
        }
    </script>
</body>
</html>