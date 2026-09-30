<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Layanan;

/**
 * Seeder data layanan klinik default.
 * Jalankan: php artisan db:seed --class=LayananSeeder
 */
class LayananSeeder extends Seeder
{
    public function run(): void
    {
        $layanans = [
            [
                'nama_layanan' => 'Konsultasi & Pemeriksaan',
                'deskripsi'    => 'Konsultasi dan pemeriksaan gigi rutin untuk memelihara kesehatan gigi dan mulut, mendeteksi masalah sejak dini.',
                'harga_mulai'  => 50000,
                'harga_sampai' => 150000,
                'durasi'       => '30 menit',
                'ikon'         => 'bx-conversation',
                'aktif'        => true,
                'urutan'       => 1,
            ],
            [
                'nama_layanan' => 'Penambalan Gigi',
                'deskripsi'    => 'Prosedur penambalan gigi untuk mengembalikan bentuk dan fungsi gigi yang rusak atau berlubang menggunakan bahan komposit modern.',
                'harga_mulai'  => 100000,
                'harga_sampai' => 300000,
                'durasi'       => '30 - 60 menit',
                'ikon'         => 'bx-shield',
                'aktif'        => true,
                'urutan'       => 2,
            ],
            [
                'nama_layanan' => 'Pencabutan Gigi',
                'deskripsi'    => 'Prosedur pencabutan gigi yang bermasalah dan tidak bisa diperbaiki lagi dari gusi, dilakukan dengan anestesi lokal.',
                'harga_mulai'  => 100000,
                'harga_sampai' => 350000,
                'durasi'       => '20 - 45 menit',
                'ikon'         => 'bx-minus-circle',
                'aktif'        => true,
                'urutan'       => 3,
            ],
            [
                'nama_layanan' => 'Scaling / Pembersihan Karang Gigi',
                'deskripsi'    => 'Pembersihan karang gigi (Scaling) untuk mengatasi gusi berdarah dan bau mulut menggunakan alat ultrasonik.',
                'harga_mulai'  => 150000,
                'harga_sampai' => 400000,
                'durasi'       => '45 - 60 menit',
                'ikon'         => 'bx-water',
                'aktif'        => true,
                'urutan'       => 4,
            ],
            [
                'nama_layanan' => 'Pemasangan Kawat Gigi',
                'deskripsi'    => 'Pemasangan kawat gigi (behel) untuk memperbaiki susunan gigi yang tidak rapi atau posisi rahang yang tidak normal.',
                'harga_mulai'  => 3000000,
                'harga_sampai' => 8000000,
                'durasi'       => '1 - 2 jam (awal)',
                'ikon'         => 'bx-link',
                'aktif'        => true,
                'urutan'       => 5,
            ],
            [
                'nama_layanan' => 'Bleaching / Pemutihan Gigi',
                'deskripsi'    => 'Prosedur dental whitening untuk mengembalikan estetika gigi dan mendapatkan warna cerah yang natural.',
                'harga_mulai'  => 500000,
                'harga_sampai' => 1500000,
                'durasi'       => '60 - 90 menit',
                'ikon'         => 'bx-sun',
                'aktif'        => true,
                'urutan'       => 6,
            ],
        ];

        foreach ($layanans as $data) {
            $id = Layanan::generateID();
            Layanan::create(array_merge(['id_layanan' => $id], $data));
        }

        $this->command->info('✅ ' . count($layanans) . ' layanan klinik berhasil ditambahkan.');
    }
}
