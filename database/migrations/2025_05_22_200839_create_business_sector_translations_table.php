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
        Schema::create('business_sector_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_sector_id')->constrained()->cascadeOnDelete();
            $table->string('locale');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->unique(['business_sector_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_sector_translations');
    }
};
