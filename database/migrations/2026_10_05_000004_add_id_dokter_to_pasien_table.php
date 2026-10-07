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
        if (Schema::hasTable('pasien') && !Schema::hasColumn('pasien', 'id_dokter')) {
            Schema::table('pasien', function (Blueprint $table) {
                $table->string('id_dokter', 50)->nullable()->after('dokter_pilihan');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pasien') && Schema::hasColumn('pasien', 'id_dokter')) {
            Schema::table('pasien', function (Blueprint $table) {
                $table->dropColumn('id_dokter');
            });
        }
    }
};
