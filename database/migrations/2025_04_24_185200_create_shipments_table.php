<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('provider');
            $table->string('tracking_number')->nullable();
            $table->string('status')->default('pending');
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->string('address');
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->json('details')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
