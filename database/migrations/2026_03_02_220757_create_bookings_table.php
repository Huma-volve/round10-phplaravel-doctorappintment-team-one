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
        Schema::create('bookings', function (Blueprint $table) {
           $table->id('id');
            $table->unsignedInteger('patient_id');
            $table->unsignedInteger('doctor_id');
            $table->unsignedInteger('time_slot_id');
            $table->timestamp('starts_at_utc');
            $table->timestamp('ends_at_utc');
            $table->enum('status', ['draft', 'pending_payment', 'confirmed', 'completed', 'cancelled_by_patient', 'cancelled_by_doctor', 'rescheduled']);
            $table->enum('payment_method', ['stripe', 'cash'])->default('cash');
            $table->enum('payment_status', ['unpaid', 'paid', 'failed', 'refunded', 'partially_refunded'])->default('unpaid');
            $table->bigInteger('amount_cents');
            $table->char('currency', 3);
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('users');
            $table->foreign('doctor_id')->references('id')->on('doctors');
            $table->foreign('time_slot_id')->references('id')->on('doctor_time_slots');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
