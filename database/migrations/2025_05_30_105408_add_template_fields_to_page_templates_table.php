<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTemplateFieldsToPageTemplatesTable extends Migration
{
    public function up()
    {
        Schema::table('page_templates', function (Blueprint $table) {
            if (!Schema::hasColumn('page_templates', 'ui_components')) {
                $table->text('ui_components')->nullable();
            }
            if (!Schema::hasColumn('page_templates', 'layout')) {
                $table->text('layout')->nullable();
            }
            if (!Schema::hasColumn('page_templates', 'thumbnail')) {
                $table->string('thumbnail')->nullable();
            }
            if (!Schema::hasColumn('page_templates', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
            if (!Schema::hasColumn('page_templates', 'order')) {
                $table->integer('order')->default(0);
            }
        });
    }

    public function down()
    {
        Schema::table('page_templates', function (Blueprint $table) {
            $columns = [
                'ui_components',
                'layout',
                'thumbnail',
                'is_active',
                'order'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('page_templates', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}
