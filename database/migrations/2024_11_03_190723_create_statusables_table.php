<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusablesTable extends Migration
{
    public function up()
    {
        Schema::create('statusables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('status_id')->constrained('statuses')->onDelete('cascade');
            $table->morphs('statusable');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('statusables');
    }
}
