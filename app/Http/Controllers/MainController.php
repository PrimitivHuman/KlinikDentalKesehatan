<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use App\Models\Tentang;
use App\Models\Dokter;
use App\Models\Layanan;
use App\Models\Berita;
use Illuminate\Http\Request;

class MainController extends Controller
{
    /**
     * Menampilkan halaman utama (landing page) klinik.
     * K3 Fix: Hapus Pasien::get() — data pasien tidak dipakai di view frontend.
     * R2 & R3: Tambahkan data Berita (published) dan Layanan (aktif) untuk frontend.
     */
    public function index() {
        $galeri   = Galeri::get();
        $tentang  = Tentang::first();
        $dokter   = Dokter::get();
        $kategori = Galeri::kategori()->get();

        // R3: Layanan aktif untuk section Services di halaman publik
        $layanans = Layanan::aktif()->get();

        // R2: Artikel terbaru (published) untuk section berita/blog (opsional)
        $beritas = Berita::where('status', 'published')
            ->orderBy('tgl_terbit', 'desc')
            ->limit(3)
            ->get();

        return view('main.index', [
            'title'    => 'Beranda',
            'menu'     => 'home',
            'galeri'   => $galeri,
            'tentang'  => $tentang,
            'dokter'   => $dokter,
            'layanans' => $layanans,
            'beritas'  => $beritas,
        ]);
    }

    /**
     * Menampilkan halaman form reservasi janji temu.
     * R3: Tambahkan daftar layanan agar pasien bisa memilih layanan yang diinginkan.
     */
    public function appointment() {
        $dokter   = Dokter::all();
        $layanans = Layanan::aktif()->get();

        return view('main.appointment', [
            'title'    => 'Form Reservasi Janji Temu',
            'menu'     => 'appointment',
            'dokter'   => $dokter,
            'layanans' => $layanans,
        ]);
    }
}
