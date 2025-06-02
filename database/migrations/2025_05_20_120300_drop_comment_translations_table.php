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
        Schema::dropIfExists('comment_translations');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('comment_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comment_id')->constrained()->cascadeOnDelete();
            $table->string('locale');
            $table->text('content');
            $table->timestamps();

            $table->unique(['comment_id', 'locale']);
        });
    }
};
