<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('gateway');
            $table->string('subscription_id');
            $table->string('status')->default('active');
            $table->decimal('amount', 12, 2);
            $table->string('currency', 8)->default('SAR');
            $table->string('interval')->default('month');
            $table->timestamp('started_at');
            $table->timestamp('ends_at')->nullable();
            $table->json('details')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
