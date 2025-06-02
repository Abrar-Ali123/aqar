<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_primary')->default(false);
            $table->string('logo')->nullable();
            $table->string('header')->nullable();
            $table->string('License')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('google_maps_url')->nullable();
            $table->string('default_locale')->default('ar');
            $table->json('supported_locales')->nullable();
            $table->timestamps();
            // $table->string('name'); // تم حذف عمود الاسم من الجدول الأساسي، الاسم في جدول الترجمة فقط
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
