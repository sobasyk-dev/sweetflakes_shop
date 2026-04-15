<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;


class AdminController extends Controller
{
    // --- ADMIN ANALYSIS (The part you wanted to add) ---
    public function ad_dashboard() 
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Access Denied');
        }

        // 1. Global Stats (All Channels - Completed Only)
        $stats = [
            'total_revenue' => Order::where('status', 'completed')->sum('total_price') ?? 0,
            'total_orders'  => Order::where('status', 'completed')->count() ?? 0,
            'total_units'   => OrderItem::whereHas('order', fn($q) => $q->where('status', 'completed'))
                                ->sum('quantity') ?? 0,
        ];

        // 2. Customer Ranking
        $customers = User::where('role', 'customer')
        // ONLY include users who have at least one completed order
        ->whereHas('orders', function($q) {
            $q->where('status', 'completed');
        })
        // Count of completed orders
        ->withCount(['orders' => fn($q) => $q->where('status', 'completed')])
        
        // Total money spent
        ->withSum(['orders as total_spent' => fn($q) => $q->where('status', 'completed')], 'total_price')
        
        // Total units bought (requires orderItems relationship in User model)
        ->withSum(['orderItems as total_units_bought' => function($q) {
            $q->whereHas('order', function($query) {
                $query->where('status', 'completed');
            });
        }], 'quantity')
        
        // Order by highest spend first
        ->orderByDesc('total_spent')
        ->get();

        // 3. PIE CHART: Best Sellers
        $topProducts = OrderItem::whereHas('order', fn($q) => $q->where('status', 'completed'))
            ->select('product_name', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // 4. CHANNEL ANALYSIS LOGIC
        // Using delivery_method: 'walk-in' vs everything else ('online')
        $channelSql = "SUM(CASE WHEN delivery_method = 'walk-in' THEN total_price ELSE 0 END) as walkin_data, 
                    SUM(CASE WHEN delivery_method != 'walk-in' THEN total_price ELSE 0 END) as online_data";

        // Daily Sales
        $dailySales = Order::where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(30))
            ->select(DB::raw('DATE(created_at) as labels'), DB::raw($channelSql))
            ->groupBy('labels')
            ->orderBy('labels')
            ->get();

        // Monthly Sales
        $monthlySales = Order::where('status', 'completed')
            ->whereYear('created_at', date('Y'))
            ->select(DB::raw('DATE_FORMAT(created_at, "%b") as labels'), DB::raw($channelSql))
            ->groupBy(DB::raw('MONTH(created_at)'), 'labels')
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();

        // Yearly Sales
        $yearlySales = Order::where('status', 'completed')
            ->select(DB::raw('YEAR(created_at) as labels'), DB::raw($channelSql))
            ->groupBy('labels')
            ->orderBy('labels')
            ->get();

        return view('admin.ad_dashboard', compact('customers', 'stats', 'topProducts', 'dailySales', 'monthlySales', 'yearlySales'));
    }

    public function ad_pos() {
        $products = Product::with(['variants' => function($query) {
            $query->where('is_active', true);
        }])->get();
    
        // Get unique category names for the tabs
        $categories = $products->pluck('category')->unique()->map(function($cat) {
            return is_array($cat) || is_object($cat) ? ($cat['name'] ?? $cat->name ?? 'Uncategorized') : $cat;
        });

        return view('admin.ad_pos', compact('products', 'categories'));
    }

    public function pos_checkout(Request $request) {
        try {
            return DB::transaction(function () use ($request) {
                // Generate Order Number
                $orderNumber = 'ORD-' . strtoupper(uniqid());

                $order = Order::create([
                    'order_number'    => $orderNumber,
                    'customer_name'   => $request->customer_name ?? 'Walk-in Customer',
                    'phone'           => $request->phone ?? 'N/A',
                    'address'         => $request->address ?? 'N/A',
                    'total_price'     => $request->total,
                    'amount_paid'     => $request->total,
                    'status'          => 'pending',
                    'payment_type'    => 'full',
                    'payment_method'  => $request->payment_method ?? 'N/A',
                    'delivery_method' => 'walk-in',
                    'notes'           => $request->notes ?? 'N/A',
                ]);

                foreach ($request->items as $item) {
                    OrderItem::create([
                        'order_id'           => $order->id,
                        'product_variant_id' => $item['id'],
                        'product_name'       => $item['p_name'],
                        'variant_name'       => $item['v_name'],
                        'quantity'           => $item['qty'],
                        'unit_price'         => $item['price'],
                    ]);

                    $variant = ProductVariant::find($item['id']);
                    $variant->decrement('stock', $item['qty']);
                }

                return response()->json([
                    'success' => true, 
                    'order_number' => $orderNumber
                ]);
            });
        } catch (\Exception $e) {
            // This sends the specific SQL or Logic error back to your alert box
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function ad_orders() 
    {
        $orders = Order::with(['items']) // Ensure 'items' relationship is loaded
            ->latest()
            ->get()
            ->map(function($order) {
                // Add a formatted date for the UI
                $order->formatted_date = $order->created_at->format('d M Y, h:i A');
                
                // Explicitly ensure total_price is a float for JS calculations
                $order->total_price = (float)$order->total_price;

                // Optional: You can pre-process item names here if they aren't in the items table
                return $order;
            });

        return view('admin.ad_orders', compact('orders'));
    }

    public function update_order_status(Request $request, $id)
    {
        // 1. Validate the request to ensure status is one of the allowed types
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        try {
            // 2. Find the order or fail with a 404
            $order = Order::findOrFail($id);

            // 3. Update the status
            $order->update([
                'status' => $request->status
            ]);

            // 4. Redirect back with a success message
            return redirect()->back()->with('success', "Order #{$order->order_number} status updated to " . ucfirst($request->status));

        } catch (\Exception $e) {
            // 5. If something goes wrong (database error, etc.), redirect back with error
            Log::error("Order Update Error: " . $e->getMessage());
            
            return redirect()->back()->withErrors("Could not update Order #{$id}. Please try again.");
        }
    }

}
