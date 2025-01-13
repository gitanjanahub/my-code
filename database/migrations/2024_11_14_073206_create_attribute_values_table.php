<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_name_id')->constrained()->cascadeOnDelete();
            $table->string('value'); // E.g., Large, Red, etc.
            $table->timestamps();
            $table->softDeletes(); // Add soft delete column
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_values');
    }
};
