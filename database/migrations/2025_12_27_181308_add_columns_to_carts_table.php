<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            // Add session_id if it's missing (for guest checkout)
            if (!Schema::hasColumn('carts', 'session_id')) {
                $table->string('session_id')->nullable()->after('id')->index();
            }
            
            // Add status (this is what's causing your error)
            if (!Schema::hasColumn('carts', 'status')) {
                $table->string('status')->default('active')->after('session_id');
            }

            // Add total_price
            if (!Schema::hasColumn('carts', 'total_price')) {
                $table->decimal('total_price', 10, 2)->default(0.00)->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn(['session_id', 'status', 'total_price']);
        });
    }
};
