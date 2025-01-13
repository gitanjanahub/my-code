<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attribute_names', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // E.g., Size, Color, etc.
            $table->timestamps();
            $table->softDeletes(); // Add soft delete column
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_names');
    }
};
