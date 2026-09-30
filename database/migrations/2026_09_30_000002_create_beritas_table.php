<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * R2: Membuat tabel berita untuk fitur blog/artikel klinik.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('beritas')) {
            Schema::create('beritas', function (Blueprint $table) {
                $table->string('id_berita', 20)->primary();
                $table->string('judul', 200);
                $table->string('slug', 220)->unique();
                $table->text('isi');
                $table->string('images', 255)->nullable();
                $table->string('penulis', 100)->nullable();
                $table->enum('status', ['draft', 'published'])->default('draft');
                $table->date('tgl_terbit')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('beritas');
    }
};
