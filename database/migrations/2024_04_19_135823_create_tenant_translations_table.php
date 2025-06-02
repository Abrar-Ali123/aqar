<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('locale', 5);
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_translations');
    }
};
