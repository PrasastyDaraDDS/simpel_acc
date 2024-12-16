<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameProductTypeToProductCategoryTypeAndChangeProductId extends Migration
{
    public function up()
    {
        // Rename the table
        Schema::rename('product_types', 'product_category_types');

    }

    public function down()
    {

        Schema::rename('product_category_types', 'product_types');
    }
}
