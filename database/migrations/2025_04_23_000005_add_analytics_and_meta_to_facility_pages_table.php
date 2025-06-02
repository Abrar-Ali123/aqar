<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('facility_pages', function (Blueprint $table) {
            $table->json('meta')->nullable()->after('design_settings');
            $table->string('analytics_code')->nullable()->after('meta');
        });
    }

    public function down()
    {
        Schema::table('facility_pages', function (Blueprint $table) {
            $table->dropColumn(['meta', 'analytics_code']);
        });
    }
};
