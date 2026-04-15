<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // Unique order number (e.g., ORD-20251228-001)
            $table->string('order_number')->unique(); 
            
            // Financial data
            $table->decimal('total_price', 10, 2)->default(0.00);
            // New: Amount actually paid during checkout (Full or 30% Deposit)
            $table->decimal('amount_paid', 10, 2)->default(0.00); 
            
            // Order state
            $table->string('status')->default('pending');
            
            // Payment Details
            $table->string('payment_type'); // 'full' or 'deposit'
            $table->string('payment_method'); // 'qr', 'transfer', or 'cash'
            $table->string('payment_receipt')->nullable(); // Stores the file path to image
            
            // Customer Details
            $table->string('customer_name');
            $table->string('phone');
            
            // Logistics
            $table->string('delivery_method'); // 'pickup' or 'delivery'
            $table->text('notes')->nullable();
            
            // Link to the user who placed the order
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};