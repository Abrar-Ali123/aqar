<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['new', 'in_progress', 'completed'])->default('new');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->string('category')->nullable();
            $table->date('due_date')->nullable();
            $table->json('assigned_to')->nullable(); // [user_ids]
            $table->json('comments')->nullable(); // [{user_id, content, created_at}]
            $table->json('attachments')->nullable(); // [{name, path, type, size}]
            $table->json('time_logs')->nullable(); // [{start, end, duration}]
            $table->json('subtasks')->nullable(); // [{title, completed}]
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
