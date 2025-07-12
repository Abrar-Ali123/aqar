<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('gateway');
            $table->string('transaction_id')->nullable();
            $table->string('status')->default('pending');
            $table->decimal('amount', 12, 2);
            $table->string('currency', 8)->default('SAR');
            $table->boolean('is_cod')->default(false);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('shipping_company_id')->nullable();
            $table->json('details')->nullable();
            $table->timestamps();
            $table->foreign('shipping_company_id')->references('id')->on('shipping_companies')->onDelete('set null');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
