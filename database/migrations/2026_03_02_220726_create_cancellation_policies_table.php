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
        Schema::create('cancellation_policies', function (Blueprint $table) {
            $table->id();
            $table->boolean('allowed_cancel');
            $table->integer('allowed_cancel_before_minutes')->nullable();
            $table->enum('fee_type', ['none', 'fixed', 'percent'])->default('none');
            $table->integer('fee_value')->nullable();
            $table->boolean('allow_reschedule');
            $table->integer('allowed_reschedule_before_minutes')->nullable();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cancellation_policies');
    }
};
