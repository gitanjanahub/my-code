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
        Schema::create('aboutuses', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Title for the about us section
            $table->text('description'); // Main content for about us
            $table->string('mission')->nullable(); // Company mission statement
            $table->string('vision')->nullable(); // Company vision statement
            $table->string('image')->nullable(); // Image related to About Us (file path)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aboutuses');
    }
};
