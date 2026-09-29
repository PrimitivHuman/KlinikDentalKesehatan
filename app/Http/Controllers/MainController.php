<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Tentang;
use App\Models\Dokter;
use App\Models\Pasien;
use Illuminate\Http\Request;
use SimpleXMLElement;

class MainController extends Controller
{
    public function index() {
        $galeri = Galeri::get();
        $tentang = Tentang::first();
        $dokter = Dokter::get();
        $pasien = Pasien::get();


        $kategori = Galeri::kategori()->get();
        return view('main.index', [
            'title' => 'Beranda',
            'menu' => 'home',
            'galeri' => $galeri,
            'tentang' => $tentang,
            'dokter' => $dokter,
            'pasien' => $pasien
        ]);
    }

    public function appointment() {
        $dokter = Dokter::all();

        return view('main.appointment', [
            'title'  => 'Form Reservasi Janji Temu',
            'menu'   => 'appointment',
            'dokter' => $dokter,
        ]);
    }
                                
}
