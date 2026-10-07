<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use App\Models\Tentang;
use App\Models\Dokter;
use App\Models\Layanan;
use App\Models\Berita;
use App\Models\Kegiatan;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MainController extends Controller
{
    /**
     * Menampilkan halaman utama (landing page) klinik.
     * #24 Fix: Cache query landing page untuk performa optimal.
     */
    public function index()
    {
        $galeri    = Cache::remember('home_galeri', 600, fn () => Galeri::latest('id_galeri')->limit(12)->get());
        $tentang   = Cache::remember('home_tentang', 600, fn () => Tentang::first());
        $dokter    = Cache::remember('home_dokter', 600, fn () => Dokter::limit(8)->get());
        $layanans  = Cache::remember('home_layanans', 600, fn () => Layanan::aktif()->get());
        $beritas   = Cache::remember('home_beritas', 600, fn () => Berita::where('status', 'published')
            ->orderBy('tgl_terbit', 'desc')
            ->limit(3)
            ->get());
        $kegiatans = Cache::remember('home_kegiatans', 600, fn () => Kegiatan::orderBy('tgl_kegiatan', 'desc')
            ->limit(3)
            ->get());

        return view('main.index', [
            'title'     => 'Beranda',
            'menu'      => 'home',
            'galeri'    => $galeri,
            'tentang'   => $tentang,
            'dokter'    => $dokter,
            'layanans'  => $layanans,
            'beritas'   => $beritas,
            'kegiatans' => $kegiatans,
        ]);
    }

    /**
     * Menampilkan halaman form reservasi janji temu.
     */
    public function appointment()
    {
        $dokter   = Dokter::all();
        $layanans = Layanan::aktif()->get();

        return view('main.appointment', [
            'title'    => 'Form Reservasi Janji Temu',
            'menu'     => 'appointment',
            'dokter'   => $dokter,
            'layanans' => $layanans,
        ]);
    }

    /**
     * #18 Fix: Halaman publik daftar artikel & berita kesehatan gigi.
     */
    public function berita()
    {
        $beritas = Berita::where('status', 'published')
            ->orderBy('tgl_terbit', 'desc')
            ->paginate(6);

        return view('main.berita', [
            'title'   => 'Artikel & Edukasi Kesehatan Gigi',
            'menu'    => 'berita',
            'beritas' => $beritas,
        ]);
    }

    /**
     * #18 Fix: Halaman publik detail artikel/berita berdasarkan slug.
     */
    public function berita_detail($slug)
    {
        $berita = Berita::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $terbaru = Berita::where('status', 'published')
            ->where('id_berita', '!=', $berita->id_berita)
            ->orderBy('tgl_terbit', 'desc')
            ->limit(4)
            ->get();

        return view('main.berita_detail', [
            'title'   => $berita->judul . ' — Artikel Klinik',
            'menu'    => 'berita',
            'berita'  => $berita,
            'terbaru' => $terbaru,
        ]);
    }

    /**
     * #38 Fix: XML Sitemap untuk SEO search engine.
     */
    public function sitemap()
    {
        $beritas   = Berita::where('status', 'published')->get();
        $layanans  = Layanan::aktif()->get();
        $kegiatans = Kegiatan::orderBy('tgl_kegiatan', 'desc')->get();

        return response()->view('main.sitemap', [
            'beritas'   => $beritas,
            'layanans'  => $layanans,
            'kegiatans' => $kegiatans,
        ])->header('Content-Type', 'text/xml');
    }

    /**
     * Halaman publik daftar agenda kegiatan klinik.
     */
    public function agenda()
    {
        $kegiatans = Kegiatan::orderBy('tgl_kegiatan', 'desc')->paginate(6);

        return view('main.agenda', [
            'title'     => 'Agenda & Kegiatan Klinik',
            'menu'      => 'agenda',
            'kegiatans' => $kegiatans,
        ]);
    }

    /**
     * Halaman publik detail agenda kegiatan klinik.
     */
    public function agenda_detail($id)
    {
        $kegiatan = Kegiatan::where('id_kegiatan', $id)->firstOrFail();
        $terbaru  = Kegiatan::where('id_kegiatan', '!=', $id)
            ->orderBy('tgl_kegiatan', 'desc')
            ->limit(4)
            ->get();

        return view('main.agenda_detail', [
            'title'    => $kegiatan->judul_kegiatan . ' — Agenda Kegiatan',
            'menu'     => 'agenda',
            'kegiatan' => $kegiatan,
            'terbaru'  => $terbaru,
        ]);
    }
}
