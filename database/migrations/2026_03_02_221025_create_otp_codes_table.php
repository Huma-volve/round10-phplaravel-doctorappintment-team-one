<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('otp_codes', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('user_id');
            $table->enum('channel', ['email']);
            $table->string('destination', 255);
            $table->enum('purpose', ['login', 'verify_phone', 'verify_email', 'reset_password']);
            $table->string('code_hash', 255);
            $table->timestamp('expires_at_utc');
            $table->tinyInteger('attempts');
            $table->tinyInteger('max_attempts');
            $table->tinyInteger('send_count');
            $table->timestamp('last_sent_at_utc');
            $table->timestamp('consumed_at_utc');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_codes');
    }
};
