<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->constrained('attributes')->onDelete('cascade');
            $table->text('value')->nullable();
            $table->unsignedBigInteger('attributeable_id');
            $table->string('attributeable_type');
            $table->timestamps();
            $table->index(['attributeable_id', 'attributeable_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('attribute_values');
    }
};
