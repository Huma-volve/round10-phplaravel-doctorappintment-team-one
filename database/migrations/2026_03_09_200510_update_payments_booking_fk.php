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
         Schema::table('payments', function (Blueprint $table) {

            $table->dropForeign(['booking_id']);

            $table->foreign('booking_id')
                  ->references('id')
                  ->on('bookings')
                  ->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
              Schema::table('payments', function (Blueprint $table) {

            $table->dropForeign(['booking_id']);

            $table->foreign('booking_id')
                  ->references('id')
                  ->on('bookings');

        });
    
    }
};
