<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attribute_inventory_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('attribute_id')->constrained()->onDelete('cascade');
            $table->string('attribute_value'); // القيمة المحددة للسمة (مثل: XL, أحمر، الفرع الرئيسي)
            $table->integer('quantity')->default(0);
            $table->integer('low_stock_threshold')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('batch_number')->nullable();
            $table->json('metadata')->nullable(); // بيانات إضافية حسب نوع السمة
            $table->timestamps();

            // يجب أن تكون القيم فريدة لكل منتج وسمة وقيمة
            $table->unique(['product_id', 'attribute_id', 'attribute_value'], 'unique_inventory_value');
        });

        // إضافة حقل has_inventory و inventory_settings للسمات
        Schema::table('attributes', function (Blueprint $table) {
            $table->boolean('has_inventory')->default(false);
            $table->json('inventory_settings')->nullable()->after('has_inventory');
        });
    }

    public function down()
    {
        Schema::dropIfExists('attribute_inventory_values');
        
        Schema::table('attributes', function (Blueprint $table) {
            $table->dropColumn(['has_inventory', 'inventory_settings']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->integer('quantity')->nullable();
            $table->integer('low_stock_threshold')->nullable();
            $table->boolean('track_inventory')->default(false);
        });
    }
};
