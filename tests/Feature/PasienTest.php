<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Pasien;
use App\Models\Dokter;

/**
 * K13 Fix: Feature tests untuk manajemen janji temu pasien.
 */
class PasienTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test halaman form appointment bisa diakses publik.
     */
    public function test_halaman_appointment_dapat_diakses(): void
    {
        $response = $this->get('/appointment');
        $response->assertStatus(200);
        $response->assertDontSee('class="nav-link scrollto active"', false);
    }

    /**
     * Test pengiriman form appointment dengan data lengkap berhasil.
     */
    public function test_submit_appointment_berhasil(): void
    {
        $response = $this->post('/appointment', [
            'nama_pasien'    => 'Budi Santoso',
            'tanggal_janji'  => now()->addDays(3)->format('Y-m-d'),
            'email_pasien'   => 'budi@example.com',
            'no_hp_pasien'   => '081234567890',
            'alamat_pasien'  => 'Jl. Merdeka No. 1 Jakarta',
            'keluhan_pasien' => 'Sakit gigi bagian belakang kanan',
        ]);

        $response->assertRedirect('/appointment');
        $response->assertSessionHas('sent-message');
        $this->assertDatabaseHas('pasien', [
            'nama_pasien'  => 'Budi Santoso',
            'email_pasien' => 'budi@example.com',
            'status'       => 'pending',
        ]);
    }

    /**
     * Test validasi form appointment — nama wajib diisi.
     */
    public function test_submit_appointment_gagal_tanpa_nama(): void
    {
        $response = $this->post('/appointment', [
            'nama_pasien'    => '',
            'tanggal_janji'  => now()->addDays(3)->format('Y-m-d'),
            'email_pasien'   => 'budi@example.com',
            'no_hp_pasien'   => '081234567890',
            'alamat_pasien'  => 'Jl. Merdeka No. 1',
            'keluhan_pasien' => 'Gigi sakit',
        ]);

        $response->assertSessionHasErrors('nama_pasien');
    }

    /**
     * Test admin bisa update status pasien menjadi 'confirmed'.
     */
    public function test_admin_bisa_update_status_pasien(): void
    {
        $admin  = User::factory()->create(['id' => 'AK-001', 'role' => 'admin']);
        $pasien = Pasien::factory()->create(['status' => 'pending']);

        $encryptedId = encrypt($pasien->id_pasien);

        $response = $this->actingAs($admin)
            ->get("/admin-area/pasien/status/{$encryptedId}/confirmed");

        $response->assertRedirect();
        $this->assertDatabaseHas('pasien', [
            'id_pasien' => $pasien->id_pasien,
            'status'    => 'confirmed',
        ]);
    }

    /**
     * Test update status dengan status tidak valid ditolak.
     */
    public function test_status_tidak_valid_ditolak(): void
    {
        $admin  = User::factory()->create(['id' => 'AK-001', 'role' => 'admin']);
        $pasien = Pasien::factory()->create(['status' => 'pending']);

        $encryptedId = encrypt($pasien->id_pasien);

        $response = $this->actingAs($admin)
            ->get("/admin-area/pasien/status/{$encryptedId}/status_asal");

        $response->assertSessionHas('error');
    }

    /**
     * Test admin bisa mengakses form edit/rekam pasien.
     */
    public function test_admin_bisa_akses_halaman_edit_pasien(): void
    {
        $admin  = User::factory()->create(['id' => 'AK-001', 'role' => 'admin']);
        $pasien = Pasien::factory()->create(['nama_pasien' => 'Siti Aminah']);

        $encryptedId = encrypt($pasien->id_pasien);

        $response = $this->actingAs($admin)
            ->get("/admin-area/pasien/edit/{$encryptedId}");

        $response->assertStatus(200);
        $response->assertSee('Siti Aminah');
    }

    /**
     * Test admin bisa menyimpan pembaruan data rekam medis dan pembayaran pasien (no MassAssignmentException).
     */
    public function test_admin_bisa_update_rekam_dan_pembayaran_pasien(): void
    {
        $admin  = User::factory()->create(['id' => 'AK-001', 'role' => 'admin']);
        $dokter = Dokter::create([
            'id_dokter'    => 'DOK-001',
            'nama_dokter'  => 'drg. Jane Firsty',
            'no_hp_dokter' => '081234567890',
            'email_dokter' => 'jane@example.com',
            'str_dokter'   => 'STR-123456',
            'sip_dokter'   => 'SIP-123456',
        ]);
        $pasien = Pasien::factory()->create([
            'nama_pasien'   => 'Rio Dwianto',
            'tanggal_janji' => '2026-10-10 10:00:00',
        ]);

        $response = $this->actingAs($admin)
            ->post('/admin-area/pasien/edit/update', [
                'id_pasien'          => $pasien->id_pasien,
                'nama_pasien'        => 'Rio Dwianto Updated',
                'no_hp_pasien'       => '087830281137',
                'tanggal_janji'      => '2026-10-10 14:00:00',
                'email_pasien'       => 'riodwianto21@gmail.com',
                'alamat_pasien'      => 'Jl. Buah Batu Bandung',
                'keluhan_pasien'     => 'Karang pada gigi',
                'id_dokter'          => $dokter->id_dokter,
                'tindakan_pasien'    => 'Pembersihan Karang Gigi (Scaling)',
                'total_harga_pasien' => 250000,
            ]);

        $response->assertRedirect('/admin-area/pasien');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('pasien', [
            'id_pasien'          => $pasien->id_pasien,
            'nama_pasien'        => 'Rio Dwianto Updated',
            'id_dokter'          => $dokter->id_dokter,
            'dokter_pilihan'     => 'drg. Jane Firsty',
            'tindakan_pasien'    => 'Pembersihan Karang Gigi (Scaling)',
            'total_harga_pasien' => 250000,
        ]);
    }

    /**
     * Test pencegahan bentrok jadwal dokter dalam rentang waktu 30 menit (#4).
     */
    public function test_bentrok_jadwal_30_menit_ditolak(): void
    {
        $dokter = Dokter::create([
            'id_dokter'    => 'DOK-002',
            'nama_dokter'  => 'drg. Rian Wijaya',
            'no_hp_dokter' => '081234567891',
            'email_dokter' => 'rian@example.com',
            'str_dokter'   => 'STR-654321',
            'sip_dokter'   => 'SIP-654321',
        ]);

        // Pasien pertama pukul 10:00
        Pasien::factory()->create([
            'id_dokter'      => $dokter->id_dokter,
            'dokter_pilihan' => $dokter->nama_dokter,
            'tanggal_janji'  => '2026-10-15 10:00:00',
            'status'         => 'confirmed',
        ]);

        // Pasien kedua mencoba mendaftar pada pukul 10:15 (dalam selang 30 menit)
        $response = $this->post('/appointment', [
            'nama_pasien'    => 'Pasien Kedua',
            'tanggal_janji'  => '2026-10-15 10:15:00',
            'email_pasien'   => 'kedua@example.com',
            'no_hp_pasien'   => '081298765432',
            'alamat_pasien'  => 'Jl. Dago No. 10',
            'keluhan_pasien' => 'Konsultasi behel',
            'dokter_pilihan' => $dokter->nama_dokter,
        ]);

        $response->assertSessionHas('error');
    }

    /**
     * Test perubahan status pasien memicu pengiriman email notifikasi status (#3).
     */
    public function test_update_status_memicu_email_status_update(): void
    {
        \Illuminate\Support\Facades\Mail::fake();

        $admin  = User::factory()->create(['id' => 'AK-001', 'role' => 'superadmin']);
        $pasien = Pasien::factory()->create([
            'status'       => 'pending',
            'email_pasien' => 'pasien@example.com',
        ]);

        $encryptedId = encrypt($pasien->id_pasien);

        $response = $this->actingAs($admin)
            ->post("/admin-area/pasien/status/{$encryptedId}/confirmed");

        $response->assertRedirect();

        \Illuminate\Support\Facades\Mail::assertQueued(\App\Mail\AppointmentStatusUpdate::class, function ($mail) {
            return $mail->hasTo('pasien@example.com');
        });
    }

    /**
     * Test format rupiah pada total harga dikonversi dan disimpan sebagai integer (#5).
     */
    public function test_update_harga_format_rupiah_dikonversi_menjadi_integer(): void
    {
        $admin  = User::factory()->create(['id' => 'AK-001', 'role' => 'superadmin']);
        $pasien = Pasien::factory()->create();

        $response = $this->actingAs($admin)
            ->post('/admin-area/pasien/edit/update', [
                'id_pasien'          => $pasien->id_pasien,
                'nama_pasien'        => $pasien->nama_pasien,
                'no_hp_pasien'       => $pasien->no_hp_pasien,
                'tanggal_janji'      => $pasien->tanggal_janji,
                'email_pasien'       => $pasien->email_pasien,
                'alamat_pasien'      => $pasien->alamat_pasien,
                'keluhan_pasien'     => $pasien->keluhan_pasien,
                'total_harga_pasien' => 'Rp 350.000',
            ]);

        $response->assertRedirect('/admin-area/pasien');

        $this->assertDatabaseHas('pasien', [
            'id_pasien'          => $pasien->id_pasien,
            'total_harga_pasien' => 350000,
        ]);
    }

    /**
     * Test pencarian dan filter pasien berdasarkan kata kunci, status, dan rentang tanggal (#32).
     */
    public function test_filter_dan_pencarian_pasien(): void
    {
        $admin = User::factory()->create(['id' => 'AK-001', 'role' => 'superadmin']);

        Pasien::factory()->create(['nama_pasien' => 'Budi Santoso', 'status' => 'pending']);
        Pasien::factory()->create(['nama_pasien' => 'Dewi Lestari', 'status' => 'completed']);

        // Filter status 'completed'
        $response = $this->actingAs($admin)->get('/admin-area/pasien/search?status=completed');
        $response->assertStatus(200);
        $response->assertSee('Dewi Lestari');
        $response->assertDontSee('Budi Santoso');

        // Pencarian keyword 'Budi'
        $responseKeyword = $this->actingAs($admin)->get('/admin-area/pasien/search?cari=Budi');
        $responseKeyword->assertStatus(200);
        $responseKeyword->assertSee('Budi Santoso');
        $responseKeyword->assertDontSee('Dewi Lestari');
    }

    /**
     * Test response HTTP menyertakan Security Headers (#27).
     */
    public function test_response_menyertakan_security_headers(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }
}

