<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant')->constrained('users')->onDelete('cascade');
            $table->foreignId('manager')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('bank_emp')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('bank_id')->nullable()->constrained('banks')->onDelete('set null');
            $table->foreignId('facility_id')->nullable()->constrained('facilities')->onDelete('cascade'); // منشأة القرض
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null'); // آخر من قام بالتحديث
            $table->date('birth');
            $table->decimal('salary', 10, 2);
            $table->decimal('commitments', 10, 2)->nullable();
            $table->boolean('military')->default(false);
            $table->string('rank')->nullable();
            $table->date('employment')->nullable();
            $table->boolean('supported')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('loans');
    }
}
