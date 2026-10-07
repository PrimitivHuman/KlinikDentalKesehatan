# Panduan Cloudflare Tunnel (Online Showcase / Demo ke Klien)

Dokumen ini berisi panduan cara membagikan proyek **Klinik FAM Dental Care** ke internet secara instan tanpa perlu sewa domain atau konfigurasi IP publik/port forwarding, menggunakan **Cloudflare Tunnel (`cloudflared`)**.

---

## 1. Persiapan yang Sudah Dikonfigurasi di Project

Sistem aplikasi Laravel telah disiapkan secara khusus untuk mendukung tunneling publik dengan aman:
1. **Reverse Proxy Trust (`bootstrap/app.php`):**
   - Mengaktifkan `$middleware->trustProxies(at: '*')` sehingga Laravel mengenali header `X-Forwarded-Proto`, `X-Forwarded-Host`, dan IP klien dari Cloudflare.
2. **Otomatis Force HTTPS (`AppServiceProvider.php`):**
   - Mendeteksi akses via tunnel `*.trycloudflare.com` dan otomatis memaksa skema `https://` untuk seluruh asset (`CSS`, `JS`, `Gambar`, `Font`), form submit, dan redirect.
   - **Hasil:** Tidak akan ada kendala *Mixed Content Warning* / tampilan rusak di browser klien.

---

## 2. Cara Menjalankan Tunnel (Paling Praktis: 1 Klik)

Aplikasi sudah memiliki skrip otomatis di root folder proyek:

### Cara Utama: Menggunakan Laragon (Rekomendasi)
1. Pastikan **Laragon** sudah dalam keadaan **Start All** (Apache dan MySQL aktif).
2. Di folder proyek, klik dua kali file:
   ```text
   tunnel.bat
   ```
   *Atau jalankan via Terminal:*
   ```bash
   .\tunnel.bat
   ```
3. Tunggu 3–5 detik hingga muncul kotak informasi seperti ini:
   ```text
   +--------------------------------------------------------------------------------------------+
   |  Your quick Tunnel has been created! Visit it at (it may take some time to be reachable):  |
   |  https://xxxx-xxxx-xxxx.trycloudflare.com                                                  |
   +--------------------------------------------------------------------------------------------+
   ```
4. **Salin tautan `https://....trycloudflare.com`** tersebut dan kirimkan ke klien atau teman Anda!
5. Klien bisa langsung membuka website publik, mencoba reservasi janji temu pasien, hingga membuka halaman admin `/login`.

---

## 3. Cara Menghentikan Tunnel
- Untuk menutup akses dari luar, cukup tekan **`Ctrl + C`** di jendela CMD yang menjalankan `tunnel.bat`.
- Setiap kali Anda menjalankan ulang `tunnel.bat`, Cloudflare akan memberikan URL baru yang aman dan terenkripsi HTTPS secara gratis.

---

## 4. Tips Saat Demo ke Klien
- **Kredensial Admin untuk Klien:**
  - Email: `admin@klinikfamdentalcare.com`
  - URL Login: `https://[link-tunnel].trycloudflare.com/login`
- **WhatsApp Notifikasi:**
  - Karena WhatsApp link menggunakan `wa.me/62...`, tombol WhatsApp di data pasien akan langsung membuka chat WhatsApp ke nomor pasien.
