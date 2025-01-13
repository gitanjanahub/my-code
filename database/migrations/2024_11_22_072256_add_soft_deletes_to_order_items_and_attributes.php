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


        // Add 'deleted_at' column to order_item_attributes table
        Schema::table('order_item_attributes', function (Blueprint $table) {
            $table->softDeletes(); // Adds 'deleted_at' column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {


        // Remove 'deleted_at' column from order_item_attributes table
        Schema::table('order_item_attributes', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
