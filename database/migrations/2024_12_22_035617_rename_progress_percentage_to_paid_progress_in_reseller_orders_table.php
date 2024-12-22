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
        Schema::dropIfExists('reseller_orders'); // Drop the table if it exists
    }

    public function down()
    {
        // Optionally, you can define the structure of the table to recreate it if needed
        Schema::create('reseller_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reseller_id'); // Example column
            $table->unsignedBigInteger('product_id'); // Example column
            $table->integer('paid_progress')->nullable(); // Example column
            $table->timestamps();

            // Add any necessary foreign key constraints
            // $table->foreign('reseller_id')->references('id')->on('resellers')->onDelete('cascade');
            // $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }
};
