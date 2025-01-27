<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add the shipping_cost column
            $table->integer('shipping_cost')->default(0); // Default value can be set as needed
            $table->dropColumn('quantity');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Remove the shipping_cost column if rolling back
            $table->dropColumn('shipping_cost');
            $table->integer('quantity')->default(0);
        });
    }
};
