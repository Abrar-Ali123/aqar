<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('facility_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('thumbnail')->nullable();
            $table->string('preview_url')->nullable();
            $table->json('supported_components');
            $table->json('default_settings');
            $table->json('style_settings');
            $table->json('layout_settings');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('facility_template_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('facility_templates')->onDelete('cascade');
            $table->string('locale');
            $table->string('name');
            $table->text('description')->nullable();
            $table->unique(['template_id', 'locale']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('facility_template_translations');
        Schema::dropIfExists('facility_templates');
    }
};
