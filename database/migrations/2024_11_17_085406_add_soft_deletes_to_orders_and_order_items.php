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
        // Add soft deletes to the orders table
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'deleted_at')) {
                $table->softDeletes(); // Adds a deleted_at column
            }
        });

        // Add soft deletes to the order_items table
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'deleted_at')) {
                $table->softDeletes(); // Adds a deleted_at column
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop soft deletes from the orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        // Drop soft deletes from the order_items table
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
