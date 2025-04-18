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
         Schema::create('user_translations', function (Blueprint $table) {
             $table->id();
             $table->unsignedBigInteger('user_id');
             $table->string('locale')->index();
             $table->string('name');
             $table->string('info')->nullable();
             $table->timestamps();
             $table->unique(['user_id', 'locale']);
             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
         });
     }


     public function down()
     {
         Schema::dropIfExists('user_translations');
     }
 };
