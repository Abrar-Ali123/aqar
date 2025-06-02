<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();
        });

        Schema::create('wishlist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wishlist_id')->constrained()->onDelete('cascade');
            $table->morphs('wishlistable'); // للربط مع المنتجات أو الخدمات
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('price_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('alertable');
            $table->decimal('target_price', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_notified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('browsing_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('browseable');
            $table->timestamp('last_viewed_at');
            $table->integer('view_count')->default(1);
            $table->timestamps();
        });

        Schema::create('product_comparisons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name')->nullable();
            $table->timestamps();
        });

        Schema::create('comparison_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_comparison_id')->constrained()->onDelete('cascade');
            $table->morphs('comparable');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comparison_items');
        Schema::dropIfExists('product_comparisons');
        Schema::dropIfExists('browsing_history');
        Schema::dropIfExists('price_alerts');
        Schema::dropIfExists('wishlist_items');
        Schema::dropIfExists('wishlists');
    }
};
