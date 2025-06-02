<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gallery_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_id')->constrained()->onDelete('cascade');
            $table->string('locale', 5);
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('alt_text')->nullable();
            $table->timestamps();

            $table->unique(['gallery_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_translations');
    }
};
