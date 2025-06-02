<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('facility_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->constrained()->onDelete('cascade');
            $table->string('visitor_id');
            $table->string('device_type');
            $table->string('referrer')->nullable();
            $table->integer('page_views');
            $table->float('time_on_page');
            $table->float('duration');
            $table->json('interaction_data');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('facility_analytics');
    }
};
