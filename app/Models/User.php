<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'id';
    protected $keyType    = 'string';

    public $incrementing = false;

    /**
     * Atribut yang dapat diisi secara massal.
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'profile_pict',
        'role', // P3: Kolom role (admin | operator)
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atribut yang di-cast ke tipe tertentu.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Menghasilkan ID unik untuk akun admin dengan format AK-001.
     * P2-Fix: Gunakan regex untuk ekstraksi angka agar format konsisten (3 digit, zero-padded).
     *
     * @return string Contoh: AK-001, AK-002, AK-010
     */
    public static function generateID(): string
    {
        $lastId = User::max('id');

        if ($lastId) {
            // Ekstrak bagian angka dari akhir ID (misal: "AK-001" → 1, "AK-010" → 10)
            preg_match('/\d+$/', $lastId, $matches);
            $num = isset($matches[0]) ? (int) $matches[0] : 0;
        } else {
            $num = 0;
        }

        return 'AK-' . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Menghapus file foto profil dari storage berdasarkan ID user.
     *
     * @param string $id ID user
     */
    public static function deleteImage(string $id): void
    {
        $user = User::where('id', $id)->first();

        if ($user && !empty($user->profile_pict)) {
            $filepath = public_path('/img/account/' . $user->profile_pict);
            if (file_exists($filepath)) {
                unlink($filepath);
            }
        }
    }
}
