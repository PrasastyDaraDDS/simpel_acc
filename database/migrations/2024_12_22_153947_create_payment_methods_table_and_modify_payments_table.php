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
        // Create the PaymentMethods table
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->string('name'); // Name of the payment method
            $table->timestamps(); // Created at and updated at timestamps
        });

        // Modify the Payments table
        Schema::table('payments', function (Blueprint $table) {
            // Remove the amount_shipping and amount_overhead columns
            $table->dropColumn(['amount_shipping', 'amount_overhead']);

            // Add payment_method_id foreign key
            $table->unsignedBigInteger('payment_method_id')->nullable(); // Assuming it can be nullable

            // Add foreign key constraint
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('cascade');
        });
    }

    public function down()
    {
        // Reverse the changes made in the up() method
        Schema::table('payments', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['payment_method_id']);

            // Re-add the removed columns
            $table->decimal('amount_shipping', 10, 2)->nullable(); // Adjust type and precision as needed
            $table->decimal('amount_overhead', 10, 2)->nullable(); // Adjust type and precision as needed

            // Drop the payment_method_id column
            $table->dropColumn('payment_method_id');
        });

        // Drop the PaymentMethods table
        Schema::dropIfExists('payment_methods');
    }
};
