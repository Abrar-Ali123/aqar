<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('facility_components', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // slider, products, features, etc.
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->json('default_settings');
            $table->json('style_settings');
            $table->json('data_settings');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('facility_page_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_page_id')->constrained('facility_pages')->onDelete('cascade');
            $table->foreignId('component_id')->constrained('facility_components')->onDelete('cascade');
            $table->integer('order')->default(0);
            $table->json('settings');
            $table->json('style');
            $table->json('data');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('facility_component_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('component_id')->constrained('facility_components')->onDelete('cascade');
            $table->string('locale');
            $table->string('name');
            $table->text('description')->nullable();
            $table->unique(['component_id', 'locale']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('facility_component_translations');
        Schema::dropIfExists('facility_page_components');
        Schema::dropIfExists('facility_components');
    }
};
