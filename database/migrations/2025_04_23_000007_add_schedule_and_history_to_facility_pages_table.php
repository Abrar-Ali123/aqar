<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('facility_pages', function (Blueprint $table) {
            $table->timestamp('scheduled_from')->nullable()->after('enable_reviews');
            $table->timestamp('scheduled_to')->nullable()->after('scheduled_from');
        });
        Schema::create('facility_page_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_page_id')->constrained('facility_pages')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->json('snapshot');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('facility_pages', function (Blueprint $table) {
            $table->dropColumn(['scheduled_from', 'scheduled_to']);
        });
        Schema::dropIfExists('facility_page_histories');
    }
};
