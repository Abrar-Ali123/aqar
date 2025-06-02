<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('facility_pages', function (Blueprint $table) {
            if (!Schema::hasColumn('facility_pages', 'name')) {
                $table->string('name')->after('template_id');
            }
        });
    }

    public function down()
    {
        Schema::table('facility_pages', function (Blueprint $table) {
            if (Schema::hasColumn('facility_pages', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
};
