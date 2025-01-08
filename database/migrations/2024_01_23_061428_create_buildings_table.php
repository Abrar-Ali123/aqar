<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('contract_id')->nullable();
            $table->unsignedBigInteger('owner_id');
             $table->unsignedBigInteger('feature_id')->nullable();
            $table->string('Number_of_floors');
            $table->string('Number_of_Apartments');
            $table->string('Office_ratio');
            $table->string('image');
            $table->string('video');
            $table->string('image_gallery');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('google_maps_url')->nullable();
            $table->unsignedBigInteger('facility_id');
            $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('cascade');
             $table->foreign('feature_id')->references('id')->on('features')->onDelete('cascade');
             $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('set null');
            $table->foreign('owner_id')->references('id')->on('owners')->onDelete('cascade');
               $table->timestamps();

        });
        Schema::enableForeignKeyConstraints();

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buildings');
    }
};
