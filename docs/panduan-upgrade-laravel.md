# 🚀 Panduan Upgrade Laravel 8 → Laravel 11

> **Status**: Panduan perencanaan — belum dieksekusi.  
> Laravel 8 sudah melewati batas *Security Support* (resmi berakhir Januari 2023). Upgrade disarankan dilakukan secara bertahap.

---

## Strategi: Upgrade Bertahap (8 → 9 → 10 → 11)

Jangan langsung lompat dari L8 ke L11. Setiap versi memiliki breaking changes yang harus diselesaikan satu per satu.

```
Laravel 8 → Laravel 9 → Laravel 10 → Laravel 11
```

---

## Langkah 1: Persiapan Sebelum Upgrade

### Buat branch baru di Git
```bash
git checkout -b upgrade/laravel-9
```

### Jalankan test suite dulu (pastikan baseline hijau)
```bash
php artisan test
```

### Cek kompatibilitas package saat ini
```bash
composer outdated
```

Package yang perlu dicek:
| Package | L8 | L9+ |
|---------|-----|-----|
| `maatwebsite/excel` | ^3.1 | Perlu ^3.1.48+ |
| `realrashid/sweet-alert` | ^6.0 | Kompatibel |
| `laravel/sanctum` | ^2.11 | Perlu ^3.0+ di L10 |

---

## Langkah 2: Upgrade ke Laravel 9

### 2.1 Update `composer.json`
```json
{
    "require": {
        "php": "^8.0",
        "laravel/framework": "^9.0",
        "laravel/sanctum": "^3.0"
    }
}
```

### 2.2 Jalankan upgrade
```bash
composer update laravel/framework --with-dependencies
php artisan migrate
```

### 2.3 Breaking changes utama L8 → L9
- `$dates` property di model → ganti ke `$casts`
- `lang/` directory dipindah dari `resources/lang/` ke `lang/`
- `Route::middleware` syntax tidak berubah ✅
- `Str::of()` beberapa method baru (non-breaking)

**Contoh fix `$dates` → `$casts`:**
```php
// Sebelum (L8)
protected $dates = ['deleted_at', 'tgl_terbit'];

// Sesudah (L9+)
protected $casts = [
    'deleted_at' => 'datetime',
    'tgl_terbit' => 'date',
];
```

---

## Langkah 3: Upgrade ke Laravel 10

### 3.1 Requirement PHP
Laravel 10 memerlukan **PHP 8.1+**. Pastikan server Anda sudah di PHP 8.1.

```bash
php --version  # Harus >= 8.1
```

### 3.2 Update `composer.json`
```json
{
    "require": {
        "php": "^8.1",
        "laravel/framework": "^10.0"
    }
}
```

### 3.3 Breaking changes utama L9 → L10
- Seluruh core Laravel sudah menggunakan **PHP native types** — method signature berubah
- `Str::password()` dan helper baru tersedia
- `artisan model:prune` tersedia untuk soft deletes cleanup
- `Http::fake()` improvements

**Hal yang perlu dicek di project ini:**
- Pastikan semua method Controller yang override dari base class menggunakan type-hint yang sesuai

---

## Langkah 4: Upgrade ke Laravel 11

### 4.1 Requirement PHP
Laravel 11 memerlukan **PHP 8.2+**.

### 4.2 Perubahan arsitektur besar di L11
Laravel 11 memperkenalkan **struktur aplikasi yang lebih ramping** ("slim skeleton"):

| Komponen | L8/9/10 | L11 |
|----------|---------|-----|
| `app/Http/Kernel.php` | Ada | **Dihapus** (diganti `bootstrap/app.php`) |
| Middleware default | Di Kernel | Otomatis di `bootstrap/app.php` |
| `routes/api.php` | Default ada | Opsional |
| `EventServiceProvider` | Wajib | Opsional |

**Registrasi Middleware di L11 (`bootstrap/app.php`):**
```php
return Application::configure(basePath: dirname(__DIR__))
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->create();
```

### 4.3 Hapus `app/Http/Kernel.php` yang sudah ada

---

## Checklist Setelah Setiap Upgrade

- [ ] `php artisan test` — semua test hijau
- [ ] `php artisan route:list` — semua route terdaftar
- [ ] `php artisan view:clear && php artisan config:clear` — cache dibersihkan
- [ ] Login admin berfungsi
- [ ] Form appointment berfungsi
- [ ] Upload gambar berfungsi
- [ ] Export Excel berfungsi

---

## Rekomendasi Tambahan (Bersamaan dengan Upgrade)

### Ganti ke Spatie Permission (R7 full)
```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

Lalu di `User` model:
```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
}
```

Dan di route:
```php
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    // ...
});
```

### Ganti ke Vite (dari Mix)
Laravel 10+ sudah menggunakan Vite secara default.
```bash
npm install --save-dev vite laravel-vite-plugin
```

---

## Estimasi Waktu

| Tahap | Estimasi |
|-------|----------|
| L8 → L9 | 2–4 jam |
| L9 → L10 | 1–2 jam |
| L10 → L11 | 3–6 jam (arsitektur berubah) |
| Testing menyeluruh | 2–4 jam |
| **Total** | **~1–2 hari kerja** |

---

> 💡 **Tips**: Selalu lakukan upgrade di branch terpisah, dan test di environment staging sebelum deploy ke production.
