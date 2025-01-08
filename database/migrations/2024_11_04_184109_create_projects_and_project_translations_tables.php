<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsAndProjectTranslationsTables extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('google_maps_url')->nullable();
            $table->foreignId('facility_id')->constrained()->onDelete('cascade');
            $table->string('image')->nullable();
            $table->foreignId('seller_user_id')->constrained('users')->onDelete('cascade');
            $table->enum('project_type', ['apartment_complex', 'villa_group']);
            $table->timestamps();
        });

        Schema::create('project_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('locale')->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unique(['project_id', 'locale']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_translations');
        Schema::dropIfExists('projects');
    }
}
