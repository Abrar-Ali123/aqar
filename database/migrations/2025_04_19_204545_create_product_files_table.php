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
        Schema::create('product_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('path');
            $table->string('type')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->boolean('is_downloadable')->default(true);
            $table->unsignedInteger('download_count')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('download_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_files');
    }
};
