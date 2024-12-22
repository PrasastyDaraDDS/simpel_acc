<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('stock')->default(0); // Add stock column with default value of 0
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove stock column from products table
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('stock'); // Remove the stock column if rolling back
        });

    }
};
