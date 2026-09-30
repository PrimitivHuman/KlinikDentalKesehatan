<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * K5 Fix: Menghapus kolom nama_kategori yang redundan dari tabel galeri.
 * Data nama kategori sudah tersedia di tabel kategori via id_kategori,
 * sehingga menyimpannya ulang di galeri adalah denormalisasi yang berpotensi inkonsisten.
 */
return new class extends Migration
{
    /**
     * Hapus kolom nama_kategori dari tabel galeri.
     */
    public function up(): void
    {
        Schema::table('galeri', function (Blueprint $table) {
            if (Schema::hasColumn('galeri', 'nama_kategori')) {
                $table->dropColumn('nama_kategori');
            }
        });
    }

    /**
     * Kembalikan kolom nama_kategori ke tabel galeri (rollback).
     */
    public function down(): void
    {
        Schema::table('galeri', function (Blueprint $table) {
            if (!Schema::hasColumn('galeri', 'nama_kategori')) {
                $table->string('nama_kategori', 100)->nullable()->after('images');
            }
        });
    }
};
