<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;

class MenuController extends Controller
{
    // Welcome page - Removed Auth-dependent stats
    public function cs_welcome(Request $request)
    {
        return view('customer.cs_welcome');
    }

    public function setOrderMethod($method) {
        session(['order_method' => $method]);
        return redirect()->route('customer.cs_menu');
    }

    public function cs_menu() {
        $categories = Category::with(['products.variants' => function ($q) {
            $q->where('is_active', true)->where('stock', '>', 0);
        }])->where('is_active', true)->get();

        // Login-less: Fetch cart by session_id ONLY
        $cart = Cart::where('status', 'active')
            ->where('session_id', session()->getId())
            ->with('items')
            ->first();

        return view('customer.cs_menu', compact('categories', 'cart'));
    }

    public function cs_cart()
    {
        // Login-less: Pure session-based cart
        $cart = Cart::where('status', 'active')
            ->where('session_id', session()->getId())
            ->with('items.variant.product')
            ->first();

        $cartItems = $cart ? $cart->items : collect();
        return view('customer.cs_cart', compact('cartItems', 'cart'));
    }

    public function cs_cart_store(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id', 
            'quantity'   => 'required|integer|min:1',
        ]);

        $sessionId = session()->getId();

        // 1. Find or create cart based ONLY on session
        $cart = Cart::firstOrCreate(
            ['session_id' => $sessionId, 'status' => 'active'],
            ['user_id' => null, 'total_price' => 0] // No user_id
        );

        $variant = ProductVariant::findOrFail($request->variant_id);

        $cartItem = CartItem::where('cart_id', $cart->id)
                    ->where('product_variant_id', $variant->id)
                    ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity);
        } else {
            CartItem::create([
                'cart_id'            => $cart->id,
                'product_variant_id' => $variant->id,
                'quantity'           => $request->quantity,
                'unit_price'         => $variant->price,
            ]);
        }

        return redirect()->route('customer.cs_menu')->with('success', 'Sweet! Item added to your basket.');
    }

    // Logic for Update and Remove remains largely same as they use Item ID
    public function cs_cart_update(Request $request, $id)
    {
        $cartItem = CartItem::findOrFail($id);
        $action = $request->input('action');

        if ($action === 'increase') {
            $cartItem->increment('quantity');
        } elseif ($action === 'decrease' && $cartItem->quantity > 1) {
            $cartItem->decrement('quantity');
        }

        $cart = $cartItem->cart()->with('items')->first();
        $method = session('order_method', 'delivery'); 
        $deliveryFee = ($method === 'pickup') ? 0.00 : 5.00;
        
        $subtotal = $cart->items->sum(fn($i) => $i->unit_price * $i->quantity);
        $total = $subtotal + $deliveryFee;

        return response()->json([
            'success' => true,
            'new_quantity' => $cartItem->quantity,
            'item_total' => number_format($cartItem->unit_price * $cartItem->quantity, 2),
            'subtotal' => number_format($subtotal, 2),
            'total' => number_format($total, 2),
            'deposit' => number_format($total * 0.30, 2),
            'item_count' => $cart->items->sum('quantity')
        ]);
    }

    public function cs_cart_remove($id)
    {
        CartItem::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Item removed from basket.');
    }

    public function preparePayment(Request $request)
    {
        $cart = Cart::where('status', 'active')
            ->where('session_id', session()->getId())
            ->with('items.variant.product')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('customer.cs_cart')->with('error', 'Your basket is empty.');
        }

        foreach ($cart->items as $item) {
            if ($item->quantity > $item->variant->stock) {
                return redirect()->route('customer.cs_cart')->with('error', 
                    "⚠️ Inventory Update: We only have {$item->variant->stock} units of '{$item->variant->product->name}' left.");
            }
        }

        session(['checkout_payment_type' => $request->payment_type ?? 'full']);
        return redirect()->route('customer.cs_payment');
    }

    public function cs_payment()
    {
        $cart = Cart::where('status', 'active')
            ->where('session_id', session()->getId())
            ->with('items.variant.product')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('customer.cs_menu')->with('error', 'Your basket is empty.');
        }

        $subtotal = $cart->items->sum(fn($item) => $item->unit_price * $item->quantity);
        $method = session('order_method', 'delivery');
        $deliveryFee = ($method === 'pickup') ? 0.00 : 5.00;
        $total = $subtotal + $deliveryFee;
        $paymentType = session('checkout_payment_type', 'full');
        $amountToPay = ($paymentType === 'deposit') ? ($total * 0.30) : $total;

        return view('customer.cs_payment', [
            'total' => $total,
            'amountToPay' => $amountToPay,
            'paymentType' => $paymentType,
            'subtotal' => $subtotal,
            'deliveryFee' => $deliveryFee,
            'cartItems' => $cart->items 
        ]);
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'address'         => session('order_method') === 'pickup' ? 'nullable' : 'required|string',
            'name'            => 'required|string|max:50',
            'phone'           => 'required|string|max:15',
            'payment_method'  => 'required|in:qr,transfer,cash',
            'payment_type'    => 'required|in:deposit,full',
            'amount_paid'     => 'required|numeric',
            'payment_receipt' => 'required_unless:payment_method,cash|image|max:2048', 
            'notes'           => 'nullable|string|max:1000',
        ]);

        // Login-less: Fetch cart by session_id
        $cart = Cart::where('session_id', session()->getId())
                    ->where('status', 'active')
                    ->with('items.variant.product')
                    ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('customer.cs_menu')->with('error', 'No active order found.');
        }

        $subtotal = $cart->items->sum(fn($item) => (float)$item->unit_price * $item->quantity);
        $deliveryFee = (session('order_method') === 'pickup') ? 0.00 : 5.00;
        $finalTotal = $subtotal + $deliveryFee;

        try {
            $confirmedOrderNumber = DB::transaction(function () use ($request, $cart, $finalTotal) {
                
                $receiptPath = null;
                if ($request->hasFile('payment_receipt')) {
                    $receiptPath = $request->file('payment_receipt')->store('receipts', 'public');
                }

                $newOrder = Order::create([
                    'order_number'    => 'SF-' . strtoupper(Str::random(8)),
                    'user_id'         => null, // Set to NULL for guest
                    'customer_name'   => $request->name,
                    'phone'           => $request->phone,
                    'address'         => session('order_method') === 'pickup' ? 'N/A' : $request->address,
                    'notes'           => $request->notes ?? 'N/A',
                    'total_price'     => $finalTotal,
                    'amount_paid'     => $request->amount_paid,
                    'status'          => 'pending',
                    'payment_type'    => $request->payment_type,
                    'payment_method'  => $request->payment_method,
                    'payment_receipt' => $receiptPath,
                    'delivery_method' => session('order_method', 'pickup'),
                ]);

                foreach ($cart->items as $item) {
                    OrderItem::create([
                        'order_id'           => $newOrder->id,
                        'product_variant_id' => $item->product_variant_id,
                        'product_name'       => $item->variant->product->name,
                        'variant_name'       => $item->variant->name,
                        'unit_price'         => $item->unit_price,
                        'quantity'           => $item->quantity,
                    ]);

                    $item->variant->decrement('stock', $item->quantity);
                    $item->variant->refresh();

                    if ($item->variant->stock <= 0) {
                        $item->variant->update(['stock' => 0, 'is_active' => false]);
                    }
                }

                $cart->items()->delete();
                $cart->delete();
                
                return $newOrder->order_number;
            });

            session()->forget(['order_method', 'checkout_payment_type']);
            return redirect()->route('customer.cs_order_complete', ['order_number' => $confirmedOrderNumber])
                             ->with('success', 'Order processed successfully!');

        } catch (\Exception $e) {
            return redirect()->route('customer.cs_payment')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function cs_orders(Request $request)
    {
        // Login-less History: Filter by phone number provided in the GET request
        $phone = $request->query('phone');
        
        $orders = $phone 
            ? Order::where('phone', $phone)->with('items')->latest()->get() 
            : collect(); // Return empty if no phone searched

        return view('customer.cs_order_history', compact('orders'));
    }

    public function cs_order_complete($order_number)
    {
        // Login-less Detail: Just find by order_number (Publicly accessible but limited info)
        $order = Order::where('order_number', $order_number)
                    ->with('items')
                    ->firstOrFail();

        return view('customer.cs_order_complete', [
            'order'      => $order,
            'deliveryFee' => ($order->delivery_method === 'pickup') ? 0.00 : 5.00,
            'totalPrice' => (float)$order->total_price,
            'amountPaid' => (float)$order->amount_paid,
            'balanceDue' => (float)$order->total_price - (float)$order->amount_paid,
        ]);
    }
}