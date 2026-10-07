<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Kategori;
use App\Models\Galeri;
use App\Models\Berita;
use App\Models\Kegiatan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SecurityAndRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_appointment_ignores_price_and_action_fields(): void
    {
        $payload = [
            'nama_pasien'        => 'Budi Santoso',
            'tanggal_janji'      => now()->addDays(2)->format('Y-m-d H:i:s'),
            'email_pasien'       => 'budi@example.com',
            'no_hp_pasien'       => '081234567890',
            'alamat_pasien'      => 'Jl. Sudirman No. 10',
            'keluhan_pasien'     => 'Sakit gigi geraham',
            'total_harga_pasien' => '5000000', // Mencoba manipulasi harga
            'tindakan_pasien'    => 'Cabut Gigi Palsu',
            'persetujuan'        => '1',
        ];

        $response = $this->post('/appointment', $payload);
        $response->assertSessionHas('sent-message');

        $pasien = Pasien::where('email_pasien', 'budi@example.com')->first();
        $this->assertNotNull($pasien);
        $this->assertNull($pasien->total_harga_pasien);
        $this->assertNull($pasien->tindakan_pasien);
        $this->assertEquals('pending', $pasien->status);
    }

    public function test_public_appointment_with_selected_doctor_links_id_dokter(): void
    {
        $dokter = Dokter::create([
            'id_dokter'     => 'DOK-001',
            'nama_dokter'   => 'Drg. Rio Dwianto',
            'no_hp_dokter'  => '081234567899',
            'email_dokter'  => 'rio@example.com',
            'jadwal_dokter' => 'Senin - Jumat 09:00 - 15:00',
            'str_dokter'    => '12345678',
            'sip_dokter'    => 'SIP-12345',
            'images'        => 'dokter.jpg',
        ]);

        $payload = [
            'nama_pasien'    => 'Anisa Rahma',
            'tanggal_janji'  => now()->addDays(3)->format('Y-m-d H:i:s'),
            'email_pasien'   => 'anisa@example.com',
            'no_hp_pasien'   => '081298765432',
            'alamat_pasien'  => 'Jl. Merdeka No. 45',
            'keluhan_pasien' => 'Konsultasi kawat gigi',
            'dokter_pilihan' => 'Drg. Rio Dwianto',
            'persetujuan'    => '1',
        ];

        $response = $this->post('/appointment', $payload);
        $response->assertSessionHas('sent-message');

        $pasien = Pasien::where('email_pasien', 'anisa@example.com')->first();
        $this->assertNotNull($pasien);
        $this->assertEquals('Drg. Rio Dwianto', $pasien->dokter_pilihan);
        $this->assertEquals('DOK-001', $pasien->id_dokter);
        $this->assertNotNull($pasien->dokter);
        $this->assertEquals('Drg. Rio Dwianto', $pasien->dokter->nama_dokter);
    }

    public function test_appointment_honeypot_rejects_bots(): void
    {
        $payload = [
            'nama_pasien'   => 'Bot Spammer',
            'tanggal_janji' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'email_pasien'  => 'spammer@example.com',
            'no_hp_pasien'  => '081234567890',
            'alamat_pasien' => 'Spam City',
            'keluhan_pasien'=> 'Spamming',
            'website_hp'    => 'http://spamsite.com', // Honeypot diisi
            'persetujuan'   => '1',
        ];

        $response = $this->post('/appointment', $payload);
        $this->assertDatabaseMissing('pasien', ['email_pasien' => 'spammer@example.com']);
    }

    public function test_cannot_delete_own_superadmin_account(): void
    {
        $superadmin = User::create([
            'id'       => 'ADM-001',
            'name'     => 'Super Admin',
            'email'    => 'superadmin@example.com',
            'password' => bcrypt('password123'),
            'role'     => 'superadmin',
        ]);

        $this->actingAs($superadmin);

        $response = $this->delete('/admin-area/akun/delete/' . encrypt($superadmin->id) . '/account');
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $superadmin->id, 'deleted_at' => null]);
    }

    public function test_cannot_delete_last_superadmin_account(): void
    {
        $superadmin = User::create([
            'id'       => 'ADM-001',
            'name'     => 'Admin Satu',
            'email'    => 'admin1@example.com',
            'password' => bcrypt('password123'),
            'role'     => 'superadmin',
        ]);

        $secondAdmin = User::create([
            'id'       => 'ADM-002',
            'name'     => 'Admin Dua',
            'email'    => 'admin2@example.com',
            'password' => bcrypt('password123'),
            'role'     => 'superadmin',
        ]);

        $this->actingAs($secondAdmin);

        // Menghapus salah satu dari 2 superadmin berhasil
        $delResponse = $this->delete('/admin-area/akun/delete/' . encrypt($superadmin->id) . '/account');
        $delResponse->assertSessionHas('success');

        // Menghapus superadmin terakhir ditolak
        $lastDelResponse = $this->delete('/admin-area/akun/delete/' . encrypt($secondAdmin->id) . '/account');
        $lastDelResponse->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $secondAdmin->id, 'deleted_at' => null]);
    }

    public function test_public_berita_pages_are_accessible(): void
    {
        $berita = Berita::create([
            'id_berita'  => 'BRT-001',
            'judul'      => 'Tips Menjaga Kesehatan Gigi',
            'slug'       => 'tips-menjaga-kesehatan-gigi',
            'isi'        => 'Berikut adalah tips menjaga kesehatan gigi harian...',
            'penulis'    => 'Drg. FAM',
            'images'     => 'tips.jpg',
            'status'     => 'published',
            'tgl_terbit' => now()->toDateString(),
        ]);

        $responseList = $this->get('/berita');
        $responseList->assertStatus(200);
        $responseList->assertSee('Tips Menjaga Kesehatan Gigi');

        $responseDetail = $this->get('/berita/' . $berita->slug);
        $responseDetail->assertStatus(200);
        $responseDetail->assertSee('Tips Menjaga Kesehatan Gigi');
    }

    public function test_public_agenda_pages_are_accessible(): void
    {
        $kegiatan = Kegiatan::create([
            'id_kegiatan'        => 'KGT-001',
            'judul_kegiatan'     => 'Bakti Sosial Pemeriksaan Gigi',
            'deskripsi_kegiatan' => 'Kegiatan pemeriksaan gigi gratis untuk warga.',
            'tgl_kegiatan'       => now()->addDays(5)->toDateString(),
            'images'             => 'baksos.jpg',
        ]);

        $responseList = $this->get('/agenda');
        $responseList->assertStatus(200);
        $responseList->assertSee('Bakti Sosial Pemeriksaan Gigi');

        $responseDetail = $this->get('/agenda/' . $kegiatan->id_kegiatan);
        $responseDetail->assertStatus(200);
        $responseDetail->assertSee('Bakti Sosial Pemeriksaan Gigi');
    }

    public function test_home_page_renders_published_berita_and_kegiatan(): void
    {
        Berita::create([
            'id_berita'  => 'BRT-001',
            'judul'      => 'Artikel Landing Page',
            'slug'       => 'artikel-landing-page',
            'isi'        => 'Konten artikel...',
            'penulis'    => 'Admin',
            'status'     => 'published',
            'tgl_terbit' => now()->toDateString(),
        ]);

        Kegiatan::create([
            'id_kegiatan'        => 'KGT-001',
            'judul_kegiatan'     => 'Agenda Landing Page',
            'deskripsi_kegiatan' => 'Deskripsi agenda...',
            'tgl_kegiatan'       => now()->toDateString(),
            'images'             => 'agenda.jpg',
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Artikel Landing Page');
        $response->assertSee('Agenda Landing Page');
        $response->assertSee('/#berita');
        $response->assertSee('/#agenda');
    }

    public function test_sitemap_xml_renders_correctly(): void
    {
        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $this->assertStringContainsString('text/xml', (string) $response->headers->get('Content-Type'));
        $response->assertSee('<urlset', false);
        $response->assertSee(url('/appointment'), false);
    }

    public function test_cannot_delete_category_with_existing_gallery_items(): void
    {
        $superadmin = User::create([
            'id'       => 'ADM-001',
            'name'     => 'Super Admin',
            'email'    => 'super@example.com',
            'password' => bcrypt('password123'),
            'role'     => 'superadmin',
        ]);

        $kategori = Kategori::create([
            'id_kategori'   => 'KTG-001',
            'nama_kategori' => 'Scaling Gigi',
        ]);

        Galeri::create([
            'id_galeri'   => 'GLR-001',
            'judul'       => 'Proses Scaling',
            'images'      => 'scaling.jpg',
            'id_kategori' => 'KTG-001',
        ]);

        $this->actingAs($superadmin);

        $response = $this->delete('/admin-area/kategori-galeri/delete/' . encrypt($kategori->id_kategori));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('kategori', ['id_kategori' => 'KTG-001']);
    }
}
