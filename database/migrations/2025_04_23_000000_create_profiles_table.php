<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('position_id')->nullable();
            $table->date('hiring_date')->nullable();
            $table->string('job_number')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // إعادة تعليق العلاقات مع الأقسام والوظائف لتجاوز الخطأ
            // $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            // $table->foreign('position_id')->references('id')->on('positions')->onDelete('set null');
        });
    }
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
};
