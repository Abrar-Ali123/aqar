<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('role_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action'); // created, updated, deleted
            $table->json('changes')->nullable();
            $table->string('ip_address');
            $table->timestamps();

            $table->index(['role_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('role_audits');
    }
};
