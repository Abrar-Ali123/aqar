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
            // The columns to be added here (user_id, etc.) are already in the main create_facilities_table migration.
            // This migration was likely not updated after the main one was.
            // We only attempt to drop the old columns if they exist, to support older database states.
            if (Schema::hasColumn('facilities', 'business_category')) {
                $table->dropColumn('business_category');
            }
            if (Schema::hasColumn('facilities', 'business_sector')) {
                $table->dropColumn('business_sector');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            // This down method should only reverse the actions of the up method.
            // It should only add back the old columns.
            if (!Schema::hasColumn('facilities', 'business_category')) {
                $table->string('business_category')->nullable()->after('accepts_bookings');
            }
            if (!Schema::hasColumn('facilities', 'business_sector')) {
                $table->string('business_sector')->nullable()->after('business_category');
            }
        });
    }
};
