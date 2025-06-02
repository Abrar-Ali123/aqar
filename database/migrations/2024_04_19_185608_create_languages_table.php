<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguagesTable extends Migration
{
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name');         // اسم اللغة بلغتها الأصلية
            $table->string('code');         // رمز اللغة (مثل ar, en)
            $table->string('direction')->default('ltr'); // اتجاه اللغة (rtl or ltr)
            $table->string('flag')->nullable();  // علم الدولة
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_required')->default(false); // هل اللغة إجبارية
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('languages');
    }
}
