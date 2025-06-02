<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('template_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('page_templates')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->json('layout')->nullable();
            $table->json('styles')->nullable();
            $table->json('components')->nullable();
            $table->decimal('version', 4, 2);
            $table->string('comment')->nullable();
            $table->boolean('is_major')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('template_revisions');
    }
};
