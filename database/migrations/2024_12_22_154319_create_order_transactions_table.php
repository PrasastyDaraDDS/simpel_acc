<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_transactions', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->unsignedBigInteger('order_id'); // Foreign key to orders table
            $table->unsignedBigInteger('product_id'); // Foreign key to products table
            $table->integer('quantity'); // Quantity of the product

            $table->timestamps(); // Created at and updated at timestamps

            // Foreign key constraints
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_transactions'); // Drop the table if rolling back
    }
};
