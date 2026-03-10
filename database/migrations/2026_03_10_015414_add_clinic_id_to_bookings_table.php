<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   
public function up()
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->unsignedBigInteger('clinic_id')->nullable()->after('doctor_id');

        $table->foreign('clinic_id')
              ->references('id')
              ->on('clinics')
              ->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->dropForeign(['clinic_id']);
        $table->dropColumn('clinic_id');
    });
}
};
