# 🦷 FAM Dental Care (KlinikDentalKesehatan)

Aplikasi web manajemen klinik gigi modern berbasis **Laravel 11**, dirancang untuk klinik **FAM Dental Care**. Aplikasi mencakup sistem reservasi janji temu pasien publik, manajemen dokter, katalog layanan & tarif, artikel edukasi/berita gigi, galeri foto terorganisir, agenda kegiatan klinik, peta situs (sitemap SEO), hingga panel admin terproteksi multi-role.

---

## ✨ Fitur Utama

- **Halaman Publik Modern** — Landing page profil klinik, dokter spesialis, galeri tindakan, artikel edukasi gigi, dan rincian tarif layanan.
- **Reservasi Online Aman**
  - Form janji temu pasien dengan validasi waktu (`after_or_equal:today`), deteksi bentrok jadwal dokter, dan format nomor telepon Indonesia (`08...`/`+62...`).
  - Proteksi manipulasi harga: penetapan tindakan medis & tarif hanya dapat ditentukan oleh admin melalui panel internal.
  - Honeypot anti-spam tersembunyi untuk menangkal bot tanpa mengganggu pengalaman pengguna.
  - Checkbox persetujuan pemrosesan data (kepatuhan UU PDP / regulasi privasi medis).
  - Notifikasi email otomatis dan asinkron (`ShouldQueue`) untuk konfirmasi pasien dan notifikasi ringkas ke tim admin.
- **Dashboard & Analitik**
  - Grafik tren kunjungan mingguan otomatis dengan middleware `TrackVisitor` yang efisien (1 query agregasi).
  - Ringkasan total pasien, dokter, artikel berita, dan tindakan.
- **Manajemen Pasien & Rekam Medis**
  - CRUD pasien, pembaruan status (`pending`, `confirmed`, `completed`, `cancelled`).
  - Pencarian fleksibel berbasis GET dengan pagination persisten (`withQueryString`).
  - Cetak invoice perawatan resmi.
  - Export data pasien ke Excel (.xlsx) dengan sanitasi formula injection.
- **Katalog Layanan & Tarif** — Pengelolaan daftar perawatan, estimasi biaya (`harga_mulai` s/d `harga_sampai`), dan toggle status aktif.
- **Artikel & Edukasi Kesehatan Gigi** — Publikasi artikel gigi dengan penanganan slug unik, status draft/published, dan rendering rich-text yang telah disanitasi.
- **Manajemen Dokter & Jadwal** — Profil dokter, STR, SIP, jadwal praktik, foto resmi, dan relasi langsung ke data pendaftaran pasien.
- **Galeri Foto & Kategori** — Portofolio klinik dengan proteksi integritas: kategori tidak dapat dihapus jika masih menaungi foto aktif.
- **Recycle Bin (Trash)** — Sistem Soft Delete menyeluruh (Pasien, Dokter, Galeri, Kegiatan, Berita, Pengguna). File foto fisik tetap tersimpan aman di disk selama masa soft delete dan hanya dimusnahkan secara permanen pada saat force delete.
- **Keamanan & Multi-Role**
  - Role berjenjang: `superadmin` (kontrol penuh), `admin` (operasional penuh minus akun), dan `operator` (staf pendaftaran/pelayanan).
  - Proteksi akun: pencegahan penghapusan akun sendiri dan pemblokiran penghapusan akun superadmin terakhir.
  - URL ID terenkripsi dengan penanganan `DecryptException` ramah pengguna (tanpa error 500 mentah).
  - Sanitasi HTML tingkat parser (DOMDocument allowlist) untuk menangkal Stored XSS pada rich-text.
  - Rate limiting login (5 percobaan per menit) dan regenerasi session otomatis.
- **SEO & Peta Situs** — Route otomatis `/sitemap.xml` dinamis dan `robots.txt` standar produksi.

---

## 🛠️ Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| Backend Framework | Laravel 11.x |
| PHP Version | ^8.2 / ^8.3 |
| Database | MySQL / MariaDB (dukungan SQLite untuk testing in-memory) |
| Frontend | Bootstrap 5, Vanilla CSS, FontAwesome, Blade Templating |
| Authentication | Laravel Session Auth + Custom Role Middleware |
| Excel Export | Maatwebsite/Excel 3.1 |
| Alert System | realrashid/sweet-alert |
| CI / Automation | GitHub Actions (`.github/workflows/ci.yml`) |
| Code Formatter | Laravel Pint (`pint.json`) |

---

## 🚀 Instalasi & Setup Lokal

### 1. Clone Repository
```bash
git clone <url-repository>
cd KlinikDentalKesehatan
```

### 2. Install Dependensi PHP & Frontend
```bash
composer install
npm install && npm run build
```

### 3. Konfigurasi Environment
Salin template konfigurasi:
```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan variabel utama pada `.env`:
```env
APP_NAME="FAM Dental Care"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=klinikfamdentalcare
DB_USERNAME=root
DB_PASSWORD=

# Kredensial Superadmin Awal untuk Seeder
ADMIN_EMAIL=admin@klinikfamdentalcare.com
ADMIN_PASSWORD=UbahPasswordIni123!

# Antrean & Mail
QUEUE_CONNECTION=database
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@klinikfamdentalcare.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 4. Migrasi & Seeder Database
Jalankan migrasi dan isi data awal:
```bash
php artisan migrate --seed
```
> **Catatan Seeder:** `AdminSeeder` bersifat *idempoten* (`updateOrCreate`) dan membaca kredensial dari file `.env`, sehingga aman dijalankan berulang tanpa menghapus akun yang sudah ada.

### 5. Buat Storage Link & Jalankan Worker
```bash
php artisan storage:link
php artisan queue:work
```

### 6. Jalankan Server Lokal
```bash
php artisan serve
```
Aplikasi dapat diakses di browser melalui `http://localhost:8000`.

---

## 👥 Matriks Hak Akses (Role Matrix)

| Modul / Aksi | Superadmin | Admin | Operator |
|--------------|:----------:|:-----:|:--------:|
| Dashboard & Statistik | ✅ | ✅ | ✅ |
| Manajemen Pasien & Status | ✅ | ✅ | ✅ |
| Cetak Invoice Pasien | ✅ | ✅ | ✅ |
| Ekspor Excel Pasien | ✅ | ✅ | ❌ |
| Dokter, Jadwal, & Foto | ✅ | ✅ | ❌ |
| Katalog Layanan & Tarif | ✅ | ✅ | ❌ |
| Galeri & Kategori | ✅ | ✅ | ❌ |
| Berita & Edukasi Gigi | ✅ | ✅ | ❌ |
| Informasi Umum & Tupoksi | ✅ | ✅ | ❌ |
| Akses Trash (Recycle Bin) | ✅ | ✅ | ❌ |
| Manajemen Akun Pengguna (`/admin-area/akun`) | ✅ | ❌ | ❌ |

---

## 🧪 Pengujian Otomatis (Automated Testing)

Jalankan seluruh rangkaian pengujian fitur dan unit:
```bash
php artisan test
```

Menjalankan pengujian spesifik keamanan dan otorisasi:
```bash
php artisan test tests/Feature/SecurityAndRoleTest.php
php artisan test tests/Feature/AuthTest.php
php artisan test tests/Feature/PasienTest.php
```

---

## 📋 Standar Format ID

| Entitas | Prefix | Generator ID | Contoh |
|---------|:------:|--------------|--------|
| Akun Admin / User | `ADM` | `IdGenerator::generate(User::class, 'ADM')` | `ADM-001` |
| Dokter | `DOK` | `IdGenerator::generate(Dokter::class, 'DOK')` | `DOK-001` |
| Galeri | `GLR` | `IdGenerator::generate(Galeri::class, 'GLR')` | `GLR-001` |
| Kategori Galeri | `KTG` | `IdGenerator::generate(Kategori::class, 'KTG')` | `KTG-001` |
| Kegiatan | `KGT` | `IdGenerator::generate(Kegiatan::class, 'KGT')` | `KGT-001` |
| Berita | `BRT` | `IdGenerator::generate(Berita::class, 'BRT')` | `BRT-001` |
| Pasien | `PSN` | Accessor Auto-pad `id_pasien` | `PSN-001` |

---

## 🛡️ Kebijakan Keamanan & Hardening

1. **CSRF Enforcement:** Seluruh aksi mutasi dan penghapusan data wajib menggunakan method `POST` / `DELETE` / `PATCH` dengan token `@csrf`.
2. **XSS Protection:** Penggunaan `{{ }}` untuk escaping judul/nama secara default. Field rich-text diproses melalui allowlist HTML tag (`<p>`, `<b>`, `<i>`, `<ul>`, `<ol>`, `<li>`, `<a>`, `<br>`) sebelum disimpan.
3. **Penyimpanan Berkas:** Ekstensi file foto divalidasi dan diubah menjadi nama acak timestamp unik untuk mencegah path traversal dan script execution.
4. **Proteksi Formula Injection:** Seluruh nilai bertipe string pada ekspor spreadsheet disaring dari karakter awalan berbahaya (`=`, `+`, `-`, `@`).

---

## 📝 Lisensi
Proyek ini dilisensikan di bawah lisensi [MIT](LICENSE).
