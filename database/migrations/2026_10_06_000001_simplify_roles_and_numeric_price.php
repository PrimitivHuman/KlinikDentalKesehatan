<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 1) Semua akun dijadikan superadmin (saat ini hanya ada satu role).
     * 2) total_harga_pasien: dari teks ("Rp 150.000") menjadi angka rupiah.
     */
    public function up(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'role')) {
            DB::table('users')->update(['role' => 'superadmin']);
        }

        if (Schema::hasTable('pasien') && Schema::hasColumn('pasien', 'total_harga_pasien')) {
            // Bersihkan data lama: simpan hanya digit
            DB::table('pasien')->select('id_pasien', 'total_harga_pasien')->orderBy('id_pasien')
                ->get()
                ->each(function ($row) {
                    if ($row->total_harga_pasien === null) {
                        return;
                    }
                    $digits = preg_replace('/\D/', '', (string) $row->total_harga_pasien);
                    DB::table('pasien')->where('id_pasien', $row->id_pasien)
                        ->update(['total_harga_pasien' => $digits === '' ? null : $digits]);
                });

            Schema::table('pasien', function (Blueprint $table) {
                $table->unsignedBigInteger('total_harga_pasien')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('pasien') && Schema::hasColumn('pasien', 'total_harga_pasien')) {
            Schema::table('pasien', function (Blueprint $table) {
                $table->string('total_harga_pasien', 50)->nullable()->change();
            });
        }
    }
};
