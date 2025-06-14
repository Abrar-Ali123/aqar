<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('loyalty_points', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('transaction_id');
            $table->integer('points');
            $table->string('type')->default('payment');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('loyalty_points');
    }
};
