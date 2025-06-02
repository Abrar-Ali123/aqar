<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('department_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('department_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->timestamps();
            $table->unique(['department_id', 'locale']);
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('department_translations');
    }
};
