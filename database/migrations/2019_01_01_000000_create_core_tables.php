<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Membuat tabel-tabel inti aplikasi klinik.
 * Tabel: pasien, dokter, galeri, kategori, counter, tentang
 */
class CreateCoreTables extends Migration
{
    public function up()
    {
        // ── Tabel pasien ────────────────────────────────────────────────
        if (!Schema::hasTable('pasien')) {
            Schema::create('pasien', function (Blueprint $table) {
                $table->increments('id_pasien');
                $table->string('nama_pasien', 100);
                $table->date('tanggal_janji')->nullable();
                $table->string('email_pasien', 100)->nullable();
                $table->string('no_hp_pasien', 20)->nullable();
                $table->text('alamat_pasien')->nullable();
                $table->text('keluhan_pasien')->nullable();
                $table->string('total_harga_pasien', 50)->nullable();
                $table->string('tindakan_pasien', 100)->nullable();
                $table->string('template', 50)->nullable();
                $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
                $table->string('dokter_pilihan', 80)->nullable();
                $table->softDeletes();
            });
        }

        // ── Tabel dokter ────────────────────────────────────────────────
        if (!Schema::hasTable('dokter')) {
            Schema::create('dokter', function (Blueprint $table) {
                $table->string('id_dokter', 20)->primary();
                $table->string('nama_dokter', 100);
                $table->string('no_hp_dokter', 20)->nullable();
                $table->string('images', 255)->nullable();
                $table->string('email_dokter', 100)->nullable();
                $table->string('jadwal_dokter', 200)->nullable();
                $table->string('str_dokter', 50)->nullable();
                $table->string('sip_dokter', 50)->nullable();
                $table->softDeletes();
            });
        }

        // ── Tabel kategori (galeri) ──────────────────────────────────────
        if (!Schema::hasTable('kategori')) {
            Schema::create('kategori', function (Blueprint $table) {
                $table->string('id_kategori', 20)->primary();
                $table->string('nama_kategori', 100);
            });
        }

        // ── Tabel galeri ────────────────────────────────────────────────
        if (!Schema::hasTable('galeri')) {
            Schema::create('galeri', function (Blueprint $table) {
                $table->string('id_galeri', 20)->primary();
                $table->string('id_kategori', 20)->nullable();
                $table->string('judul', 200)->nullable();
                $table->text('deskripsi')->nullable();
                $table->string('images', 255)->nullable();
                $table->string('nama_kategori', 100)->nullable();
                $table->softDeletes();
            });
        }

        // ── Tabel counter (visitor) ──────────────────────────────────────
        if (!Schema::hasTable('counter')) {
            Schema::create('counter', function (Blueprint $table) {
                $table->date('date');
                $table->string('ip', 45)->nullable();
            });
        }

        // ── Tabel tentang ────────────────────────────────────────────────
        if (!Schema::hasTable('tentang')) {
            Schema::create('tentang', function (Blueprint $table) {
                $table->string('id_tentang', 20)->primary();
                $table->text('informasi_umum')->nullable();
                $table->string('foto_sampul', 255)->nullable();
                $table->text('visi')->nullable();
                $table->text('misi')->nullable();
                $table->text('tupoksi')->nullable();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('galeri');
        Schema::dropIfExists('kategori');
        Schema::dropIfExists('dokter');
        Schema::dropIfExists('pasien');
        Schema::dropIfExists('counter');
        Schema::dropIfExists('tentang');
    }
}
