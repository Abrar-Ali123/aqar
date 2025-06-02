<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // جدول وحدات القياس
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            // $table->string('name'); // تم حذف عمود الاسم من جدول units الأساسي، الاسم في جدول unit_translations فقط
            $table->string('code')->unique(); // مثل: kg, l, pc
            $table->string('type'); // mass, volume, unit, length, etc.
            $table->boolean('is_base')->default(false); // هل هي وحدة أساسية؟
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // جدول ترجمات الوحدات
        Schema::create('unit_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');
            $table->string('locale');
            $table->string('name');
            $table->string('symbol')->nullable();
            $table->unique(['unit_id', 'locale']);
        });

        // جدول تحويلات الوحدات
        Schema::create('unit_conversions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_unit_id')->constrained('units')->onDelete('cascade');
            $table->foreignId('to_unit_id')->constrained('units')->onDelete('cascade');
            $table->decimal('conversion_factor', 15, 5);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['from_unit_id', 'to_unit_id']);
        });

        // إضافة علاقة الوحدة لقيم المخزون
        Schema::table('attribute_inventory_values', function (Blueprint $table) {
            $table->foreignId('unit_id')->nullable()->after('quantity')->constrained()->onDelete('restrict');
            $table->decimal('quantity', 15, 5)->change(); // تغيير نوع الكمية لدعم الكسور
        });
    }

    public function down()
    {
        Schema::table('attribute_inventory_values', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropColumn('unit_id');
            $table->integer('quantity')->change();
        });

        Schema::dropIfExists('unit_conversions');
        Schema::dropIfExists('unit_translations');
        Schema::dropIfExists('units');
    }
};
