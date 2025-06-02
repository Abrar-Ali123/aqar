<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // تحقق أولاً من عدم وجود الأعمدة قبل الإضافة
        Schema::table('facility_pages', function (Blueprint $table) {
            if (!Schema::hasColumn('facility_pages', 'analytics_code')) {
                $table->string('analytics_code')->nullable()->after('meta_image');
            }
            if (!Schema::hasColumn('facility_pages', 'facebook_pixel')) {
                $table->string('facebook_pixel')->nullable()->after('analytics_code');
            }
        });
    }

    public function down()
    {
        Schema::table('facility_pages', function (Blueprint $table) {
            if (Schema::hasColumn('facility_pages', 'analytics_code')) {
                $table->dropColumn('analytics_code');
            }
            if (Schema::hasColumn('facility_pages', 'facebook_pixel')) {
                $table->dropColumn('facebook_pixel');
            }
        });
    }
};
