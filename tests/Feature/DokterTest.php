<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;
use App\Models\User;
use App\Models\Dokter;

class DokterTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'id'           => 'AK-999',
            'name'         => 'Test Admin',
            'email'        => 'testadmin@example.com',
            'password'     => bcrypt('password'),
            'profile_pict' => 'default.png',
            'role'         => 'admin',
        ]);
    }

    public function test_authenticated_user_can_access_dokter_page_with_data()
    {
        Dokter::create([
            'id_dokter'     => 'DOK-001',
            'nama_dokter'   => 'drg. Sarah Jenkins',
            'no_hp_dokter'  => '081234567890',
            'email_dokter'  => 'sarah@example.com',
            'jadwal_dokter' => 'Senin - Jumat 09:00 - 15:00',
            'str_dokter'    => 'STR-12345678',
            'sip_dokter'    => 'SIP-87654321',
            'images'        => 'default.jpg',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin-area/dokter');
        $response->assertStatus(200);
        $response->assertSee('drg. Sarah Jenkins');
        $response->assertSee('STR-12345678');
        $response->assertSee('SIP-87654321');
    }

    public function test_authenticated_user_can_access_dokter_new_page()
    {
        $response = $this->actingAs($this->admin)->get('/admin-area/dokter/new');
        $response->assertStatus(200);
        $response->assertSee('Tambah Dokter Baru');
    }

    public function test_authenticated_user_can_access_dokter_edit_page()
    {
        $dokter = Dokter::create([
            'id_dokter'     => 'DOK-002',
            'nama_dokter'   => 'drg. Budi Pratama',
            'no_hp_dokter'  => '081234567891',
            'email_dokter'  => 'budi@example.com',
            'jadwal_dokter' => 'Selasa - Sabtu 10:00 - 16:00',
            'str_dokter'    => 'STR-88889999',
            'sip_dokter'    => 'SIP-11112222',
            'images'        => 'default.jpg',
        ]);

        $encryptedId = Crypt::encrypt($dokter->id_dokter);
        $response = $this->actingAs($this->admin)->get("/admin-area/dokter/edit/{$encryptedId}");
        $response->assertStatus(200);
        $response->assertSee('drg. Budi Pratama');
        $response->assertSee('STR-88889999');
    }
}
