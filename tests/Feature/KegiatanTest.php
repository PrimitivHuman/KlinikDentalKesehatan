<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Kegiatan;

class KegiatanTest extends TestCase
{
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
}
