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
        Schema::table('orders', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['product_id']); // Adjust the foreign key name if necessary

            // Now remove the product_id column
            $table->dropColumn('product_id');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Re-add the product_id column if rolling back
            $table->unsignedBigInteger('product_id')->nullable(); // Adjust nullable as needed
        });
    }
};
