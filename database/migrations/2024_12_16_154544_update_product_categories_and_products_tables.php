<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductCategoriesAndProductsTables extends Migration
{
    public function up()
    {
        // Remove the foreign key constraint first
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropForeign(['product_id']); // Drop the foreign key constraint
        });

        // Now remove the product_id column from product_categories table
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropColumn('product_id'); // Remove the product_id column
        });

        // Add product_category_type_id column to products table
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('product_category_type_id')->nullable(); // Add the product_category_type_id column
        });
    }

    public function down()
    {
        // Reverse the changes
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('product_category_type_id'); // Remove the product_category_type_id column
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable(); // Re-add the product_id column
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade'); // Re-add the foreign key constraint
        });
    }
}
