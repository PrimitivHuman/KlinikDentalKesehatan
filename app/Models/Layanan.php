<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Support\IdGenerator;

/**
 * R3: Model Layanan untuk mengelola layanan/perawatan gigi klinik.
 */
class Layanan extends Model
{
    use HasFactory, \App\Models\Concerns\ClearsHomeCache;

    protected $table      = 'layanans';
    protected $primaryKey = 'id_layanan';
    protected $keyType    = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id_layanan',
        'nama_layanan',
        'deskripsi',
        'harga_mulai',
        'harga_sampai',
        'durasi',
        'images',
        'ikon',
        'aktif',
        'urutan',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    /**
     * Generate ID unik format LYN-001 dengan DB transaction (anti race condition).
     */
    public static function generateID(): string
    {
        return IdGenerator::next('LYN-', Layanan::query(), 'id_layanan');
    }

    /**
     * Hapus file gambar dari storage.
     */
    public static function deleteImage(string $id): void
    {
        $layanan = Layanan::where('id_layanan', $id)->first();
        if ($layanan && $layanan->images) {
            $path = public_path('/img/layanan/' . $layanan->images);
            if (file_exists($path)) {
                @unlink($path);
            }
        }
    }

    /**
     * Scope untuk layanan yang aktif saja (digunakan di frontend).
     */
    public function scopeAktif($query)
    {
        return $query->where('aktif', true)->orderBy('urutan');
    }

    /**
     * Format range harga untuk tampilan.
     */
    public function getHargaRangeAttribute(): string
    {
        if ($this->harga_mulai && $this->harga_sampai) {
            return 'Rp ' . number_format($this->harga_mulai, 0, ',', '.') . ' — Rp ' . number_format($this->harga_sampai, 0, ',', '.');
        }
        if ($this->harga_mulai) {
            return 'Mulai Rp ' . number_format($this->harga_mulai, 0, ',', '.');
        }
        return 'Hubungi Klinik';
    }
}
