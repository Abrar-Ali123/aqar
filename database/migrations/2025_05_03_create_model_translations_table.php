<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('model_translations', function (Blueprint $table) {
            $table->id();
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->string('locale');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('slug')->nullable();
            $table->text('details')->nullable();
            $table->string('address')->nullable();
            $table->text('notes')->nullable();

            $table->unique(['model_type', 'model_id', 'locale']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('model_translations');
    }
};
