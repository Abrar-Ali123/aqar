<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('facility_pages', function (Blueprint $table) {
            if (!Schema::hasColumn('facility_pages', 'content')) {
                $table->json('content')->nullable()->after('meta_description');
            }
        });
    }

    public function down()
    {
        Schema::table('facility_pages', function (Blueprint $table) {
            if (Schema::hasColumn('facility_pages', 'content')) {
                $table->dropColumn('content');
            }
        });
    }
};
