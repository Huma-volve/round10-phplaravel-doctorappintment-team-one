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
           $table->id();
            $table->foreignId('patient_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->foreignId('time_slot_id')->constrained('doctor_time_slots')->cascadeOnDelete();
            $table->timestamp('starts_at_utc');
            $table->timestamp('ends_at_utc');
            $table->enum('status', ['draft', 'pending_payment', 'confirmed', 'completed', 'cancelled_by_patient', 'cancelled_by_doctor', 'rescheduled']);
            $table->enum('payment_method', ['stripe', 'cash'])->default('cash');
            $table->enum('payment_status', ['unpaid', 'paid', 'failed', 'refunded', 'partially_refunded'])->default('unpaid');
            $table->bigInteger('amount_cents');
            $table->char('currency', 3);
            $table->timestamps();

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
