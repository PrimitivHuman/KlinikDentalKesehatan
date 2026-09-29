<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class TrashTest extends TestCase
{
    /**
     * User yang sudah login dapat mengakses halaman Recycle Bin (trash).
     */
    public function test_authenticated_user_can_access_trash_page()
    {
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'id'           => 'AK-998',
                'name'         => 'Test Admin 2',
                'email'        => 'testadmin2@example.com',
                'password'     => bcrypt('password'),
                'profile_pict' => 'default.png',
            ]);
        }

        $response = $this->actingAs($user)->get('/admin-area/trash');
        $response->assertStatus(200);
    }
}
