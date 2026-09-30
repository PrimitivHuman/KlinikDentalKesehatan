<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Pasien;

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
}
