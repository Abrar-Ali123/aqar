<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyPropertyTypeColumnInProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // تعديل عمود property_type ليكون VARCHAR(50) بدلاً من الحجم الحالي
            $table->string('property_type', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // إعادة العمود إلى حجمه الأصلي إذا كنت تعرفه
            // $table->string('property_type', 10)->change(); // مثال
        });
    }
}
