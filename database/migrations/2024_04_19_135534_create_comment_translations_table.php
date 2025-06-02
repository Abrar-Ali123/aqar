<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comment_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comment_id')->constrained()->onDelete('cascade');
            $table->string('locale', 5);
            $table->text('content');
            $table->timestamps();

            $table->unique(['comment_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comment_translations');
    }
};
