<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * R2: Model Berita untuk fitur blog/artikel klinik.
 */
class Berita extends Model
{
    use HasFactory, SoftDeletes;

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
        return DB::transaction(function () {
            $lastId = Berita::withTrashed()->lockForUpdate()->max('id_berita');

            if ($lastId) {
                preg_match('/\d+$/', $lastId, $matches);
                $num = isset($matches[0]) ? (int) $matches[0] : 0;
            } else {
                $num = 0;
            }

            return 'BRT-' . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Generate slug unik dari judul artikel.
     *
     * @param string $judul
     * @return string
     */
    public static function generateSlug(string $judul): string
    {
        $slug = Str::slug($judul, '-');
        $count = Berita::where('slug', 'like', "{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
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
