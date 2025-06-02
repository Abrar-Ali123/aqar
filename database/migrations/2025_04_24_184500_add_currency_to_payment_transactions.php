<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->string('display_currency', 8)->nullable()->after('currency');
            $table->decimal('display_amount', 12, 2)->nullable()->after('amount');
            $table->decimal('conversion_rate', 12, 6)->nullable()->after('display_amount');
        });
    }
    public function down(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->dropColumn(['display_currency', 'display_amount', 'conversion_rate']);
        });
    }
};
