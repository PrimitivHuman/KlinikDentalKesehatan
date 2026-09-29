<?php

namespace Tests\Feature;

use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * Halaman login dapat diakses dengan status HTTP 200.
     */
    public function test_login_page_can_be_rendered()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /**
     * User dengan kredensial salah tidak dapat login.
     */
    public function test_user_cannot_login_with_invalid_password()
    {
        $response = $this->post('/login', [
            'email'    => 'nonexistent@example.com',
            'password' => 'wrong_password',
        ]);

        $response->assertSessionHas('message');
        $this->assertGuest();
    }

    /**
     * Rate limiting: setelah 5 percobaan login gagal, request ke-6 harus diblokir (429 Too Many Requests).
     */
    public function test_login_rate_limiting_blocks_after_5_failed_attempts()
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email'    => 'wronguser@example.com',
                'password' => 'wrongpass',
            ]);
        }

        // Request ke-6
        $response = $this->post('/login', [
            'email'    => 'wronguser@example.com',
            'password' => 'wrongpass',
        ]);

        $response->assertStatus(429);
    }
}
