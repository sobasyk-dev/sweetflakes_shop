<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sweetflakes | Business Intelligence</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=Inter:wght@400;500;600;800&display=swap" rel="stylesheet">
    <x-tailwind />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .glass-card {
            background: rgba(36, 26, 18, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>

<body class="min-h-screen text-cream font-sans bg-cocoa-950 selection:bg-caramel-500/30">
    <x-loader text="Fetching latest insights..."/>
    <x-ad_header />
    <x-alerts />
    
    <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[60%] md:w-[40%] h-[40%] rounded-full bg-caramel-500/10 blur-[80px] md:blur-[120px]"></div>
        <div class="absolute bottom-[10%] -right-[5%] w-[50%] md:w-[30%] h-[30%] rounded-full bg-caramel-500/5 blur-[80px] md:blur-[100px]"></div>
    </div>

    <main class="mx-auto max-w-6xl px-4 md:px-6 py-6">
        
        <x-ad_pill_link title1="Business" title2="Intelligence" />

        <section class="grid grid-cols-3 gap-2 md:gap-4 mb-6 mt-4">
            <div class="glass-card rounded-[1.2rem] md:rounded-[1.5rem] p-3 md:p-5 text-center border-t border-white/10 shadow-xl transition-all duration-500 hover:border-caramel-500/30">
                <p class="text-[7px] md:text-[9px] uppercase tracking-[0.1em] md:tracking-[0.2em] text-caramel-500 font-black">Sales</p>
                <p class="mt-1 md:mt-2 text-md md:text-xl font-black text-cream tracking-tighter">RM {{ number_format($stats['total_revenue'], 0) }}</p>
            </div>

            <div class="glass-card rounded-[1.2rem] md:rounded-[1.5rem] p-3 md:p-5 text-center border-t border-white/10 shadow-xl transition-all duration-500 hover:border-caramel-500/30">
                <p class="text-[7px] md:text-[9px] uppercase tracking-[0.1em] md:tracking-[0.2em] text-caramel-500 font-black">Orders</p>
                <p class="mt-1 md:mt-2 text-lg md:text-2xl font-black text-cream tracking-tighter">{{ $stats['total_orders'] }}</p>
            </div>

            <div class="glass-card rounded-[1.2rem] md:rounded-[1.5rem] p-3 md:p-5 text-center border-t border-white/10 shadow-xl transition-all duration-500 hover:border-caramel-500/30">
                <p class="text-[7px] md:text-[9px] uppercase tracking-[0.1em] md:tracking-[0.2em] text-caramel-500 font-black">Sold</p>
                <div class="flex items-center justify-center gap-1 mt-1 md:mt-2">
                    <p class="text-lg md:text-2xl font-black text-cream tracking-tighter">{{ number_format($stats['total_units']) }}</p>
                </div>
            </div>
        </section>

        <section class="mb-6">

            <!-- Sales Analysis  -->
            <div class="glass-card rounded-[2rem] p-6 mb-6 border-t border-white/10">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
                    <div>
                        <h2 class="font-serif text-xl italic text-cream">Sales Analysis</h2>
                        <p class="text-[9px] uppercase tracking-widest text-white/30 mt-1">Online vs. Walk-in Revenue</p>
                    </div>
                    
                    @if($dailySales->count() > 0 || $monthlySales->count() > 0 || $yearlySales->count() > 0)
                        <div class="flex items-center justify-center gap-2 md:gap-3">
                            <button onclick="updateSalesChart('daily')" id="btn-daily" 
                                class="glass-card text-[8px] md:text-[9px] uppercase tracking-widest px-4 py-2 rounded-full bg-caramel-500 text-cocoa-950 font-black transition-all duration-300 shadow-lg shadow-caramel-500/10">
                                Daily
                            </button>
                            <button onclick="updateSalesChart('monthly')" id="btn-monthly" 
                                class="glass-card text-[8px] md:text-[9px] uppercase tracking-widest px-4 py-2 rounded-full text-white/40 font-bold hover:text-white/80 transition-all duration-300">
                                Monthly
                            </button>
                            <button onclick="updateSalesChart('yearly')" id="btn-yearly" 
                                class="glass-card text-[8px] md:text-[9px] uppercase tracking-widest px-4 py-2 rounded-full text-white/40 font-bold hover:text-white/80 transition-all duration-300">
                                Yearly
                            </button>
                        </div>
                    @endif
                </div>

                <div class="h-[300px] flex items-center justify-center">
                    @if($dailySales->count() > 0 || $monthlySales->count() > 0 || $yearlySales->count() > 0)
                        <canvas id="salesAnalysisChart"></canvas>
                    @else
                        <div class="flex flex-col items-center justify-center opacity-40">
                            <svg class="w-8 h-8 mb-3 text-caramel-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <p class="text-[10px] uppercase tracking-[0.2em] font-black">No channel data available</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Best Sellers Section -->
            <div class="glass-card rounded-[2rem] p-6 border-t border-white/10">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="font-serif text-xl italic text-cream">Best Sellers</h2>
                    <span class="text-[9px] uppercase tracking-widest text-caramel-500 font-bold italic">Popularity</span>
                </div>
                
                <div class="h-[300px] flex items-center justify-center">
                    @if($topProducts->count() > 0)
                        <canvas id="bestSellerChart"></canvas>
                    @else
                        <div class="flex flex-col items-center justify-center opacity-40">
                            <svg class="w-8 h-8 mb-3 text-caramel-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <p class="text-[10px] uppercase tracking-[0.2em] font-black">No products sold yet</p>
                        </div>
                    @endif
                </div>
            </div>
            
        </section>

        <section class="mb-10 mt-12">
            <div class="flex flex-col md:flex-row items-center justify-between gap-2 mb-4 px-2">
                <div class="flex items-center gap-2 w-full md:w-auto">
                    <h2 class="font-serif text-2xl italic text-cream whitespace-nowrap">Customer Ranking</h2>
                </div>
                
                <div class="flex items-center justify-between w-full md:w-auto gap-2">
                    <span class="text-[9px] uppercase tracking-[0.2em] text-white/30 font-bold md:hidden">Top Performers</span>
                    <!-- <button class="text-[10px] uppercase tracking-widest text-caramel-500 font-black border border-caramel-500/20 px-6 py-2.5 rounded-full bg-white/[0.02] hover:bg-caramel-500 hover:text-cocoa-950 transition-all duration-300">
                        Export Report
                    </button> -->
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
                @if($customers->count() > 0 && $customers->first()->orders_count > 0)
                    @foreach($customers as $customer)
                        <div class="glass-card rounded-[2rem] p-5 md:p-6 border-t border-white/10 shadow-xl transition-all duration-500 hover:border-caramel-500/40 group">
                            <div class="flex justify-between items-start mb-6">
                                <div class="flex items-center gap-4">
                                    <div class="h-12 w-12 rounded-full bg-caramel-500/10 flex items-center justify-center text-caramel-400 text-lg font-bold border border-caramel-500/20 shadow-inner group-hover:scale-110 transition-transform duration-500">
                                        {{ substr($customer->name, 0, 1) }}
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <span class="text-cream font-serif italic text-lg truncate leading-tight">{{ $customer->name }}</span>
                                        <span class="text-[10px] text-white/30 uppercase tracking-widest truncate">{{ $customer->email }}</span>
                                    </div>
                                </div>
                                <span class="text-[10px] font-black text-caramel-500/40 italic">#{{ $loop->iteration }}</span>
                            </div>

                            <div class="grid grid-cols-2 gap-4 py-4 border-y border-white/5">
                                <div class="flex flex-col">
                                    <p class="text-[8px] uppercase tracking-widest text-caramel-500/60 font-black mb-1">Frequency</p>
                                    <p class="text-sm font-bold text-cream">{{ $customer->orders_count }} <span class="text-[10px] text-white/20 font-normal">Orders</span></p>
                                </div>
                                <div class="flex flex-col border-l border-white/5 pl-4">
                                    <p class="text-[8px] uppercase tracking-widest text-caramel-500/60 font-black mb-1">Items</p>
                                    <p class="text-sm font-bold text-cream">{{ number_format($customer->total_units_bought ?? 0) }} <span class="text-[10px] text-white/20 font-normal">Items</span></p>
                                </div>
                            </div>

                            <div class="mt-5 flex items-center justify-between bg-white/[0.03] p-3 rounded-xl border border-white/5">
                                <span class="text-[9px] uppercase tracking-[0.2em] text-white/40 font-bold">Total Contribution</span>
                                <span class="text-base font-black text-caramel-400 tracking-tighter">
                                    RM {{ number_format($customer->total_spent ?? 0, 2) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-span-full glass-card rounded-[2.5rem] p-20 text-center border-t border-white/10 opacity-50">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-caramel-500/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857" />
                                </svg>
                            </div>
                            <p class="text-xs uppercase tracking-[0.3em] font-black text-cream">No Customer Ranking Data</p>
                            <p class="text-[9px] text-white/20 mt-2 uppercase">Complete orders to populate this list</p>
                        </div>
                    </div>
                @endif
            </div>
        </section>

    </main>
    <x-footer />

    <script>
        // Global Chart Configuration
        Chart.defaults.color = 'rgba(255, 255, 255, 0.4)';
        Chart.defaults.font.family = "'Inter', sans-serif";

        // --- 1. Best Seller Doughnut Chart (Completed Items Only) ---
        const pieCtx = document.getElementById('bestSellerChart').getContext('2d');

        // 1. Calculate the total from your JSON data
        const totalUnits = @json($topProducts->pluck('total_sold')).reduce((a, b) => a + b, 0);

        new Chart(pieCtx, {
            type: 'doughnut',
            // Register the datalabels plugin
            plugins: [ChartDataLabels],
            data: {
                labels: @json($topProducts->pluck('product_name')),
                datasets: [{
                    label: 'Units Sold',
                    data: @json($topProducts->pluck('total_sold')),
                    backgroundColor: ['#d2a679', '#f5f5f5', '#5c4033', '#8b5e3c', '#3d2b1f'],
                    borderWidth: 0,
                    hoverOffset: 15
                }]
            },
            options: { 
                cutout: '70%', 
                plugins: { 
                    legend: { 
                        position: 'bottom', 
                        labels: { 
                            padding: 20, 
                            usePointStyle: true, 
                            font: { size: 10, family: 'sans-serif' },
                            color: '#f5f5f5' // Matches your cream text
                        } 
                    },
                    // Configuration for the labels on top of segments
                    datalabels: {
                        color: (context) => {
                            // Make text dark on light backgrounds and light on dark backgrounds
                            const bgColor = context.dataset.backgroundColor[context.dataIndex];
                            return bgColor === '#f5f5f5' ? '#0d0a09' : '#fff';
                        },
                        anchor: 'end',    // Positioned towards the outside of the arc
                        align: 'start',   // Pulled slightly back into the segment
                        offset: 10,
                        borderRadius: 4,
                        backgroundColor: (context) => context.dataset.backgroundColor[context.dataIndex],
                        font: {
                            weight: 'bold',
                            size: 11
                        },
                        formatter: (value) => {
                            return value + ' units'; // Adds 'u' for units, e.g., "45u"
                        }
                    },
                    tooltip: {
                        enabled: true, // Keep tooltips as a fallback
                        callbacks: {
                            label: function(context) {
                                return ` ${context.label}: ${context.raw} units sold`;
                            }
                        }
                    }
                },
            }
        });

        // --- 2. Sales Analysis Bar Chart (Completed Revenue) ---
        const lineCtx = document.getElementById('salesAnalysisChart').getContext('2d');
        
        // The data here is already filtered by the controller to show only status='completed'
        const salesData = {
            daily: { 
                labels: @json($dailySales->pluck('labels')), 
                online: @json($dailySales->pluck('online_data')), 
                walkin: @json($dailySales->pluck('walkin_data')) 
            },
            monthly: { 
                labels: @json($monthlySales->pluck('labels')), 
                online: @json($monthlySales->pluck('online_data')), 
                walkin: @json($monthlySales->pluck('walkin_data')) 
            },
            yearly: { 
                labels: @json($yearlySales->pluck('labels')), 
                online: @json($yearlySales->pluck('online_data')), 
                walkin: @json($yearlySales->pluck('walkin_data')) 
            }
        };

        let salesChart = new Chart(lineCtx, {
            type: 'bar',
            // 1. Register the plugin
            plugins: [ChartDataLabels], 
            data: {
                labels: salesData.daily.labels,
                datasets: [
                    {
                        label: 'Online',
                        data: salesData.daily.online,
                        backgroundColor: '#d2a679', 
                        borderRadius: 4,
                        barPercentage: 0.8,
                        categoryPercentage: 0.6
                    },
                    {
                        label: 'Walk-in',
                        data: salesData.daily.walkin,
                        backgroundColor: '#af4949ff',
                        borderRadius: 4,
                        barPercentage: 0.8,
                        categoryPercentage: 0.6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                // 2. Increase layout padding so labels don't get cut off at the top
                layout: {
                    padding: { top: 20 }
                },
                scales: {
                    y: { 
                        stacked: false,
                        beginAtZero: true, 
                        grid: { color: 'rgba(255, 255, 255, 0.05)', drawBorder: false },
                        ticks: { 
                            font: { size: 10 },
                            callback: v => 'RM ' + v 
                        }
                    },
                    x: { 
                        stacked: false,
                        grid: { display: false },
                        ticks: { font: { size: 9 } }
                    }
                },
                plugins: {
                    // 3. Configure the Data Labels
                    datalabels: {
                        anchor: 'end', // Position at the end of the bar
                        align: 'top',  // Position on top of the bar
                        color: '#f5f5f5', // Cream color to match your theme
                        font: {
                            size: 9,
                            weight: 'bold'
                        },
                        formatter: function(value) {
                            // Only show label if value is greater than 0
                            return value > 0 ? 'RM' + Math.round(value) : '';
                        },
                        // Prevents labels from overlapping if bars are too thin
                        clip: false 
                    },
                    legend: { 
                        position: 'top', 
                        align: 'end',
                        labels: { boxWidth: 8, font: { size: 10 }, padding: 15 } 
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: '#241a12',
                        padding: 12,
                        cornerRadius: 10,
                        callbacks: {
                            label: function(context) {
                                return ` ${context.dataset.label}: RM ${parseFloat(context.raw).toFixed(2)}`;
                            }
                        }
                    }
                }
            }
        });

        function updateSalesChart(type) {
            salesChart.data.labels = salesData[type].labels;
            salesChart.data.datasets[0].data = salesData[type].online;
            salesChart.data.datasets[1].data = salesData[type].walkin;
            salesChart.update();

            // Button highlight logic
            ['daily', 'monthly', 'yearly'].forEach(t => {
                const btn = document.getElementById(`btn-${t}`);
                if(t === type) {
                    btn.classList.add('bg-caramel-500', 'text-cocoa-950', 'font-black');
                    btn.classList.remove('text-white/40');
                } else {
                    btn.classList.remove('bg-caramel-500', 'text-cocoa-950', 'font-black');
                    btn.classList.add('text-white/40');
                }
            });
        }
    </script>
</body>
</html>