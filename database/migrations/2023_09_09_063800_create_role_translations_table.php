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
        Schema::create('role_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id'); // تعيين مفتاح خارجي للجدول roles
            $table->string('name');
            $table->string('locale');
            $table->timestamps();
        
            $table->unique(['role_id', 'locale']);
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('role_translations');
    }
};
