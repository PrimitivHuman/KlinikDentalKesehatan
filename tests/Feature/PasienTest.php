<?php

namespace Tests\Feature;

use Tests\TestCase;

class PasienTest extends TestCase
{
    /**
     * Halaman appointment dapat diakses.
     */
    public function test_appointment_page_can_be_rendered()
    {
        $response = $this->get('/appointment');
        $response->assertStatus(200);
    }

    /**
     * Submit appointment tanpa mengisi field wajib akan memicu error validasi.
     */
    public function test_appointment_submission_requires_mandatory_fields()
    {
        $response = $this->post('/appointment', []);
        $response->assertSessionHasErrors(['nama_pasien', 'email_pasien', 'no_hp_pasien', 'tanggal_janji', 'alamat_pasien', 'keluhan_pasien']);
    }

    /**
     * Submit appointment dengan format email salah memicu error validasi.
     */
    public function test_appointment_submission_fails_with_invalid_email()
    {
        $response = $this->post('/appointment', [
            'nama_pasien'    => 'John Doe',
            'email_pasien'   => 'invalid-email-format',
            'no_hp_pasien'   => '081234567890',
            'tanggal_janji'  => '2026-10-01',
            'alamat_pasien'  => 'Jl. Testing No. 123',
            'keluhan_pasien' => 'Sakit gigi belakang',
        ]);

        $response->assertSessionHasErrors(['email_pasien']);
    }
}
