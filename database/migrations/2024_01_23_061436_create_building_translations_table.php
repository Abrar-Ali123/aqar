<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('building_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('building_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->text('notes')->nullable();
            $table->text('rules')->nullable();
            $table->timestamps();
            $table->unique(['building_id', 'locale']);
            $table->foreign('building_id')->references('id')->on('buildings')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('building_translations');
    }
};
