<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Menambahkan kolom deleted_at (SoftDeletes) ke tabel pasien, dokter, dan galeri.
 * P3-Fix: Dengan SoftDeletes, data yang "dihapus" tidak hilang permanen dari database.
 *         Data masih bisa dipulihkan jika diperlukan.
 */
class AddSoftDeletesToPasienDokterGaleri extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up()
    {
        // Tambah soft delete ke tabel pasien
        if (!Schema::hasColumn('pasien', 'deleted_at')) {
            Schema::table('pasien', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Tambah soft delete ke tabel dokter
        if (!Schema::hasColumn('dokter', 'deleted_at')) {
            Schema::table('dokter', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Tambah soft delete ke tabel galeri
        if (!Schema::hasColumn('galeri', 'deleted_at')) {
            Schema::table('galeri', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Balikkan migrasi (rollback).
     */
    public function down()
    {
        Schema::table('pasien', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('dokter', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('galeri', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
