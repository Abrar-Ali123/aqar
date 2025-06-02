<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('template_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('page_templates')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('rating', 2, 1);
            $table->string('title');
            $table->text('content');
            $table->json('pros')->nullable();
            $table->json('cons')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('template_review_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained('template_reviews')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('content');
            $table->boolean('is_official')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('template_review_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained('template_reviews')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_helpful');
            $table->timestamps();
            $table->unique(['review_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('template_review_votes');
        Schema::dropIfExists('template_review_replies');
        Schema::dropIfExists('template_reviews');
    }
};
