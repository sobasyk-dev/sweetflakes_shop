<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            
            // Link to the main order
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            
            // Link to the variant (nullable in case the product is deleted later)
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->onDelete('set null');
            
            // Snapshot data (Important for historical records)
            $table->string('product_name');
            $table->string('variant_name');
            $table->decimal('unit_price', 10, 2);
            $table->integer('quantity');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};