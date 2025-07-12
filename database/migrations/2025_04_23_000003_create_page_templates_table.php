<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('page_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->json('layout')->nullable()->comment('التخطيط الكامل للصفحة');
            $table->json('styles')->nullable()->comment('أنماط CSS');
            $table->json('components')->nullable()->comment('مكونات الصفحة القابلة للتخصيص');
            $table->json('settings')->nullable()->comment('إعدادات القالب');
            $table->string('preview_image')->nullable();
            $table->string('category')->default('general');
            $table->integer('sort_order')->default(0);
            $table->unsignedBigInteger('category_id')->nullable();
            $table->json('meta_data')->nullable();
            $table->json('seo_settings')->nullable();
            $table->json('analytics_settings')->nullable();
            $table->json('custom_fields')->nullable();
            $table->json('validation_rules')->nullable();
            $table->json('dependencies')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('page_templates');
    }
};
