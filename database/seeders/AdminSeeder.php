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
        $adminEmail    = env('ADMIN_EMAIL', 'admin@klinikfamdentalcare.com');
        $adminPassword = env('ADMIN_PASSWORD', 'Admin@12345');
        $adminName     = env('ADMIN_NAME', 'Administrator');

        // Gunakan updateOrCreate agar data admin sebelumnya tidak terhapus
        $admin = User::firstOrNew(['email' => $adminEmail]);

        if (!$admin->exists) {
            $admin->id           = User::generateID();
            $admin->profile_pict = 'default.png';
        }

        $admin->name     = $adminName;
        $admin->password = Hash::make($adminPassword);
        $admin->role     = 'superadmin';
        $admin->save();

        $this->command->info('✅ Akun Admin berhasil disiapkan!');
        $this->command->info("   Email : {$adminEmail}");
        $this->command->comment('   Password diambil dari environment (ADMIN_PASSWORD).');
    }
}
