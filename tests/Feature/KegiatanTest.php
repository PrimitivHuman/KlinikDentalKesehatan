<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Kegiatan;

class KegiatanTest extends TestCase
{
    use RefreshDatabase;
    /**
     * User yang sudah login dapat mengakses halaman agenda kegiatan.
     */
    public function test_authenticated_user_can_access_activity_page()
    {
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'id'           => 'AK-999',
                'name'         => 'Test Admin',
                'email'        => 'testadmin@example.com',
                'password'     => bcrypt('password'),
                'profile_pict' => 'default.png',
                'role'         => 'admin',
            ]);
        }

        $response = $this->actingAs($user)->get('/admin-area/kegiatan');
        $response->assertStatus(200);
    }

    /**
     * User guest (belum login) ditolak dan dialihkan ke login.
     */
    public function test_guest_cannot_access_activity_page()
    {
        $response = $this->get('/admin-area/kegiatan');
        $response->assertRedirect('/login');
    }

    /**
     * User yang sudah login dapat mengakses form edit kegiatan.
     */
    public function test_authenticated_user_can_access_activity_edit_page()
    {
        $user = User::create([
            'id'           => 'AK-998',
            'name'         => 'Test Admin 2',
            'email'        => 'testadmin2@example.com',
            'password'     => bcrypt('password'),
            'profile_pict' => 'default.png',
            'role'         => 'admin',
        ]);

        $kegiatan = Kegiatan::create([
            'id_kegiatan'        => 'ACT-001',
            'judul_kegiatan'     => 'Penyuluhan Gigi Sekolah Dasar',
            'deskripsi_kegiatan' => 'Kegiatan bakti sosial pemeriksaan gigi anak.',
            'tgl_kegiatan'       => now()->format('Y-m-d'),
            'images'             => 'act.jpg',
        ]);

        $encryptedId = encrypt($kegiatan->id_kegiatan);
        $response = $this->actingAs($user)->get("/admin-area/kegiatan/edit/{$encryptedId}");
        $response->assertStatus(200);
        $response->assertSee('Penyuluhan Gigi Sekolah Dasar');
    }
}
