<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * #7 Fix: Sinkronisasi tabel counter dengan CounterController.
 * Menambahkan kolom ip_addr, count, dan indeks untuk pelacakan kunjungan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('counter', function (Blueprint $table) {
            if (!Schema::hasColumn('counter', 'ip_addr')) {
                $table->string('ip_addr', 45)->nullable()->after('date');
            }
            if (!Schema::hasColumn('counter', 'count')) {
                $table->unsignedInteger('count')->default(1)->after('ip_addr');
            }
        });
    }

    public function down(): void
    {
        Schema::table('counter', function (Blueprint $table) {
            if (Schema::hasColumn('counter', 'count')) {
                $table->dropColumn('count');
            }
            if (Schema::hasColumn('counter', 'ip_addr')) {
                $table->dropColumn('ip_addr');
            }
        });
    }
};
