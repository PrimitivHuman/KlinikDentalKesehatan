# 🦷 KlinikDentalKesehatan

Aplikasi web manajemen klinik gigi berbasis **Laravel 8**, mencakup sistem reservasi janji temu pasien, manajemen dokter, galeri foto, agenda kegiatan, dan panel admin lengkap.

---

## ✨ Fitur Utama

- **Halaman publik** — Landing page klinik (informasi, dokter, galeri)
- **Reservasi online** — Form janji temu pasien dengan email konfirmasi otomatis
- **Dashboard admin** — Statistik ringkas + grafik kunjungan mingguan
- **Manajemen pasien** — CRUD, status tracking, export Excel, invoice
- **Manajemen dokter** — CRUD + upload foto
- **Galeri foto** — Dengan kategori, upload, soft delete
- **Agenda kegiatan** — CRUD agenda klinik
- **Informasi umum** — Edit visi, misi, deskripsi, foto sampul
- **Recycle Bin** — Restore & force delete data terhapus
- **Multi-role** — `superadmin` (kelola semua) & `operator` (akses terbatas)

---

## 🛠️ Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| Backend | Laravel 8.x |
| PHP | ^7.3 / ^8.0 |
| Database | MySQL |
| Auth | Laravel Session Auth + Sanctum |
| Export | Maatwebsite/Excel 3.1 |
| Alert | realrashid/sweet-alert |
| Build Tool | Laravel Mix (Webpack) |

---

## 🚀 Instalasi & Setup

### 1. Clone Repository

```bash
git clone <url-repository>
cd KlinikDentalKesehatan
```

### 2. Install Dependensi PHP

```bash
composer install
```

### 3. Install Dependensi Node.js

```bash
npm install
npm run dev
```

### 4. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit file `.env` dan sesuaikan konfigurasi berikut:

```env
APP_NAME="Klinik Dental Kesehatan"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=klinikdental
DB_USERNAME=root
DB_PASSWORD=

# Konfigurasi Email (untuk konfirmasi janji temu)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@klinikdental.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 5. Jalankan Migrasi Database

```bash
php artisan migrate
```

### 6. (Opsional) Jalankan Seeder

```bash
php artisan db:seed
```

### 7. Buat Symbolic Link Storage

```bash
php artisan storage:link
```

### 8. Jalankan Aplikasi

```bash
php artisan serve
```

Akses di browser: `http://localhost:8000`

---

## 👥 Akun & Role

| Role | Akses |
|------|-------|
| `superadmin` | Akses penuh semua fitur termasuk manajemen akun pengguna |
| `operator` | Akses semua fitur kecuali manajemen akun pengguna |

---

## 📂 Struktur Direktori Penting

```
app/
├── Http/Controllers/   # Controller untuk setiap fitur
├── Http/Middleware/    # Termasuk RoleMiddleware
├── Models/             # Model Eloquent (Pasien, Dokter, Galeri, dll)
├── Exports/            # PasienExport untuk Excel
└── Mail/               # AppointmentConfirmation email

database/
└── migrations/         # Semua file migrasi database

resources/views/
├── admin/              # View panel admin (25+ halaman)
├── main/               # View halaman publik
└── emails/             # Template email

routes/
└── web.php             # Definisi semua route
```

---

## 🧪 Menjalankan Tests

```bash
php artisan test
```

Atau untuk test spesifik:

```bash
php artisan test --filter AuthTest
php artisan test --filter PasienTest
```

---

## 📋 Format ID

| Entitas | Format | Contoh |
|---------|--------|--------|
| Akun Admin | AK-XXX | AK-001 |
| Dokter | DOK-XXX | DOK-001 |
| Galeri | GL-XXX | GL-001 |
| Kategori | KT-XXX | KT-001 |
| Kegiatan | KGT-XXX | KGT-001 |
| Pasien | PSN-XXX (display) | PSN-001 |

---

## 🔐 Keamanan

- CSRF Protection aktif di semua form
- Rate limiting login (max 5 percobaan/menit)
- ID sensitif dienkripsi di URL
- Password di-hash dengan Bcrypt
- Session ID diregenerasi setelah login
- Role-based access control (superadmin / operator)

---

## 📝 Lisensi

MIT License
