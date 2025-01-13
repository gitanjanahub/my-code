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
        Schema::table('banners', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('order'); // Add 'is_active' column
            $table->softDeletes()->after('is_active'); // Add 'deleted_at' column for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn('is_active'); // Remove 'is_active' column
            $table->dropSoftDeletes(); // Remove 'deleted_at' column
        });
    }
};
