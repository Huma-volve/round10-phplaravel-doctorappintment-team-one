<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->string('media_url', 255)->nullable()->change();
            $table->bigInteger('media_size_bytes')->nullable()->change();
            $table->string('media_mime', 120)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->string('media_url', 255)->nullable(false)->change();
            $table->bigInteger('media_size_bytes')->nullable(false)->change();
            $table->string('media_mime', 120)->nullable(false)->change();
        });
    }
};
