<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('loyalty_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('required_points');
            $table->decimal('points_multiplier', 4, 2)->default(1.0);
            $table->json('benefits')->nullable();
            $table->timestamps();
        });

        Schema::create('user_loyalty', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('loyalty_tier_id')->constrained()->onDelete('cascade');
            $table->integer('total_points')->default(0);
            $table->integer('available_points')->default(0);
            $table->timestamps();
        });

        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('points');
            $table->string('type'); // earned, redeemed, expired, referral
            $table->string('description');
            $table->morphs('transactionable'); // للربط مع الطلبات أو الإحالات
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('referred_id')->constrained('users')->onDelete('cascade');
            $table->integer('points_awarded')->default(0);
            $table->boolean('is_converted')->default(false);
            $table->timestamp('converted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('referrals');
        Schema::dropIfExists('point_transactions');
        Schema::dropIfExists('user_loyalty');
        Schema::dropIfExists('loyalty_tiers');
    }
};
