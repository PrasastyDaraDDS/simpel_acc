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
        Schema::table('participants', function (Blueprint $table) {
            $table->string('link')->nullable(); // Add the link column
            $table->unsignedBigInteger('participant_role_id')->nullable(); // Add the foreign key column

            // Add foreign key constraint
            $table->foreign('participant_role_id')->references('id')->on('participant_roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropForeign(['participant_role_id']);
            $table->dropColumn('participant_role_id'); // Remove the foreign key column
            $table->dropColumn('link'); // Remove the link column
        });
    }
};
