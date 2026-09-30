<?php

namespace Database\Factories;

use App\Models\Pasien;
use Illuminate\Database\Eloquent\Factories\Factory;

class PasienFactory extends Factory
{
    protected $model = Pasien::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_pasien'        => $this->faker->name(),
            'tanggal_janji'      => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'email_pasien'       => $this->faker->safeEmail(),
            'no_hp_pasien'       => $this->faker->phoneNumber(),
            'alamat_pasien'      => $this->faker->address(),
            'keluhan_pasien'     => $this->faker->sentence(),
            'total_harga_pasien' => null,
            'tindakan_pasien'    => null,
            'template'           => null,
            'status'             => 'pending',
            'dokter_pilihan'     => null,
        ];
    }
}
