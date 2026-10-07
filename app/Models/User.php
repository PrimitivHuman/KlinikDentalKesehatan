<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use App\Support\IdGenerator;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'id';
    protected $keyType    = 'string';

    public $incrementing = false;

    /**
     * Nilai default atribut model.
     */
    protected $attributes = [
        'role'         => 'superadmin',
        'profile_pict' => 'default.png',
    ];

    /**
     * Atribut yang dapat diisi secara massal.
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'profile_pict',
        'role', // Saat ini hanya satu role: superadmin
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
     * K2-Fix: Dibungkus DB::transaction() untuk mencegah race condition
     * ketika dua request membuat akun baru secara bersamaan.
     *
     * @return string Contoh: AK-001, AK-002, AK-010
     */
    public static function generateID(): string
    {
        return IdGenerator::next('AK-', User::query(), 'id');
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
