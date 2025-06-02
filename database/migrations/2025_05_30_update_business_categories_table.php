<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('business_categories', function (Blueprint $table) {
            // إضافة الأعمدة الجديدة إذا لم تكن موجودة
            if (!Schema::hasColumn('business_categories', 'features')) {
                $table->json('features')->nullable();
            }
            if (!Schema::hasColumn('business_categories', 'recommended_components')) {
                $table->json('recommended_components')->nullable();
            }
            if (!Schema::hasColumn('business_categories', 'default_settings')) {
                $table->json('default_settings')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('business_categories', function (Blueprint $table) {
            $table->dropColumn(['features', 'recommended_components', 'default_settings']);
        });
    }
};
