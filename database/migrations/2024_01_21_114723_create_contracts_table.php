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
        Schema::disableForeignKeyConstraints();
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->date('start_date');
            $table->date('end_date');
             $table->timestamps();
             $table->unsignedBigInteger('facility_id');
             $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('cascade');
              $table->foreign('owner_id')->references('id')->on('owners')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::enableForeignKeyConstraints();
    }



    public function down()
    {
        Schema::dropIfExists('contracts');
    }
};
