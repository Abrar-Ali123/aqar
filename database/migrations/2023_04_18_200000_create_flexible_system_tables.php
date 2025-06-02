<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // جدول المكونات الأساسية
        Schema::create('system_components', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->enum('type', ['module', 'feature', 'workflow', 'form', 'report', 'ui', 'integration']);
            $table->boolean('is_core')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable();
            $table->json('requirements')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // جدول ترجمات المكونات
        Schema::create('system_component_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_component_id')->constrained()->onDelete('cascade');
            $table->string('locale', 5);
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique(['system_component_id', 'locale'], 'sys_comp_trans_unique');
        });

        // جدول الحقول المخصصة
        Schema::create('dynamic_fields', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('field_type', 50);
            $table->json('validation_rules')->nullable();
            $table->json('ui_settings')->nullable();
            $table->json('default_value')->nullable();
            $table->boolean('is_required')->default(false);
            $table->boolean('is_searchable')->default(false);
            $table->boolean('is_filterable')->default(false);
            $table->boolean('is_sortable')->default(false);
            $table->json('dependencies')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // جدول ترجمات الحقول المخصصة
        Schema::create('dynamic_field_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dynamic_field_id')->constrained()->onDelete('cascade');
            $table->string('locale', 5);
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('placeholder')->nullable();
            $table->text('help_text')->nullable();
            $table->timestamps();
            $table->unique(['dynamic_field_id', 'locale'], 'dyn_field_trans_unique');
        });

        // جدول قواعد العمل
        Schema::create('business_rules', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->json('conditions');
            $table->json('actions');
            $table->integer('priority')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('error_handling')->nullable();
            $table->json('logging_settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // جدول ترجمات قواعد العمل
        Schema::create('business_rule_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_rule_id')->constrained()->onDelete('cascade');
            $table->string('locale', 5);
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('error_message')->nullable();
            $table->text('success_message')->nullable();
            $table->timestamps();
            $table->unique(['business_rule_id', 'locale'], 'bus_rule_trans_unique');
        });

        // جدول قوالب الواجهة
        Schema::create('ui_templates', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->json('layout');
            $table->json('components')->nullable();
            $table->json('styles')->nullable();
            $table->json('behaviors')->nullable();
            $table->json('responsive_settings')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // جدول ترجمات قوالب الواجهة
        Schema::create('ui_template_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ui_template_id')->constrained()->onDelete('cascade');
            $table->string('locale', 5);
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique(['ui_template_id', 'locale'], 'ui_temp_trans_unique');
        });

        // جدول قواعد الأتمتة
        Schema::create('automation_rules', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('trigger_event');
            $table->json('conditions')->nullable();
            $table->json('actions');
            $table->json('schedule')->nullable();
            $table->json('retry_policy')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // جدول ترجمات قواعد الأتمتة
        Schema::create('automation_rule_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('automation_rule_id')->constrained()->onDelete('cascade');
            $table->string('locale', 5);
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('success_message')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
            $table->unique(['automation_rule_id', 'locale'], 'auto_rule_trans_unique');
        });
    }

    public function down(): void
    {
        // حذف الجداول بالترتيب العكسي لتجنب مشاكل المفاتيح الأجنبية
        Schema::dropIfExists('automation_rule_translations');
        Schema::dropIfExists('automation_rules');
        Schema::dropIfExists('ui_template_translations');
        Schema::dropIfExists('ui_templates');
        Schema::dropIfExists('business_rule_translations');
        Schema::dropIfExists('business_rules');
        Schema::dropIfExists('dynamic_field_translations');
        Schema::dropIfExists('dynamic_fields');
        Schema::dropIfExists('system_component_translations');
        Schema::dropIfExists('system_components');
    }
};
