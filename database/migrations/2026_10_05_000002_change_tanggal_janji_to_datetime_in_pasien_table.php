<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * #8 Fix: Mengubah tipe kolom tanggal_janji dari DATE menjadi DATETIME
 * agar dapat menyimpan jam reservasi dari form appointment (datetime-local).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pasien', function (Blueprint $table) {
            $table->dateTime('tanggal_janji')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pasien', function (Blueprint $table) {
            $table->date('tanggal_janji')->nullable()->change();
        });
    }
};
