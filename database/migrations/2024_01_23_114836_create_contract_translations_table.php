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
        Schema::create('contract_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_id');
            $table->string('locale');
            $table->string('title');
            $table->text('content');
            $table->text('file');
            $table->timestamps();

            $table->unique(['contract_id', 'locale']);
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
        });
    }
    

    public function down()
    {
        Schema::dropIfExists('contract_translations');
    }
};
