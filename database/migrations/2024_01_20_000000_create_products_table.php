<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
                        $table->string('sku')->unique()->nullable(); // Making it nullable for now to avoid issues with existing logic if any
            $table->foreignId('facility_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('business_categories')->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->decimal('price', 10, 2)->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('locale');
            $table->string('name');
            $table->text('info')->nullable();
            $table->text('description')->nullable();
            $table->unique(['product_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('product_translations');
        Schema::dropIfExists('products');
        Schema::enableForeignKeyConstraints();
    }
};
