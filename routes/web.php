<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\TentangController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\LayananController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

// Public Routes
Route::get('/', [MainController::class, 'index']);
Route::get('/appointment', [MainController::class, 'appointment']);
Route::post('/appointment', [PasienController::class, 'pasien_submit']);

// Auth Routes
Route::get('/login', [AdminController::class, 'login'])->name('login')->middleware('guest');
Route::post('/login', [AkunController::class, 'login'])->middleware('throttle:5,1');
Route::get('/logout', [AkunController::class, 'logout'])->middleware('auth');

// Admin Authenticated Routes (semua user yang sudah login)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/admin-area', [AdminController::class, 'index']);

    // Pengaturan profil akun sendiri
    Route::get('/admin-area/pengaturan', [AkunController::class, 'settings']);
    Route::post('/admin-area/pengaturan/update', [AkunController::class, 'settings_update']);

    // Detail profil akun sendiri (bisa diakses semua role)
    Route::get('/admin-area/akun/detail', [AkunController::class, 'account_detail']);

    // Pasien Management
    Route::get('/admin-area/pasien', [AdminController::class, 'pasien']);
    Route::post('/admin-area/pasien', [PasienController::class, 'pasien_search']);
    Route::get('/admin-area/pasien/edit/{id}', [PasienController::class, 'pasien_edit']);
    Route::get('/admin-area/pasien/edit/{id}/{from}', [PasienController::class, 'pasien_edit']);
    Route::post('/admin-area/pasien/edit/update', [PasienController::class, 'pasien_update']);
    Route::get('/admin-area/pasien/delete/{id}', [PasienController::class, 'pasien_delete']);
    Route::get('/admin-area/pasien/status/{id}/{status}', [PasienController::class, 'pasien_status_update']);
    Route::get('/admin-area/pasien/invoice/{id}', [PasienController::class, 'pasien_invoice']);
    Route::get('/export-data', [PasienController::class, 'export'])->name('Pasien.export');

    // Agenda Kegiatan (Activity)
    Route::get('/admin-area/kegiatan', [AdminController::class, 'activity']);
    Route::get('/admin-area/kegiatan/new', [AdminController::class, 'activity_new']);
    Route::post('/admin-area/kegiatan/submit', [KegiatanController::class, 'activity_submit']);
    Route::get('/admin-area/kegiatan/edit/{id}', [KegiatanController::class, 'activity_edit']);
    Route::post('/admin-area/kegiatan/edit/update', [KegiatanController::class, 'activity_update']);
    Route::get('/admin-area/kegiatan/delete/{id}', [KegiatanController::class, 'activity_delete']);

    // Dokter Management
    Route::get('/admin-area/dokter', [AdminController::class, 'dokter']);
    Route::post('/admin-area/dokter', [DokterController::class, 'dokter_search']);
    Route::get('/admin-area/dokter/new', [AdminController::class, 'dokter_new']);
    Route::post('/admin-area/dokter/submit', [DokterController::class, 'dokter_submit']);
    Route::get('/admin-area/dokter/edit/{id}', [DokterController::class, 'dokter_edit']);
    Route::post('/admin-area/dokter/edit/update', [DokterController::class, 'dokter_update']);
    Route::get('/admin-area/dokter/delete/{id}', [DokterController::class, 'dokter_delete']);

    // Galeri Management
    Route::get('/admin-area/galeri', [AdminController::class, 'gallery']);
    Route::post('/admin-area/galeri', [GaleriController::class, 'gallery_search']);
    Route::get('/admin-area/galeri/new', [AdminController::class, 'gallery_new']);
    Route::post('/admin-area/galeri/submit', [GaleriController::class, 'gallery_submit']);
    Route::get('/admin-area/galeri/edit/{id}', [GaleriController::class, 'gallery_edit']);
    Route::post('/admin-area/galeri/edit/update', [GaleriController::class, 'gallery_update']);
    Route::get('/admin-area/galeri/delete/{id}', [GaleriController::class, 'gallery_delete']);

    // Kategori Galeri
    Route::get('/admin-area/kategori-galeri', [AdminController::class, 'kategori']);
    Route::post('/admin-area/kategori-galeri', [GaleriController::class, 'kategori_search']);
    Route::get('/admin-area/kategori-galeri/new', [AdminController::class, 'kategori_new']);
    Route::post('/admin-area/kategori-galeri/submit', [GaleriController::class, 'kategori_submit']);
    Route::get('/admin-area/kategori-galeri/detail/{id}', [GaleriController::class, 'kategori_details']);
    Route::get('/admin-area/kategori-galeri/edit/{id}', [GaleriController::class, 'kategori_edit']);
    Route::post('/admin-area/kategori-galeri/edit/update', [GaleriController::class, 'kategori_update']);
    Route::get('/admin-area/kategori-galeri/delete/{id}', [GaleriController::class, 'kategori_delete']);

    // Informasi Umum / About
    Route::get('/admin-area/informasi-umum', [AdminController::class, 'about']);
    Route::post('/admin-area/informasi-umum/edit-foto', [TentangController::class, 'photo_edit']);
    Route::post('/admin-area/informasi-umum/edit-deskripsi', [TentangController::class, 'informasi_edit']);
    Route::post('/admin-area/informasi-umum/edit-visi', [TentangController::class, 'visi_edit']);
    Route::post('/admin-area/informasi-umum/edit-misi', [TentangController::class, 'misi_edit']);

    // Trash / Recycle Bin
    Route::get('/admin-area/trash', [AdminController::class, 'trash']);
    Route::get('/admin-area/trash/restore/{type}/{id}', [AdminController::class, 'restore']);
    Route::get('/admin-area/trash/force-delete/{type}/{id}', [AdminController::class, 'force_delete']);

    // R2: Berita / Artikel Klinik
    Route::get('/admin-area/berita', [BeritaController::class, 'index']);
    Route::get('/admin-area/berita/new', [BeritaController::class, 'create']);
    Route::post('/admin-area/berita/submit', [BeritaController::class, 'store']);
    Route::get('/admin-area/berita/edit/{id}', [BeritaController::class, 'edit']);
    Route::post('/admin-area/berita/edit/update', [BeritaController::class, 'update']);
    Route::get('/admin-area/berita/delete/{id}', [BeritaController::class, 'destroy']);
    Route::get('/admin-area/berita/toggle/{id}', [BeritaController::class, 'toggleStatus']);

    // R3: Layanan / Perawatan Klinik
    Route::get('/admin-area/layanan', [LayananController::class, 'index']);
    Route::get('/admin-area/layanan/new', [LayananController::class, 'create']);
    Route::post('/admin-area/layanan/submit', [LayananController::class, 'store']);
    Route::get('/admin-area/layanan/edit/{id}', [LayananController::class, 'edit']);
    Route::post('/admin-area/layanan/edit/update', [LayananController::class, 'update']);
    Route::get('/admin-area/layanan/delete/{id}', [LayananController::class, 'destroy']);
    Route::get('/admin-area/layanan/toggle/{id}', [LayananController::class, 'toggleAktif']);
});

// K1 Fix: Route khusus superadmin — Manajemen Akun Pengguna
// Hanya akun dengan role 'superadmin' yang dapat mengelola data user lain.
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/admin-area/akun', [AdminController::class, 'account']);
    Route::post('/admin-area/akun', [AkunController::class, 'account_search']);
    Route::get('/admin-area/akun/new', [AdminController::class, 'account_new']);
    Route::post('/admin-area/akun/submit', [AkunController::class, 'account_submit']);
    Route::get('/admin-area/akun/edit/{id}/{from}', [AkunController::class, 'account_edit']);
    Route::post('/admin-area/akun/edit/update', [AkunController::class, 'account_update']);
    Route::get('/admin-area/akun/delete/{id}/{from}', [AkunController::class, 'account_delete']);
});
