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
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // for authenticated users
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // for products
            $table->string('guest_id')->nullable(); // to store guest's unique identifier
            $table->timestamps();

            // Ensure a user/guest can only have one instance of a product in their wishlist
            $table->unique(['user_id', 'product_id']);
            $table->unique(['guest_id', 'product_id']); // ensure guest can only add product once
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
