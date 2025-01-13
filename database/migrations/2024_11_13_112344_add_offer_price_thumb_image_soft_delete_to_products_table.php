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
            // Adding new fields
            $table->decimal('offer_price', 10, 2)->nullable()->after('price');
            $table->string('thumb_image')->nullable()->after('image');

            // Adding soft deletes
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Dropping the new fields
            $table->dropColumn('offer_price');
            $table->dropColumn('thumb_image');

            // Dropping soft deletes
            $table->dropSoftDeletes();
        });
    }
};
