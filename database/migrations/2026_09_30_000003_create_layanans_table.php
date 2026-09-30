<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * R3: Membuat tabel layanans untuk mengelola layanan/perawatan gigi klinik.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('layanans')) {
            Schema::create('layanans', function (Blueprint $table) {
                $table->string('id_layanan', 20)->primary();
                $table->string('nama_layanan', 150);
                $table->text('deskripsi')->nullable();
                $table->string('harga_mulai', 50)->nullable();
                $table->string('harga_sampai', 50)->nullable();
                $table->string('durasi', 50)->nullable();
                $table->string('images', 255)->nullable();
                $table->string('ikon', 50)->nullable()->comment('Class icon BoxIcons, misal: bx-tooth');
                $table->boolean('aktif')->default(true);
                $table->integer('urutan')->default(0)->comment('Urutan tampil di halaman publik');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('layanans');
    }
};
