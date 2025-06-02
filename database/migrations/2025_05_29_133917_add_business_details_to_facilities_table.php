<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->json('working_hours')->nullable();
            $table->json('social_media')->nullable();
            $table->string('website')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('tax_number')->nullable();
        });
    }

    public function down()
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn([
                'working_hours',
                'social_media',
                'website',
                'registration_number',
                'tax_number'
            ]);
        });
    }
};
