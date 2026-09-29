<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom status dan dokter_pilihan ke tabel pasien.
     */
    public function up(): void
    {
        Schema::table('pasien', function (Blueprint $table) {
            if (!Schema::hasColumn('pasien', 'status')) {
                $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])
                      ->default('pending')
                      ->after('tindakan_pasien');
            }
            if (!Schema::hasColumn('pasien', 'dokter_pilihan')) {
                $table->string('dokter_pilihan', 80)->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pasien', function (Blueprint $table) {
            if (Schema::hasColumn('pasien', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('pasien', 'dokter_pilihan')) {
                $table->dropColumn('dokter_pilihan');
            }
        });
    }
};
