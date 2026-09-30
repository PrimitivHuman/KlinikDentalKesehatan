<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Galeri;
use App\Models\Tentang;
use App\Models\User;
use App\Models\Counter;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Kegiatan;
use App\Models\Berita;
use App\Models\Layanan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Halaman Dashboard / Beranda Admin.
     */
    public function index() {
        $countinformation = Tentang::count();
        $countcategory    = Galeri::kategori()->count();
        $countgaleri      = Galeri::count();
        $countkegiatan    = Kegiatan::count();
        $countuser        = User::count();
        $countpasien      = Pasien::count();
        $countdokter      = Dokter::count();
        $countervisits    = Counter::getCounterData();

        // R4: Analytics — statistik pasien per bulan (12 bulan terakhir)
        $pasienPerBulan = DB::table('pasien')
            ->select(DB::raw('MONTH(tanggal_janji) as bulan, COUNT(*) as total'))
            ->whereYear('tanggal_janji', date('Y'))
            ->whereNull('deleted_at')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->keyBy('bulan');

        $chartPasienBulanan = [];
        for ($m = 1; $m <= 12; $m++) {
            $chartPasienBulanan[] = $pasienPerBulan->has($m) ? $pasienPerBulan[$m]->total : 0;
        }

        // R4: Statistik status pasien
        $statusStats = Pasien::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        // R5: Jumlah berita & layanan untuk dashboard
        $countberita  = Berita::count();
        $countlayanan = Layanan::count();

        return view('admin.index', [
            'title'                => 'Beranda',
            'menu'                 => 'home',
            'information_count'    => $countinformation,
            'category_count'       => $countcategory,
            'galeri_count'         => $countgaleri,
            'kegiatan_count'       => $countkegiatan,
            'user_count'           => $countuser,
            'pasien_count'         => $countpasien,
            'dokter_count'         => $countdokter,
            'countervisit'         => $countervisits,
            'chart_pasien_bulanan' => $chartPasienBulanan,
            'status_stats'         => $statusStats,
            'berita_count'         => $countberita,
            'layanan_count'        => $countlayanan,
        ]);
    }

    /**
     * Halaman Data Dokter.
     */
    public function dokter() {
        $dokter = Dokter::paginate(8);

        return view('admin.dokter', [
            'title'  => "Data Dokter",
            'menu'   => "dokter",
            'dokter' => $dokter,
        ]);
    }

    /**
     * Form Tambah Dokter Baru.
     */
    public function dokter_new() {
        return view('admin.dokter_new', [
            'menu'  => "dokter",
            'title' => "Tambah Dokter Baru",
        ]);
    }

    /**
     * R3: Halaman Manajemen Layanan Klinik.
     */
    public function layanan() {
        $layanans = Layanan::orderBy('urutan')->paginate(10);
        return view('admin.layanan', [
            'title'    => 'Manajemen Layanan Klinik',
            'menu'     => 'layanan',
            'layanans' => $layanans,
        ]);
    }

    /**
     * R3: Form Tambah Layanan Baru.
     */
    public function layanan_new() {
        return view('admin.layanan_new', [
            'title' => 'Tambah Layanan Baru',
            'menu'  => 'layanan',
        ]);
    }

    /**
     * Halaman Galeri Foto.
     */
    public function gallery() {
        $gallery = Galeri::paginate(8);

        return view('admin.gallery', [
            'title'   => "Galeri Foto",
            'menu'    => "galeri",
            'gallery' => $gallery,
       ]);
    }

    /**
     * Form Tambah Galeri Foto Baru.
     */
    public function gallery_new() {
        $category = Galeri::kategori()->get();

        return view('admin.gallery_new', [
            'category' => $category,
            'menu'     => "galeri",
            'title'    => "Upload Foto Baru",
        ]);
    }

    /**
     * Halaman Data Agenda Kegiatan Klinik.
     */
    public function activity() {
        $activities = Kegiatan::paginate(8);

        return view('admin.activity', [
            'menu'       => "kegiatan",
            'title'      => 'Data Agenda Kegiatan',
            'activities' => $activities,
        ]);
    }

    /**
     * Form Tambah Agenda Kegiatan Baru.
     */
    public function activity_new() {
        return view('admin.activity_new', [
            'menu'  => "kegiatan",
            'title' => 'Tambah Agenda Kegiatan Baru',
        ]);
    }

    /**
     * Halaman Informasi Umum Klinik.
     */
    public function about() {
        $about = Tentang::get();

        return view('admin.about', [
            'menu'  => "informasi",
            'title' => 'Data Informasi Umum',
            'about' => $about,
        ]);
    }

    /**
     * Halaman Kategori Galeri Foto.
     */
    public function kategori() {
        $category = Galeri::kategori()->paginate(20);

        return view('admin.gallery_category', [
            'category' => $category,
            'menu'     => "kategori",
            'title'    => "Kategori Foto",
        ]);
    }

    /**
     * Form Tambah Kategori Foto Baru.
     */
    public function kategori_new() {
        return view('admin.gallery_category_new', [
            'menu'  => "kategori",
            'title' => "Kategori Foto Baru",
        ]);
    }

    /**
     * Halaman Form Login Admin.
     */
    public function login() {
        return view('admin.login', [
            'title' => 'Login Admin',
        ]);
    }

    /**
     * Halaman Daftar Pengguna / Akun Admin.
     */
    public function account() {
        $account = User::paginate(20);

        return view('admin.account', [
            'menu'    => "pengguna",
            'title'   => 'Data Akun Admin',
            'account' => $account,
        ]);
    }

    /**
     * Form Tambah Akun Admin Baru.
     */
    public function account_new() {
        return view('admin.account_new', [
            'menu'  => "pengguna",
            'title' => 'Tambah Akun Admin Baru',
        ]);
    }

    /**
     * Halaman Data Pasien Reservasi.
     */
    public function pasien() {
        $pasien = Pasien::orderBy('id_pasien', 'desc')->paginate(10);

        return view('admin.pasien', [
            'title'  => 'Data Janji Temu Pasien',
            'menu'   => 'pasien',
            'pasien' => $pasien,
        ]);
    }

    /**
     * Halaman Sampah / Trash Bin (Melihat data soft deleted).
     */
    public function trash() {
        // K8 Fix: Gunakan paginate agar tidak memuat semua data terhapus sekaligus
        $deletedPasien   = Pasien::onlyTrashed()->paginate(10, ['*'], 'pasien_page');
        $deletedDokter   = Dokter::onlyTrashed()->paginate(10, ['*'], 'dokter_page');
        $deletedGaleri   = Galeri::onlyTrashed()->paginate(10, ['*'], 'galeri_page');
        $deletedKegiatan = Kegiatan::onlyTrashed()->paginate(10, ['*'], 'kegiatan_page');

        return view('admin.trash', [
            'title'           => 'Sampah / Recycle Bin',
            'menu'            => 'trash',
            'deletedPasien'   => $deletedPasien,
            'deletedDokter'   => $deletedDokter,
            'deletedGaleri'   => $deletedGaleri,
            'deletedKegiatan' => $deletedKegiatan,
        ]);
    }

    /**
     * Memulihkan (Restore) data soft deleted.
     */
    public function restore($type, $id) {
        $realId = decrypt($id);
        $restored = false;

        if ($type === 'pasien') {
            $restored = Pasien::onlyTrashed()->where('id_pasien', $realId)->restore();
        } elseif ($type === 'dokter') {
            $restored = Dokter::onlyTrashed()->where('id_dokter', $realId)->restore();
        } elseif ($type === 'galeri') {
            $restored = Galeri::onlyTrashed()->where('id_galeri', $realId)->restore();
        } elseif ($type === 'kegiatan') {
            $restored = Kegiatan::onlyTrashed()->where('id_kegiatan', $realId)->restore();
        }

        if ($restored) {
            // K14 Fix: Log aksi restore untuk audit trail
            Log::info('Data restored from trash', [
                'type'    => $type,
                'id'      => $realId,
                'by_user' => Auth::id(),
            ]);
            return redirect('/admin-area/trash')->with('success', 'Berhasil memulihkan data.');
        }

        return redirect('/admin-area/trash')->with('error', 'Gagal memulihkan data.');
    }

    /**
     * Menghapus permanen (Force Delete) data soft deleted.
     */
    public function force_delete($type, $id) {
        $realId = decrypt($id);
        $deleted = false;

        if ($type === 'pasien') {
            $item = Pasien::onlyTrashed()->where('id_pasien', $realId)->first();
            if ($item) $deleted = $item->forceDelete();
        } elseif ($type === 'dokter') {
            $item = Dokter::onlyTrashed()->where('id_dokter', $realId)->first();
            if ($item) {
                Dokter::deleteImage($realId);
                $deleted = $item->forceDelete();
            }
        } elseif ($type === 'galeri') {
            $item = Galeri::onlyTrashed()->where('id_galeri', $realId)->first();
            if ($item) {
                Galeri::deleteImage($realId);
                $deleted = $item->forceDelete();
            }
        } elseif ($type === 'kegiatan') {
            $item = Kegiatan::onlyTrashed()->where('id_kegiatan', $realId)->first();
            if ($item) {
                Kegiatan::deleteImage($realId);
                $deleted = $item->forceDelete();
            }
        }

        if ($deleted) {
            // K14 Fix: Log aksi force delete untuk audit trail
            Log::warning('Data permanently deleted', [
                'type'    => $type,
                'id'      => $realId,
                'by_user' => Auth::id(),
            ]);
            return redirect('/admin-area/trash')->with('success', 'Berhasil menghapus data secara permanen.');
        }

        return redirect('/admin-area/trash')->with('error', 'Gagal menghapus permanen data.');
    }
}
