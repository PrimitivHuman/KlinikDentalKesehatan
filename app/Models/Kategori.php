<?php

namespace App\Models;

use App\Support\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Kategori foto galeri (tabel `kategori`).
 */
class Kategori extends Model
{
    use HasFactory;

    protected $table      = 'kategori';
    protected $primaryKey = 'id_kategori';
    protected $keyType    = 'string';

    public $incrementing = false;
    public $timestamps   = false;

    protected $fillable = [
        'id_kategori',
        'nama_kategori',
    ];

    /**
     * Foto galeri yang memakai kategori ini.
     */
    public function galeri()
    {
        return $this->hasMany(Galeri::class, 'id_kategori', 'id_kategori');
    }

    /**
     * Menghasilkan ID unik kategori format KT-001 (numerik, aman setelah 999).
     */
    public static function generateID(): string
    {
        return IdGenerator::next('KT-', static::query(), 'id_kategori');
    }
}
