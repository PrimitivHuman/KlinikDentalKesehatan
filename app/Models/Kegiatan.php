<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Kegiatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kegiatans';
    protected $primaryKey = 'id_kegiatan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_kegiatan',
        'judul_kegiatan',
        'deskripsi_kegiatan',
        'tgl_kegiatan',
        'images',
    ];

    /**
     * Hapus file gambar kegiatan fisik jika data dihapus permanen.
     *
     * @param string $id
     * @return void
     */
    public static function deleteImage($id) {
        $kegiatan = Kegiatan::withTrashed()->where('id_kegiatan', $id)->first();
        if ($kegiatan && $kegiatan->images) {
            $image_path = public_path('/img/activity/' . $kegiatan->images);
            if (file_exists($image_path)) {
                @unlink($image_path);
            }
        }
    }

    /**
     * Generate ID otomatis format konsisten 3 digit (KGT-001, KGT-002, dst).
     * K2-Fix: Dibungkus DB::transaction() + lockForUpdate() untuk mencegah race condition.
     *
     * @return string
     */
    public static function generateID() {
        return DB::transaction(function () {
            $last = Kegiatan::withTrashed()->lockForUpdate()->orderBy('id_kegiatan', 'desc')->first();
            if (!$last) {
                return 'KGT-001';
            }

            preg_match('/\d+$/', $last->id_kegiatan, $matches);
            $number = isset($matches[0]) ? (int)$matches[0] + 1 : 1;

            return 'KGT-' . str_pad($number, 3, '0', STR_PAD_LEFT);
        });
    }
}
