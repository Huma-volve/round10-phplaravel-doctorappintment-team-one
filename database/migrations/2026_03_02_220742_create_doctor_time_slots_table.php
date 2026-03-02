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
        Schema::create('doctor_time_slots', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedInteger('doctor_id');
            $table->unsignedInteger('clinic_id');
            $table->timestamp('starts_at_utc');
            $table->timestamp('ends_at_utc');
            $table->enum('status', ['available', 'booked'])->default('available');
            $table->integer('capacity')->default(1);
            $table->timestamps();

            $table->foreign('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->foreign('clinic_id')->constrained('clinic')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_time_slots');
    }
};
