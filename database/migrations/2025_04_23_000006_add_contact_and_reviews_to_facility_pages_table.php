<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('facility_pages', function (Blueprint $table) {
            $table->boolean('enable_contact_form')->default(false)->after('analytics_code');
            $table->boolean('enable_reviews')->default(false)->after('enable_contact_form');
        });
        Schema::create('facility_page_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_page_id')->constrained('facility_pages')->onDelete('cascade');
            $table->string('name');
            $table->string('email')->nullable();
            $table->text('review');
            $table->unsignedTinyInteger('rating')->nullable();
            $table->boolean('approved')->default(false);
            $table->timestamps();
        });
        Schema::create('facility_page_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_page_id')->constrained('facility_pages')->onDelete('cascade');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('visited_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::table('facility_pages', function (Blueprint $table) {
            $table->dropColumn(['enable_contact_form', 'enable_reviews']);
        });
        Schema::dropIfExists('facility_page_reviews');
        Schema::dropIfExists('facility_page_visits');
    }
};
