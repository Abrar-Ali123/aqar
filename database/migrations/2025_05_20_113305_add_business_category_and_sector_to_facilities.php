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
        Schema::table('facilities', function (Blueprint $table) {
            $table->foreignId('business_category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('business_sector_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropForeign(['business_category_id']);
            $table->dropForeign(['business_sector_id']);
            $table->dropColumn(['business_category_id', 'business_sector_id']);
        });
    }
};
