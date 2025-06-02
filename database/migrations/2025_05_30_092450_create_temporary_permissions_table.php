<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('temporary_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->foreignId('granted_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('expires_at');
            $table->text('reason')->nullable();
            $table->timestamps();

            $table->index(['role_id', 'permission_id', 'expires_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('temporary_permissions');
    }
};
