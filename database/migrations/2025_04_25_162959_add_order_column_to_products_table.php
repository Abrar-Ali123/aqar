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
        // تم تعطيل إضافة عمود order لأنه موجود بالفعل في جدول products
        /*Schema::table('products', function (Blueprint $table) {
            $table->integer('order')->default(0)->after('type');
        });*/
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /*Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('order');
        });*/
    }
};
