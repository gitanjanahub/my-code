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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('image_path'); // Path to the image
            $table->string('link')->nullable(); // Link for "Shop Now"
            $table->string('section'); // Section identifier, e.g., 'carousel', 'offer'
            $table->enum('size', ['small', 'medium', 'large']); // Banner size
            $table->integer('order')->default(0); // Order for sorting banners
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
