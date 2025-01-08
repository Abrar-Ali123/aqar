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
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->boolean('required')->default(true);
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->string('icon')->nullable();
            $table->string('Symbol')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attributes');
    }
};
