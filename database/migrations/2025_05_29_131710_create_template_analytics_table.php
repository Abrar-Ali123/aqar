<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('template_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('page_templates')->cascadeOnDelete();
            $table->integer('views')->default(0);
            $table->integer('downloads')->default(0);
            $table->integer('installations')->default(0);
            $table->decimal('bounce_rate', 5, 2)->nullable();
            $table->integer('avg_time')->nullable();
            $table->string('device_type');
            $table->string('browser');
            $table->string('country');
            $table->string('referrer')->nullable();
            $table->date('date');
            $table->timestamps();

            $table->unique(['template_id', 'date', 'device_type'], 'template_analytics_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('template_analytics');
    }
};
