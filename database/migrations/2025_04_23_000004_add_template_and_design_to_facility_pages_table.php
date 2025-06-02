<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('facility_pages', function (Blueprint $table) {
            $table->foreignId('template_id')->nullable()->after('facility_id')->constrained('page_templates')->nullOnDelete();
            $table->json('design_settings')->nullable()->after('settings');
        });
    }

    public function down()
    {
        Schema::table('facility_pages', function (Blueprint $table) {
            $table->dropForeign(['template_id']);
            $table->dropColumn(['template_id', 'design_settings']);
        });
    }
};
