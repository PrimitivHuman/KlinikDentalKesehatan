<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Hapus akun lama jika sudah ada (agar tidak duplikat)
        User::where('email', 'admin@klinikfamdentalcare.com')->delete();

        User::create([
            'id'           => 'AK-1',
            'name'         => 'Administrator',
            'email'        => 'admin@klinikfamdentalcare.com',
            'password'     => Hash::make('Admin@12345'),
            'profile_pict' => '',
        ]);

        $this->command->info('✅ Akun Admin berhasil dibuat!');
        $this->command->info('   Email    : admin@klinikfamdentalcare.com');
        $this->command->info('   Password : Admin@12345');
    }
}
