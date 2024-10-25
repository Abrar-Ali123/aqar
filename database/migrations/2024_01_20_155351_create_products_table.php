<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(true);
            $table->double('price');
            $table->string('image')->nullable();
            $table->string('video')->nullable();
            $table->text('image_gallery')->nullable();

            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('google_maps_url')->nullable();

            $table->unsignedBigInteger('facility_id');
            $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('cascade');

            $table->enum('property_type', ['sale', 'rent'])->default('sale');
            $table->unsignedBigInteger('owner_user_id')->nullable();
            $table->foreign('owner_user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('seller_user_id')->nullable();
            $table->foreign('seller_user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
        Schema::enableForeignKeyConstraints();

    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
