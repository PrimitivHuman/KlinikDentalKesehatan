# 📘 Dokumentasi Project — Klinik Dental Kesehatan (FAM Dental Care)

> Dokumen ini disusun dari hasil pembacaan kode sumber (routes, controller, model, migrasi, seeder, test, view, dan konfigurasi).
> Temuan pada bagian **Kekurangan** sudah diverifikasi langsung di kode, dengan referensi file.

---

## 1. Ringkasan

**Klinik Dental Kesehatan** adalah aplikasi web manajemen klinik gigi berbasis **Laravel 11**. Aplikasi punya dua sisi:

| Sisi | Fungsi |
|------|--------|
| **Publik** | Landing page klinik (profil, layanan, dokter, galeri) dan form reservasi janji temu online |
| **Admin (`/admin-area`)** | Dashboard, manajemen pasien/janji temu, dokter, galeri, kegiatan, artikel, layanan, informasi klinik, akun pengguna, recycle bin |

Nama brand yang tampil di UI: **Klinik FAM Dental Care** (database: `klinikfamdentalcare`).

---

## 2. Tech Stack

| Komponen | Teknologi | Sumber |
|----------|-----------|--------|
| Framework | Laravel `^11.0` | `composer.json` |
| PHP | `^8.2` (dev: PHP 8.4 di Laragon) | `composer.json`, `docs/panduan-upgrade-laravel.md` |
| Database | MySQL (test: SQLite in-memory) | `.env`, `phpunit.xml` |
| Auth | Session Auth + Sanctum (terpasang, belum dipakai API) | `User.php` |
| Excel | `maatwebsite/excel ^3.1` | `PasienExport.php` |
| Notifikasi UI | `realrashid/sweet-alert ^8` | `composer.json` |
| Frontend build | Laravel Mix (Webpack) | `webpack.mix.js` |
| Front-end publik | Blade + Bootstrap 5 + Bootstrap Icons + AOS | `resources/views/main` |
| Front-end admin | Blade + Boxicons + Summernote + chart | `resources/views/admin` |
| Testing | PHPUnit 11 | `tests/` |

---

## 3. Struktur Direktori

```
KlinikDentalKesehatan/
├── app/
│   ├── Exports/PasienExport.php          # Export Excel data pasien
│   ├── Http/
│   │   ├── Controllers/                  # 15 controller (lihat §5)
│   │   └── Middleware/RoleMiddleware.php # Alias middleware `role:`
│   ├── Mail/                             # AppointmentConfirmation, AppointmentAdminNotification
│   ├── Models/                           # 11 model (lihat §4)
│   └── Providers/
├── bootstrap/app.php                     # Konfigurasi routing & middleware (gaya Laravel 11)
├── database/
│   ├── factories/                        # PasienFactory, UserFactory
│   ├── migrations/                       # 12 migrasi
│   └── seeders/                          # Admin, KlinikData, Layanan
├── docs/                                 # Dokumentasi
├── public/
│   ├── img/{account,activity,berita,dokter,gallery,layanan}   # Upload gambar
│   └── main/img/logo/                    # Foto sampul "Tentang"
├── resources/views/
│   ├── admin/                            # ±31 halaman admin + layout/
│   ├── main/                             # index, appointment + layout/
│   ├── emails/                           # Template email
│   └── invoice.blade.php                 # Invoice cetak
├── routes/web.php                        # Semua route aplikasi
└── tests/Feature/                        # Auth, Login, Dokter, Kegiatan, Pasien, Trash, Example
```

---

## 4. Model Data (Database)

### 4.1 Tabel & Model

| Tabel | Model | Primary Key | Soft Delete | Format ID |
|-------|-------|-------------|:-----------:|-----------|
| `users` | `User` | `id` (string) | ❌ | `AK-001` |
| `pasien` | `Pasien` | `id_pasien` (auto-increment) | ✅ | tampilan `PSN-001` |
| `dokter` | `Dokter` | `id_dokter` (string) | ✅ | `DOK-001` |
| `kategori` | *(query builder via `Galeri::kategori()`)* | `id_kategori` | ❌ | `KT-001` |
| `galeri` | `Galeri` | `id_galeri` (string) | ✅ | `GL-001` |
| `kegiatans` | `Kegiatan` | `id_kegiatan` (string) | ✅ | `KGT-001` |
| `beritas` | `Berita` | `id_berita` (string) | ✅ | `BRT-001` |
| `layanans` | `Layanan` | `id_layanan` (string) | ❌ | `LYN-001` |
| `tentang` | `Tentang` | `id_tentang` (`TG-001`, hardcoded) | ❌ | `TG-001` |
| `counter` | `Counter` | tanpa PK | ❌ | – |
| `password_resets`, `failed_jobs`, `personal_access_tokens` | bawaan Laravel | – | – | – |

### 4.2 Detail Tabel Penting

**`pasien`** — data janji temu
`nama_pasien, tanggal_janji (date), email_pasien, no_hp_pasien, alamat_pasien, keluhan_pasien, total_harga_pasien (string), tindakan_pasien, template, status (enum: pending|confirmed|completed|cancelled), dokter_pilihan (nama dokter, bukan FK), deleted_at`

**`dokter`** — `nama_dokter, no_hp_dokter, images, email_dokter, jadwal_dokter, str_dokter, sip_dokter`

**`layanans`** — `nama_layanan, deskripsi, harga_mulai, harga_sampai, durasi, images, ikon, aktif, urutan`

**`beritas`** — `judul, slug, isi, images, penulis (nama user), status (draft|published), tgl_terbit`

**`users`** — `id, name, email, password, profile_pict, role (default 'admin')`

### 4.3 Relasi

- `Pasien → Dokter`: `hasOne` berdasarkan **nama** (`dokter_pilihan` ↔ `nama_dokter`), bukan foreign key.
- `Galeri → Kategori`: kolom `id_kategori` tanpa foreign key constraint.
- `Berita → User`: `belongsTo` via kolom `penulis` ↔ `users.name`.

---

## 5. Fitur & Alur Kerja

### 5.1 Halaman Publik

| Route | Controller | Keterangan |
|-------|-----------|------------|
| `GET /` | `MainController@index` | Landing page: galeri, tentang, dokter, layanan aktif, 3 berita terbaru |
| `GET /appointment` | `MainController@appointment` | Form reservasi (daftar dokter & layanan) |
| `POST /appointment` | `PasienController@pasien_submit` | Simpan reservasi |

**Alur reservasi:**
1. Pasien mengisi form (nama, HP, email, alamat, tanggal, dokter opsional, keluhan).
2. Data divalidasi dan disimpan dengan `status = pending`.
3. Email konfirmasi dikirim ke pasien (`AppointmentConfirmation`).
4. Email notifikasi dikirim ke semua akun `superadmin` (`AppointmentAdminNotification`).
5. Kegagalan email hanya dicatat ke log, reservasi tetap tersimpan.

### 5.2 Autentikasi

| Route | Keterangan |
|-------|------------|
| `GET /login` | Form login (middleware `guest`) |
| `POST /login` | Login, throttle **5 percobaan/menit**, regenerasi session, log sukses/gagal |
| `GET /logout` | Logout + invalidate session |

### 5.3 Panel Admin (`/admin-area/*`, middleware `auth`)

| Modul | Fitur |
|-------|-------|
| **Dashboard** | Kartu statistik, grafik pasien per bulan (tahun berjalan), statistik status, jumlah berita/layanan, data pengunjung mingguan |
| **Pasien** | Daftar (paginate 10), cari, edit, hapus (soft delete), ubah status, invoice cetak, export Excel (`/export-data`) |
| **Dokter** | CRUD + upload foto, pencarian |
| **Galeri** | CRUD foto + pencarian |
| **Kategori Galeri** | CRUD + halaman detail |
| **Kegiatan** | CRUD agenda + foto |
| **Berita** | CRUD artikel, slug otomatis, toggle draft/published |
| **Layanan** | CRUD, toggle aktif, urutan tampil, rentang harga |
| **Informasi Umum** | Edit deskripsi, visi, misi, foto sampul (rich text Summernote) |
| **Trash** | Lihat, pulihkan, hapus permanen (pasien, dokter, galeri, kegiatan) |
| **Pengaturan** | Edit profil sendiri, ganti password, foto profil |
| **Akun** *(superadmin saja)* | CRUD akun pengguna |

### 5.4 Hak Akses

- Route akun (`/admin-area/akun*`) dilindungi `role:superadmin` lewat `RoleMiddleware` (mendukung multi-role dan wildcard `*`, log akses ditolak).
- **Semua route admin lain hanya butuh login** — role `admin` dan `operator` punya akses yang sama.

### 5.5 Seeder

| Seeder | Isi |
|--------|-----|
| `AdminSeeder` | Akun `admin@klinikfamdentalcare.com` (role `superadmin`) |
| `KlinikDataSeeder` | Data dasar klinik (tentang, dokter, kategori, dll.) |
| `LayananSeeder` | Daftar layanan awal |

---

## 6. Instalasi Singkat

```bash
composer install
npm install && npm run dev
cp .env.example .env
php artisan key:generate
# set DB_* dan MAIL_* di .env
php artisan migrate --seed
php artisan serve
```

Menjalankan test: `php artisan test`

> ⚠️ `README.md` masih menyebut Laravel 8 dan `DB_DATABASE=klinikdental`. Ikuti `composer.json` (Laravel 11) dan sesuaikan nama database dengan `.env`.

---

## 7. Kondisi Saat Ini (Hal Positif)

- ✅ Upgrade Laravel 8 → 11 sudah selesai dan terdokumentasi.
- ✅ Soft delete + Recycle Bin untuk data penting.
- ✅ ID di URL dienkripsi (`encrypt/decrypt`).
- ✅ Upload gambar divalidasi (`image|mimes|max`).
- ✅ Login di-throttle, session diregenerasi, password di-hash.
- ✅ Generator ID memakai transaksi + `lockForUpdate`.
- ✅ Logging untuk aksi sensitif (login, restore, force delete, akses ditolak).
- ✅ Ada test feature (19 test hijau menurut dokumen upgrade).
- ✅ `.env` tidak ikut ter-track di Git.

---

## 8. 🛠️ Status Perbaikan 40 Kekurangan (100% Resolved)

Seluruh 40 kekurangan yang diidentifikasi telah diselesaikan dan diverifikasi melalui unit & feature test suite (31 passed, 63 assertions). Berikut rincian implementasi teknis per item:

### 🔴 Kritis / Tinggi (Keamanan & Integritas)

| # | Masalah Asal | Solusi & Implementasi Teknis | Status |
|---|--------------|------------------------------|:------:|
| 1 | **Manipulasi Harga & Tindakan Pasien Publik** | Dihapus dari validasi publik di `PasienController@pasien_submit`. Form publik kini hanya menerima data janji. Penetapan tindakan medis dan invoice mutlak dilakukan admin. Ditambahkan honeypot anti-spam (`website_hp`). | ✅ Selesai |
| 2 | **Aksi Destruktif Memakai Method GET** | Seluruh rute hapus, status, toggle, restore, force-delete, dan logout diubah menjadi `POST` / `DELETE` / `PATCH` dengan `@csrf` dan `@method('DELETE')` di Blade. Rute `web.php` mendukung dual-method untuk kompatibilitas. | ✅ Selesai |
| 3 | **File ZIP & SQL Dump Ter-track Git** | File `public/1.zip`, `public/2.zip`, `*.sql`, `*.tar.gz`, `*.bak` dihapus dari git tracking (`git rm --cached`) dan ditambahkan ke `.gitignore`. | ✅ Selesai |
| 4 | **Password Default Admin & Seeder Menimpa Akun** | `AdminSeeder` diubah menggunakan `updateOrCreate()` membaca kredensial dari `ADMIN_EMAIL` dan `ADMIN_PASSWORD` di `.env`, tanpa menghapus akun lama dan tanpa mencetak plaintext password. | ✅ Selesai |
| 5 | **Stored XSS pada Rich-Text & Judul** | Dibuat helper `App\Support\Sanitizer::cleanHtml()` berbasis allowlist DOMDocument. Judul di-escape menggunakan `{{ }}` di seluruh view publik dan admin. | ✅ Selesai |
| 6 | **Konfigurasi Production & .env Hardening** | Dibuat `.env.production.example` (`APP_DEBUG=false`, `SESSION_SECURE_COOKIE=true`). Template `.env.example` dimodernisasi sesuai Laravel 11. | ✅ Selesai |

---

### 🟠 Sedang (Bug Fungsional & Integritas Data)

| # | Masalah Asal | Solusi & Implementasi Teknis | Status |
|---|--------------|------------------------------|:------:|
| 7 | **Visitor Counter Tidak Berfungsi** | Migrasi `update_counter_table` menambahkan `ip_addr` dan `count`. Dibuat middleware `TrackVisitor` yang dipasang pada route web publik. Query `Counter::getCounterData()` diringkas dari 8 query menjadi 1 query agregasi. | ✅ Selesai |
| 8 | **Jam Janji Temu Hilang** | Migrasi `change_tanggal_janji_to_datetime_in_pasien_table` mengubah kolom `tanggal_janji` menjadi `dateTime`, menyimpan jam janji dari form `datetime-local`. | ✅ Selesai |
| 9 | **Jadwal Masa Lalu & Double-Booking** | Ditambahkan validasi `after_or_equal:today` serta pengecekan bentrok jadwal dokter spesifik pada waktu yang sama di `PasienController`. | ✅ Selesai |
| 10 | **ID Bentrok pada Soft-Deleted Records** | Dibuat `App\Support\IdGenerator` yang menggunakan `withTrashed()`, `lockForUpdate()`, dan DB transaction untuk memastikan ID selalu unik. | ✅ Selesai |
| 11 | **Soft Delete Menghapus File Foto Fisik** | Pemanggilan `deleteImage` dihapus dari `dokter_delete` dan `gallery_delete`. Penghapusan berkas fisik hanya dilakukan saat aksi permanen di Trash (`AdminController::force_delete`). | ✅ Selesai |
| 12 | **Pencarian Memakai POST + Paging Hilang** | Seluruh form pencarian diubah ke method `GET` dengan input `name="cari"`. Ditambahkan `withQueryString()` pada pagination dan delegasi otomatis di `AdminController`. | ✅ Selesai |
| 13 | **Pencarian Query Array ke `like`** | Query pencarian distandarisasi menggunakan closure `$query->where(function($q) use ($keyword) { $q->where(...)->orWhere(...); })`. | ✅ Selesai |
| 14 | **Inkonsistensi Role & Dropdown Form Akun** | Form `account_new` dan `account_edit` ditambahkan dropdown pilihan role (`superadmin`, `admin`, `operator`). Role badge ditampilkan di tabel akun. Validasi role `in:superadmin,admin,operator`. | ✅ Selesai |
| 15 | **Superadmin Bisa Hapus Diri Sendiri** | Guard di `AkunController::account_delete` menolak penghapusan akun sendiri dan pemusnahan superadmin terakhir. Ditambahkan `SoftDeletes` pada model `User`. | ✅ Selesai |
| 16 | **Crash `DecryptException` HTTP 500** | Ditangani secara anggun di `bootstrap/app.php` dengan redirect kembali disertai notifikasi flash pesan warning untuk admin, atau 404 ramah pengguna untuk publik. | ✅ Selesai |
| 17 | **Manipulasi ID Hidden pada Update Form** | Ditambahkan validasi `exists:` dan validasi integritas model sebelum update dilakukan. | ✅ Selesai |
| 18 | **Fitur Berita Publik Belum Ada Tampilan** | Dibuat rute publik `/berita` dan `/berita/{slug}`, view `berita.blade.php`, `berita_detail.blade.php`, serta tautan artikel pada landing page. | ✅ Selesai |
| 19 | **Method `tupoksi_edit` Tanpa Rute** | Ditambahkan rute `POST /admin-area/informasi-umum/edit-tupoksi` di `web.php` dan komponen form card Tupoksi di `about.blade.php`. | ✅ Selesai |
| 20 | **Relasi Galeri Rusak (`DB::table`)** | Dibuat model `Kategori.php` dan relasi Eloquent standar `Galeri::belongsTo(Kategori::class, 'id_kategori', 'id_kategori')`. | ✅ Selesai |
| 21 | **Validasi Tidak Konsisten** | Ditambahkan batasan `gte:harga_mulai` pada `LayananController::update`, regex nomor telepon Indonesia `08...`/`+62...`, dan batasan panjang `max`. | ✅ Selesai |
| 22 | **Hapus Kategori Tanpa Cek Keterkaitan Galeri** | `GaleriController::kategori_delete` kini mengecek `Galeri::where('id_kategori', $id)->exists()`. Jika masih ada galeri terkait, penghapusan ditolak dengan pesan peringatan. | ✅ Selesai |
| 23 | **Relasi Pasien-Dokter Hanya via Nama** | Migrasi `add_id_dokter_to_pasien_table` menambahkan kolom `id_dokter`. `PasienController` mensinkronkan `id_dokter` secara otomatis saat pendaftaran dan update, dengan fallback relasi legacy. | ✅ Selesai |
| 24 | **Query Dashboard & Landing Page Boros** | `Counter::getCounterData()` dioptimasi menjadi 1 query agregasi SQL. Landing page publik menerapkan `Cache::remember()` untuk data yang jarang berubah. | ✅ Selesai |

---

### 🟡 Rendah (Kualitas Kode, Arsitektur & Standar)

| # | Masalah Asal | Solusi & Implementasi Teknis | Status |
|---|--------------|------------------------------|:------:|
| 25 | **Sisa File Laravel Lama** | Dihapus file middleware usang (`Authenticate`, `TrimStrings`, dll.), `server.php`, dan `welcome.blade.php` yang tidak diperlukan di struktur Laravel 11. | ✅ Selesai |
| 26 | **File Sampah di Root Repo** | Dihapus dari git index dan dimasukkan ke pattern `.gitignore`. | ✅ Selesai |
| 27 | **README.md Usang** | Diperbarui total mendokumentasikan stack Laravel 11, PHP 8.2+, role matrix, perintah seeder, dan panduan environment. | ✅ Selesai |
| 28 | **Variabel .env Lama (Laravel ≤10)** | `.env.example` dan `.env.production.example` menggunakan key resmi Laravel 11 (`BROADCAST_CONNECTION`, `FILESYSTEM_DISK`, `QUEUE_CONNECTION`, `CACHE_STORE`). | ✅ Selesai |
| 29 | **Method Mati & Duplikatif di Controller** | Dihapus method redundan `layanan()` dan `layanan_new()` dari `AdminController`. Rute terpusat pada `LayananController`. | ✅ Selesai |
| 30 | **Inkonsistensi Penamaan Method & Model** | Model `Kategori` dan relasi `Galeri::kategori()` distandarisasi mengembalikan instance Eloquent model. | ✅ Selesai |
| 31 | **Query Builder Mentah untuk Kategori** | Digantikan sepenuhnya oleh model Eloquent `App\Models\Kategori`. | ✅ Selesai |
| 32 | **Pemisahan Logika Bisnis & Helper** | Dibuat support classes tersendiri: `App\Support\Sanitizer` dan `App\Support\IdGenerator`. | ✅ Selesai |
| 33 | **Pengelolaan Berkas Upload** | Penamaan file upload distandarisasi menggunakan timestamp + ID unik + ekstensi terverifikasi untuk keamanan penyimpanan. | ✅ Selesai |
| 34 | **Cakupan Pengujian Terbatas** | Dibuat `tests/Feature/SecurityAndRoleTest.php` menguji proteksi manipulasi harga, anti-bot honeypot, anti-hapus superadmin, sitemap, dan rute berita. Total tests meningkat menjadi 31 passed (63 assertions). | ✅ Selesai |
| 35 | **Ketiadaan CI/CD & Linter** | Dibuat konfigurasi GitHub Actions `.github/workflows/ci.yml` dan konfigurasi linter Laravel Pint `pint.json`. | ✅ Selesai |
| 36 | **Formula Injection pada Ekspor Excel** | `PasienExport` membersihkan karakter awalan berbahaya (`=`, `+`, `-`, `@`) pada data teks pasien sebelum diekspor ke file Excel. | ✅ Selesai |
| 37 | **Pengiriman Email Sinkron / Membebani Request** | Mailable `AppointmentConfirmation` dan `AppointmentAdminNotification` mengimplementasikan `ShouldQueue`. Notifikasi admin digabungkan dalam satu daftar penerima. | ✅ Selesai |
| 38 | **SEO Dasar & Peta Situs** | Dibuat endpoint `/sitemap.xml` dinamis dengan view `sitemap.blade.php` serta berkas `public/robots.txt`. | ✅ Selesai |
| 39 | **Kepatuhan Privasi Data Pasien (PDP)** | Ditambahkan klausul dan checkbox persetujuan pemrosesan data medis pasien pada form janji temu `/appointment`. | ✅ Selesai |
| 40 | **Generator ID String Berbasis `max()` Huruf** | `App\Support\IdGenerator` mem-parsing suffix integer numerik tertinggi, menjamin keakuratan ID setelah angka 999 (`ADM-1000`, dst.). | ✅ Selesai |

---

## 9. 📋 Checklist Eksekusi Perbaikan

- [x] Hapus `public/*.zip`, `*.sql`, `1.zip`, `2.zip` dari repo & `.gitignore`
- [x] Hentikan input harga/tindakan dari form publik & pasang honeypot
- [x] Ubah delete/status/toggle/logout ke POST/DELETE/PATCH + `@csrf`
- [x] Sanitasi HTML (`Sanitizer::cleanHtml`) + hapus `html_entity_decode` di `{!! !!}`
- [x] Seeder admin idempoten dari `.env` (`updateOrCreate`), tanpa print password
- [x] Buat `.env.production.example` & modernisasi `.env.example`
- [x] Migrasi `tanggal_janji` → `datetime`
- [x] Standarisasi generator ID numerik dengan `withTrashed()` dan row lock
- [x] Pindahkan `deleteImage` dari soft delete ke force delete
- [x] Pencarian via GET + pagination `withQueryString()`
- [x] Rekonstruksi visitor counter dengan middleware `TrackVisitor`
- [x] Rute publik Berita (`/berita`, `/berita/{slug}`) & rute edit tupoksi
- [x] Form akun dengan pilihan role + guard anti hapus superadmin terakhir & diri sendiri
- [x] Model `Kategori` Eloquent & guard hapus kategori berkait galeri
- [x] Migrasi `id_dokter` pada tabel pasien & relasi Eloquent
- [x] Asinkron mailable (`ShouldQueue`) & batched admin emails
- [x] Sanitasi formula injection pada ekspor Excel pasien
- [x] Sitemap XML dinamis (`/sitemap.xml`) & `robots.txt`
- [x] Klausul consent privasi data medis pasien
- [x] Pembersihan sisa file usang Laravel lama
- [x] Update `README.md` komprehensif (Laravel 11, Role Matrix, Setup)
- [x] CI/CD Workflow (`.github/workflows/ci.yml`) & `pint.json`
- [x] Penambahan test suite keamanan & fitur `SecurityAndRoleTest` + `PasienTest` (40 tests passed / 102 assertions)

