<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('facility_pages', function (Blueprint $table) {
            $table->string('meta_title')->nullable()->after('slug');
            $table->string('meta_description')->nullable()->after('meta_title');
            $table->string('meta_image')->nullable()->after('meta_description');
        });
    }

    public function down()
    {
        Schema::table('facility_pages', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description', 'meta_image']);
        });
    }
};
