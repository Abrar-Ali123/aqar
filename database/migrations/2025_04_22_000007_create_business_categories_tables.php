<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // الفئات الرئيسية
        Schema::create('business_sectors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_en');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->text('description_en')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // الفئات الفرعية الأولى
        Schema::create('business_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sector_id')->constrained('business_sectors')->onDelete('cascade');
            $table->string('name');
            $table->string('name_en');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->text('description_en')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // الفئات الفرعية الثانية
        Schema::create('business_subcategories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('business_categories')->onDelete('cascade');
            $table->string('name');
            $table->string('name_en');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->text('description_en')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // الوحدات المتاحة لكل فئة
        Schema::create('category_modules', function (Blueprint $table) {
            $table->id();
            $table->string('module_name');
            $table->string('module_type');
            $table->morphs('categorizable'); // يمكن ربطه بأي مستوى من التصنيف
            $table->json('default_settings')->nullable();
            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ربط المؤسسات بالتصنيفات
        // تم تعطيل المفتاح الأجنبي إلى جدول businesses مؤقتاً لتجنب الخطأ
        Schema::create('business_categorizations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id'); // حذف foreign key مؤقتاً
            $table->unsignedBigInteger('sector_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('subcategory_id')->nullable();
            $table->boolean('is_custom')->default(false);
            $table->json('custom_settings')->nullable();
            $table->timestamps();
            // $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
            $table->foreign('sector_id')->references('id')->on('business_sectors');
            $table->foreign('category_id')->references('id')->on('business_categories');
            $table->foreign('subcategory_id')->references('id')->on('business_subcategories');
        });
    }

    public function down()
    {
        Schema::dropIfExists('business_categorizations');
        Schema::dropIfExists('category_modules');
        Schema::dropIfExists('business_subcategories');
        Schema::dropIfExists('business_categories');
        Schema::dropIfExists('business_sectors');
    }
};
