<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('position_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('position_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->timestamps();
            $table->unique(['position_id', 'locale']);
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('position_translations');
    }
};
