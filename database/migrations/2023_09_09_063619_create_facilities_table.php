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
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_primary')->default(false);
            $table->string('logo')->nullable();
            $table->string('header')->nullable();
            $table->json('images')->nullable();
            $table->string('License')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('google_maps_url')->nullable();
            $table->string('default_locale')->default('ar');
            $table->json('supported_locales')->nullable();
            $table->string('slug')->unique();
            $table->boolean('is_verified')->default(false);
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('reviews_count')->default(0);
            $table->boolean('accepts_bookings')->default(false);
            $table->foreignId('business_category_id')->nullable()->constrained('business_categories')->onDelete('set null');
            $table->foreignId('business_sector_id')->nullable()->constrained('business_sectors')->onDelete('set null');
            $table->json('business_details')->nullable();
            $table->json('theme_settings')->nullable();
            $table->json('styles')->nullable();
            $table->unsignedBigInteger('template_id')->nullable();
            $table->json('component_settings')->nullable();
            $table->json('inventory_management')->nullable();
            $table->timestamps();
            $table->softDeletes();
            // $table->string('name'); // تم حذف عمود الاسم من الجدول الأساسي، الاسم في جدول الترجمة فقط
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('facilities');
        Schema::enableForeignKeyConstraints();
    }
};
