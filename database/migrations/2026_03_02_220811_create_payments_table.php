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
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('booking_id');
            $table->enum('provider', ['stripe','cash'])->default('cash');
            $table->string('provider_payment_id', 255);
            $table->string('provider_customer_id', 255);
            $table->enum('status', ['initiated', 'requires_action', 'succeeded', 'failed', 'refunded', 'partially_refunded'])->default('initiated');
            $table->bigInteger('amount_cents');
            $table->bigInteger('refunded_cents');
            $table->char('currency', 3);
            $table->json('meta');
            $table->timestamps();

            $table->foreign('booking_id')->references('id')->on('bookings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
