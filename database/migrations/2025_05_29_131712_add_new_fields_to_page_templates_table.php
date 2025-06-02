<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('page_templates', function (Blueprint $table) {
            $table->boolean('is_public')->default(false);
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('rating', 2, 1)->default(0);
            $table->integer('downloads')->default(0);
            $table->decimal('version', 4, 2)->default(1.0);
            $table->json('tags')->nullable();
            $table->decimal('price', 8, 2)->default(0);
            $table->json('features')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('custom_css')->nullable();
            $table->text('custom_js')->nullable();
            $table->json('required_plugins')->nullable();
            // Soft deletes already exist
        });

        Schema::create('page_template_category', function (Blueprint $table) {
            $table->foreignId('page_template_id')->constrained()->cascadeOnDelete();
            $table->foreignId('template_category_id')->constrained()->cascadeOnDelete();
            $table->primary(['page_template_id', 'template_category_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('page_template_category');
        
        Schema::table('page_templates', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropColumn([
                'is_public',
                'author_id',
                'rating',
                'downloads',
                'version',
                'tags',
                'price',
                'features',
                'seo_title',
                'seo_description',
                'custom_css',
                'custom_js',
                'required_plugins',
                'deleted_at'
            ]);
        });
    }
};
