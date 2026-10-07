<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Support\IdGenerator;

class Galeri extends Model
{
    use HasFactory, SoftDeletes, \App\Models\Concerns\ClearsHomeCache; // P3: SoftDeletes agar data tidak terhapus permanen

    protected $table      = 'galeri';
    protected $primaryKey = 'id_galeri';
    protected $keyType    = 'string';

    protected $fillable = [
        'id_galeri',
        'id_kategori',
        'judul',
        'deskripsi',
        'images',
        // K5 Fix: 'nama_kategori' dihapus — gunakan relasi ke tabel kategori
    ];

    /**
     * K6 Fix: Relasi ke tabel kategori via Eloquent.
     * Menggantikan denormalisasi kolom nama_kategori di tabel galeri.
     */
    public function kategoriRelasi()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public $incrementing = false;
    public $timestamps   = false;

    /**
     * Query builder galeri (Eloquent, sudah menghormati soft delete).
     */
    public static function vgaleri()
    {
        return Galeri::query();
    }

    /**
     * Query builder kategori (Eloquent via model Kategori).
     */
    public static function kategori()
    {
        return Kategori::query();
    }

    /**
     * Menghapus file gambar galeri dari storage.
     *
     * @param string $id ID galeri
     */
    public static function deleteImage(string $id): void
    {
        $item = Galeri::withTrashed()->where('id_galeri', $id)->first();

        if ($item && !empty($item->images)) {
            $filepath = public_path('/img/gallery/' . $item->images);
            if (file_exists($filepath)) {
                unlink($filepath);
            }
        }
    }

    /**
     * Menghasilkan ID unik untuk galeri dengan format GL-001.
     * K2-Fix: Dibungkus DB::transaction() + lockForUpdate() untuk mencegah race condition.
     *
     * @return string Contoh: GL-001, GL-002, GL-010
     */
    public static function galleryGenerateID(): string
    {
        return IdGenerator::next('GL-', Galeri::withTrashed(), 'id_galeri');
    }

    /**
     * Menghasilkan ID unik untuk kategori galeri dengan format KT-001.
     *
     * @return string Contoh: KT-001, KT-002, KT-010
     */
    public static function categoryGenerateID(): string
    {
        return Kategori::generateID();
    }
}
