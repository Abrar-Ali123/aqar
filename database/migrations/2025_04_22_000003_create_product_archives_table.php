<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_archives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('archived_by')->constrained('users')->onDelete('cascade');
            $table->text('reason')->nullable();
            $table->timestamp('archived_at')->useCurrent();
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_archived')->default(false)->after('is_active');
            $table->integer('quantity')->default(0)->after('price');
            $table->integer('low_stock_threshold')->nullable()->after('quantity');
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_archives');
        
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_archived', 'quantity', 'low_stock_threshold']);
        });
    }
};
