<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Galeri extends Model
{
    use HasFactory, SoftDeletes; // P3: SoftDeletes agar data tidak terhapus permanen

    protected $table      = 'galeri';
    protected $primaryKey = 'id_galeri';
    protected $keyType    = 'string';

    protected $fillable = [
        'id_galeri',
        'id_kategori',
        'judul',
        'deskripsi',
        'images',
        'nama_kategori',
    ];

    public $incrementing = false;
    public $timestamps   = false;

    /**
     * Query builder untuk tabel galeri (DB facade).
     */
    public static function vgaleri()
    {
        return DB::table('galeri');
    }

    /**
     * Query builder untuk tabel kategori (DB facade).
     */
    public static function kategori()
    {
        return DB::table('kategori');
    }

    /**
     * Menghapus file gambar galeri dari storage.
     *
     * @param string $id ID galeri
     */
    public static function deleteImage(string $id): void
    {
        $item = Galeri::where('id_galeri', $id)->first();

        if ($item && !empty($item->images)) {
            $filepath = public_path('/img/gallery/' . $item->images);
            if (file_exists($filepath)) {
                unlink($filepath);
            }
        }
    }

    /**
     * Menghasilkan ID unik untuk galeri dengan format GL-001.
     * P2-Fix: Format konsisten 3 digit, zero-padded menggunakan regex + str_pad.
     *
     * @return string Contoh: GL-001, GL-002, GL-010
     */
    public static function galleryGenerateID(): string
    {
        $lastId = Galeri::max('id_galeri');

        if ($lastId) {
            preg_match('/\d+$/', $lastId, $matches);
            $num = isset($matches[0]) ? (int) $matches[0] : 0;
        } else {
            $num = 0;
        }

        return 'GL-' . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Menghasilkan ID unik untuk kategori galeri dengan format KT-001.
     * P2-Fix: Format konsisten 3 digit, zero-padded.
     *
     * @return string Contoh: KT-001, KT-002, KT-010
     */
    public static function categoryGenerateID(): string
    {
        $lastId = Galeri::kategori()->max('id_kategori');

        if ($lastId) {
            preg_match('/\d+$/', $lastId, $matches);
            $num = isset($matches[0]) ? (int) $matches[0] : 0;
        } else {
            $num = 0;
        }

        return 'KT-' . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
    }
}
