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
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->enum('event', [
                'booking',
                'cancellation',
                'chat',
                'review',
                'payment',
                'system'
            ]);

            $table->enum('channel', [
                'email',
                'in_app'
            ]);

            $table->boolean('enabled')->default(true);

            $table->timestamps();

            $table->unique(['user_id', 'event', 'channel']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
    }
};
