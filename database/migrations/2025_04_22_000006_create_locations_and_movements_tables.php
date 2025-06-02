<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // جدول المواقع/المستودعات
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('type'); // warehouse, store, branch, etc.
            $table->string('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // جدول الترجمات للمواقع
        Schema::create('location_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->string('locale');
            $table->string('name');
            $table->string('address')->nullable();
            $table->unique(['location_id', 'locale']);
        });

        // جدول حركات المخزون
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('attribute_id')->constrained()->onDelete('cascade');
            $table->string('attribute_value');
            $table->string('movement_type'); // in, out, transfer, adjustment
            $table->integer('quantity');
            $table->integer('previous_quantity');
            $table->integer('new_quantity');
            $table->foreignId('from_location_id')->nullable()->constrained('locations')->onDelete('set null');
            $table->foreignId('to_location_id')->nullable()->constrained('locations')->onDelete('set null');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('reference_type')->nullable(); // order, transfer, adjustment
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // إضافة علاقة الموقع لقيم المخزون
        Schema::table('attribute_inventory_values', function (Blueprint $table) {
            $table->foreignId('location_id')->after('attribute_value')->constrained()->onDelete('cascade');
            // تحديث المفتاح الفريد ليشمل الموقع
            $table->unique(['product_id', 'attribute_id', 'attribute_value', 'location_id'], 'unique_inventory_value_location');
        });
    }

    public function down()
    {
        Schema::table('attribute_inventory_values', function (Blueprint $table) {
            $table->dropForeign(['location_id']);
            $table->dropColumn('location_id');
            $table->unique(['product_id', 'attribute_id', 'attribute_value'], 'unique_inventory_value');
        });

        Schema::dropIfExists('inventory_movements');
        Schema::dropIfExists('location_translations');
        Schema::dropIfExists('locations');
    }
};
