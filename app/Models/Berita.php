<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Support\IdGenerator;
use Illuminate\Support\Str;

/**
 * R2: Model Berita untuk fitur blog/artikel klinik.
 */
class Berita extends Model
{
    use HasFactory, SoftDeletes, \App\Models\Concerns\ClearsHomeCache;

    protected $table      = 'beritas';
    protected $primaryKey = 'id_berita';
    protected $keyType    = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id_berita',
        'judul',
        'slug',
        'isi',
        'images',
        'penulis',
        'status',
        'tgl_terbit',
    ];

    protected $casts = [
        'tgl_terbit' => 'date',
    ];

    /**
     * Generate ID unik format BRT-001 dengan DB transaction (anti race condition).
     *
     * @return string
     */
    public static function generateID(): string
    {
        return IdGenerator::next('BRT-', Berita::withTrashed(), 'id_berita');
    }

    /**
     * Generate slug unik dari judul artikel.
     *
     * @param string $judul
     * @return string
     */
    public static function generateSlug(string $judul): string
    {
        $base = Str::slug($judul, '-') ?: 'artikel';
        $slug = $base;
        $i    = 1;

        while (Berita::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    /**
     * Hapus file gambar dari storage.
     *
     * @param string $id
     */
    public static function deleteImage(string $id): void
    {
        $berita = Berita::withTrashed()->where('id_berita', $id)->first();
        if ($berita && $berita->images) {
            $path = public_path('/img/berita/' . $berita->images);
            if (file_exists($path)) {
                @unlink($path);
            }
        }
    }

    /**
     * Relasi ke User (penulis artikel).
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'penulis', 'name');
    }
}
