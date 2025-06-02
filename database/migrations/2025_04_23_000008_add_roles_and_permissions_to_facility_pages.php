<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('facility_page_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_page_id')->constrained('facility_pages')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('role'); // owner, editor, viewer
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('facility_page_permissions');
    }
};
