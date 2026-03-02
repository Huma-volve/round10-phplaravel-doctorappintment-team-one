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
        Schema::create('audit_logs', function (Blueprint $table) {
             $table->bigIncrements('id');
            $table->unsignedBigInteger('actor_user_id');
            $table->string('action', 80);
            $table->string('target_type', 80);
            $table->bigInteger('target_id');
            $table->string('ip', 45);
            $table->string('user_agent', 500);
            $table->json('meta');
            $table->timestamp('created_at_utc');

            $table->foreign('actor_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
