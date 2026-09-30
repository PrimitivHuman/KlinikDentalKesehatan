<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Pasien;

/**
 * K13 Fix: Feature tests untuk fitur-fitur kritis aplikasi klinik.
 * Meliputi: autentikasi, pendaftaran pasien, dan export data.
 */
class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test halaman login bisa diakses publik.
     */
    public function test_halaman_login_dapat_diakses(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /**
     * Test login dengan kredensial yang salah.
     */
    public function test_login_gagal_dengan_kredensial_salah(): void
    {
        $response = $this->post('/login', [
            'email'    => 'salah@email.com',
            'password' => 'passwordsalah',
        ]);

        $response->assertSessionHas('message');
        $response->assertRedirect();
    }

    /**
     * Test admin yang sudah login bisa akses dashboard.
     */
    public function test_admin_login_dapat_akses_dashboard(): void
    {
        $admin = User::factory()->create([
            'id'    => 'AK-001',
            'email' => 'admin@klinik.com',
            'role'  => 'superadmin',
        ]);

        $response = $this->actingAs($admin)->get('/admin-area');
        $response->assertStatus(200);
    }

    /**
     * Test user yang belum login diredirect ke halaman login.
     */
    public function test_guest_diredirect_ke_login(): void
    {
        $response = $this->get('/admin-area');
        $response->assertRedirect('/login');
    }

    /**
     * Test operator tidak bisa akses manajemen akun (hanya superadmin).
     */
    public function test_operator_tidak_bisa_akses_manajemen_akun(): void
    {
        $operator = User::factory()->create([
            'id'   => 'AK-002',
            'role' => 'operator',
        ]);

        $response = $this->actingAs($operator)->get('/admin-area/akun');
        $response->assertRedirect('/admin-area');
    }

    /**
     * Test superadmin bisa akses manajemen akun.
     */
    public function test_superadmin_bisa_akses_manajemen_akun(): void
    {
        $superadmin = User::factory()->create([
            'id'   => 'AK-001',
            'role' => 'superadmin',
        ]);

        $response = $this->actingAs($superadmin)->get('/admin-area/akun');
        $response->assertStatus(200);
    }
}
