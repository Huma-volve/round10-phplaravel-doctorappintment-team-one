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
        Schema::create('messages', function (Blueprint $table) {
           $table->id('id');
            $table->unsignedBigInteger('conversation_id');
            $table->unsignedBigInteger('sender_user_id');
            $table->enum('type', ['text', 'image', 'video'])->default('text');
            $table->text('body');
            $table->string('media_url', 255);
            $table->bigInteger('media_size_bytes');
            $table->string('media_mime', 120);
            $table->timestamp('sent_at_utc');
            $table->timestamp('read_at_utc');
            $table->timestamps();

            $table->foreign('conversation_id')->references('id')->on('conversations');
            $table->foreign('sender_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
