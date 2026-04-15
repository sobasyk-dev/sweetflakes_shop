<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sweetflakes | Secure Payment</title>
    <x-tailwind />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,700&family=Inter:wght@400;600;800&display=swap');
        body { background-color: #0d0a09; }
        .glass-vault {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.03), rgba(255, 255, 255, 0.01));
            backdrop-filter: blur(20px);
            border: 1px solid rgba(210, 166, 121, 0.1);
        }
        .active-tab {
            background: #d2a679 !important;
            color: #0d0a09 !important;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(210, 166, 121, 0.2);
        }
        .input-premium {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .input-premium:focus { border-color: #d2a679; outline: none; }
    </style>
</head>

<body class="min-h-screen text-cream font-sans overflow-x-hidden">
    <x-cs_header />
    <x-alerts />

    <main class="mx-auto max-w-3xl px-6 py-8">
        <div class="mb-8 text-center">
            <h1 class="font-serif text-5xl italic text-cream">Checkout</h1>
            <p class="text-[10px] text-caramel-400/60 uppercase tracking-[0.4em] mt-3">Ref: SF-{{ date('ymd-Hi') }}</p>
        </div>

        <div class="glass-vault rounded-[2.5rem] p-8 sm:p-10 mb-10 relative overflow-hidden border-caramel-500/30">
            <div class="text-center mb-8">
                <p class="text-[10px] text-cream/30 uppercase font-black tracking-[0.3em]">Amount Payable Now</p>
                <div class="flex items-center justify-center gap-2 mt-2">
                    <span class="text-2xl font-medium text-caramel-400">RM</span>
                    <h2 id="grand-total-display" class="text-6xl sm:text-7xl font-black text-cream tracking-tighter transition-all duration-500">
                        {{ number_format($amountToPay, 2) }}
                    </h2>
                </div>
            </div>

            <div class="space-y-4 max-w-sm mx-auto mb-8">
                <div class="flex justify-between items-center text-[10px] font-bold uppercase tracking-widest text-cream/40 px-1">
                    <span>Total Order Value</span>
                    <span class="text-cream">RM {{ number_format($amountToPay, 2) }}</span>
                </div>

                <div id="breakdown-balance-row" class="flex justify-between items-center pt-4 border-t border-dashed border-white/10 px-1 hidden">
                    <div class="flex flex-col text-left">
                        <span class="text-[9px] font-black uppercase tracking-widest text-caramel-500">Future Balance</span>
                        <span class="text-[7px] text-white/30 font-medium uppercase tracking-tight">To be settled later</span>
                    </div>
                    <span id="breakdown-balance-due" class="text-lg font-black text-caramel-500">RM 0.00</span>
                </div>
            </div>

            <div class="flex bg-black/40 p-1 rounded-2xl max-w-xs mx-auto border border-white/5">
                <button type="button" onclick="updatePlan('full')" id="plan-full" 
                    class="flex-1 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all duration-300 {{ $paymentType === 'full' ? 'bg-caramel-400 text-cocoa-950 shadow-lg' : 'text-cream/30' }}">
                    Full Pay
                </button>
                <button type="button" onclick="updatePlan('deposit')" id="plan-deposit" 
                    class="flex-1 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all duration-300 {{ $paymentType === 'deposit' ? 'bg-caramel-400 text-cocoa-950 shadow-lg' : 'text-cream/30' }}">
                    30% Deposit
                </button>
            </div>
        </div>

       <form action="{{ route('customer.storeOrder') }}" method="POST" enctype="multipart/form-data" id="payment-form">
            @csrf
            
            <input type="hidden" name="payment_type" id="payment_type_input" value="{{ $paymentType }}">
            <input type="hidden" name="amount_paid" id="amount_paid_input" value="{{ $amountToPay }}">
            <input type="hidden" id="selected_method" name="payment_method" value="qr">

            <div class="flex bg-white/5 p-1.5 rounded-[2rem] gap-2 mb-10 border border-white/5">
                <button type="button" onclick="switchPayment('qr')" id="btn-qr" class="payment-tab active-tab flex-1 flex flex-col items-center justify-center py-4 rounded-[1.5rem] transition-all duration-500">
                    <span class="text-xl mb-1">📱</span>
                    <span class="text-[9px] font-black uppercase tracking-widest">DuitNow QR</span>
                </button>
                <button type="button" onclick="switchPayment('transfer')" id="btn-transfer" class="payment-tab flex-1 flex flex-col items-center justify-center py-4 rounded-[1.5rem] text-cream/30 hover:bg-white/5 transition-all duration-500">
                    <span class="text-xl mb-1">🏦</span>
                    <span class="text-[9px] font-black uppercase tracking-widest">Bank Transfer</span>
                </button>
                <button type="button" onclick="switchPayment('cash')" id="btn-cash" class="payment-tab flex-1 flex flex-col items-center justify-center py-4 rounded-[1.5rem] text-cream/30 hover:bg-white/5 transition-all duration-500">
                    <span class="text-xl mb-1">💵</span>
                    <span class="text-[9px] font-black uppercase tracking-widest">Cash</span>
                </button>
            </div>

            <div class="space-y-6 bg-white/[0.02] border border-white/5 rounded-[3rem] p-10 shadow-2xl">
                <h3 class="font-serif text-2xl italic text-caramel-400">Fulfillment Details</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-cream/50 uppercase tracking-widest ml-1">
                            Recipient Name
                        </label>
                        <input type="text" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required
                            placeholder="E.g. John Doe"
                            oninput="this.value = this.value.replace(/\b\w/g, l => l.toUpperCase())"
                            class="w-full bg-white/[0.03] border {{ $errors->has('name') ? 'border-red-500/50' : 'border-white/10' }} rounded-2xl px-5 py-4 text-sm text-cream outline-none focus:border-caramel-500/50 focus:bg-white/[0.05] transition-all placeholder:text-cream/20">
                        
                        @error('name')
                            <p class="text-[9px] text-red-400 font-bold uppercase mt-1 ml-1 tracking-wider">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-cream/50 uppercase tracking-widest ml-1">
                            Mobile Number
                        </label>
                        <input type="tel" 
                            name="phone" 
                            value="{{ old('phone') }}"
                            required
                            placeholder="E.g. 60123456789"
                            class="w-full bg-white/[0.03] border {{ $errors->has('phone') ? 'border-red-500/50' : 'border-white/10' }} rounded-2xl px-5 py-4 text-sm text-cream outline-none focus:border-caramel-500/50 focus:bg-white/[0.05] transition-all placeholder:text-cream/20">
                        
                        @error('phone')
                            <p class="text-[9px] text-red-400 font-bold uppercase mt-1 ml-1 tracking-wider">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                @if(session('order_method') !== 'pickup')
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-cream/50 uppercase">Delivery Destination</label>
                    <textarea name="address" rows="3" oninput="this.value = this.value.replace(/\b\w/g, l => l.toUpperCase())" required class="w-full input-premium rounded-2xl border border-white/10 py-4 px-6 text-sm text-cream placeholder:text-cream/20" placeholder="Enter full address"></textarea>
                </div>
                @endif

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-cream/50 ml-1">Special Instructions (Optional)</label>
                    <textarea 
                        name="notes" 
                        rows="2" 
                        class="w-full input-premium rounded-2xl border border-white/10 placeholder:text-cream/20 py-4 px-6 text-sm text-cream" 
                        placeholder="E.g. Specific delivery time, allergies, or gift messages..."></textarea>
                </div>

                <div id="section-qr" class="payment-section text-center py-4">
                    <img src="{{ asset('assets/qr.jpeg') }}" class="w-48 h-48 mx-auto rounded-xl bg-white p-2 mb-4">
                    <p class="text-cream/50 text-xs">Scan via DuitNow QR App</p>
                </div>

                <div id="section-transfer" class="payment-section hidden bg-black/40 p-6 rounded-2xl">
                    <p class="text-[9px] uppercase text-caramel-400 font-black">Maybank Business</p>
                    <p class="text-2xl text-cream font-mono">5122 4321 8890</p>
                </div>

                <div id="section-cash" class="payment-section hidden text-center py-6">
                    <p class="text-cream/60 italic">Pay cash upon collection/delivery.</p>
                </div>

                <div id="receipt-area" class="mb-4">
                    <label class="block text-cream text-sm mb-2">Upload Receipt</label>
                    
                    <input type="file" 
                        name="payment_receipt" 
                        id="receipt-input"
                        onchange="updateFileName(this)"
                        class="w-full bg-white/5 border @error('payment_receipt') border-red-500 @else border-white/10 @enderror rounded-xl p-3 text-cream">
                    
                    <p id="file-label" class="text-[10px] mt-2 text-cream/40 uppercase tracking-widest font-bold">No file chosen</p>

                    @error('payment_receipt')
                        <p class="text-red-500 text-[10px] mt-1 font-bold italic">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-caramel-400 text-cocoa-950 py-5 rounded-2xl font-black uppercase tracking-[0.2em] text-xs">
                    Finalize Order
                </button>
                <a href="{{ route('customer.cs_menu') }}" class="block w-full text-center mt-5 text-white/40 hover:text-caramel-400 font-black uppercase tracking-widest text-[9px] transition-colors">
                    ← Cancel
                </a>
            </div>
        </form>
    </main>

    <script>
        // Calculate total order value once
        const totalOrderValue = {{ $amountToPay }}; 

        function updatePlan(plan) {
            const typeInput = document.getElementById('payment_type_input');
            const amountInput = document.getElementById('amount_paid_input');
            const grandDisplay = document.getElementById('grand-total-display');
            
            // Breakdown Elements
            const balanceRow = document.getElementById('breakdown-balance-row');
            const balanceLabel = document.getElementById('breakdown-balance-due');
            
            // Buttons
            const btnFull = document.getElementById('plan-full');
            const btnDep = document.getElementById('plan-deposit');

            // Calculations
            const amountNow = (plan === 'deposit') ? (totalOrderValue * 0.30) : totalOrderValue;
            const amountRemaining = totalOrderValue - amountNow;

            // Update Hidden Form Fields
            typeInput.value = plan;
            amountInput.value = amountNow.toFixed(2);

            // Update Main Display
            grandDisplay.innerText = amountNow.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            // Toggle Balance View and Button Styles
            if (plan === 'deposit') {
                balanceRow.classList.remove('hidden');
                balanceLabel.innerText = "RM " + amountRemaining.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                
                btnDep.className = "flex-1 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all duration-300 bg-caramel-400 text-cocoa-950 shadow-lg";
                btnFull.className = "flex-1 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all duration-300 text-cream/30";
            } else {
                balanceRow.classList.add('hidden');
                
                btnFull.className = "flex-1 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all duration-300 bg-caramel-400 text-cocoa-950 shadow-lg";
                btnDep.className = "flex-1 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all duration-300 text-cream/30";
            }
        }

        function switchPayment(type) {
            const selectedInput = document.getElementById('selected_method');
            if (selectedInput) selectedInput.value = type;
            
            // 1. Update Tabs
            document.querySelectorAll('.payment-tab').forEach(t => {
                t.classList.remove('active-tab');
                t.classList.add('text-cream/30');
            });

            const activeBtn = document.getElementById('btn-' + type);
            if (activeBtn) {
                activeBtn.classList.add('active-tab');
                activeBtn.classList.remove('text-cream/30');
            }

            // 2. Update Sections
            document.querySelectorAll('.payment-section').forEach(s => s.classList.add('hidden'));
            
            const activeSection = document.getElementById('section-' + type);
            if (activeSection) {
                activeSection.classList.remove('hidden');
            }

            // 3. Receipt Logic (Safe Check)
            const receiptArea = document.getElementById('receipt-area');
            const receiptInput = document.getElementById('receipt-input');

            if (receiptArea && receiptInput) {
                if (type === 'cash') {
                    receiptArea.classList.add('hidden');
                    receiptInput.required = false;
                } else {
                    receiptArea.classList.remove('hidden');
                    receiptInput.required = true;
                }
            }
        }

        function updateFileName(input) {
            const label = document.getElementById('file-label');
            if (input.files.length > 0) {
                label.innerText = "✓ " + input.files[0].name;
                label.style.color = "#d2a679";
            }
        }

        // Run once when the page finishes loading
        document.addEventListener('DOMContentLoaded', function() {
            const selectedMethod = document.getElementById('selected_method').value;
            if (selectedMethod) {
                switchPayment(selectedMethod);
            }
        });

        
    </script>
</body>
</html>