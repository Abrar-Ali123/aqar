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
         Schema::create('tasks', function (Blueprint $table) {
             $table->id();
             $table->timestamp('deadline')->nullable(); // توقيت المهمة
             $table->unsignedBigInteger('created_by'); // معرف الموظف الذي أنشأ المهمة
             $table->string('status')->default('pending'); // حالة المهمة
             $table->string('priority')->default('medium'); // أهمية المهمة
             $table->string('type')->default('internal'); // نوع المهمة (داخلي/خارجي)
             $table->timestamps();

             $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
         });
     }

     public function down()
     {
        Schema::dropIfExists('tasks');
     }
 };
