<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{Schema, DB};

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('languages', 'order')) {
            Schema::table('languages', function (Blueprint $table) {
                $table->integer('order')->default(0)->after('is_required');
            });
        }

        // تحديث قيم افتراضية للغات الموجودة
        DB::table('languages')->where('code', 'ar')->update([
            'order' => 1
        ]);
        
        DB::table('languages')->where('code', 'en')->update([
            'order' => 2
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('languages', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
};
