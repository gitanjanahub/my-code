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
        Schema::create('contactuses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Full name of the user
            $table->string('email'); // Email address of the user
            $table->string('phone')->nullable(); // Optional phone number
            $table->string('subject'); // Subject of the message
            $table->text('message'); // Message content
            //$table->string('status')->default('pending'); // Status of the message (e.g., pending, resolved)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contactuses');
    }
};
