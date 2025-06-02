<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('key')->index(); // مثل 'nav.home' أو 'actions.save'
            $table->string('group')->index(); // مثل 'messages' أو 'validation'
            $table->text('text');
            $table->string('locale', 2);
            $table->timestamps();
            
            $table->unique(['key', 'locale', 'group']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
