<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Support\IdGenerator;

class Dokter extends Model
{
    use HasFactory, SoftDeletes, \App\Models\Concerns\ClearsHomeCache; // P3: SoftDeletes agar data tidak terhapus permanen

    protected $table      = 'dokter';
    protected $primaryKey = 'id_dokter';
    protected $keyType    = 'string';

    protected $fillable = [
        'id_dokter',
        'nama_dokter',
        'no_hp_dokter',
        'images',
        'email_dokter',
        'jadwal_dokter',
        'str_dokter',
        'sip_dokter',
    ];

    public $incrementing = false;
    public $timestamps   = false;

    /**
     * Accessor agar pemanggilan $dokter->nama atau $dokter->name tetap aman.
     */
    public function getNamaAttribute(): string
    {
        return $this->nama_dokter ?? '';
    }

    public function getNameAttribute(): string
    {
        return $this->nama_dokter ?? '';
    }

    /**
     * Menghasilkan ID unik untuk dokter dengan format DOK-001.
     * K2-Fix: Dibungkus DB::transaction() + lockForUpdate() untuk mencegah race condition.
     *
     * @return string Contoh: DOK-001, DOK-002, DOK-010
     */
    public static function dokterGenerateID(): string
    {
        return IdGenerator::next('DOK-', Dokter::withTrashed(), 'id_dokter');
    }

    /**
     * Menghapus file foto dokter dari storage.
     *
     * @param string $id ID dokter
     */
    public static function deleteImage(string $id): void
    {
        $dokter = Dokter::withTrashed()->where('id_dokter', $id)->first();

        if ($dokter && !empty($dokter->images)) {
            $filepath = public_path('/img/dokter/' . $dokter->images);
            if (file_exists($filepath)) {
                unlink($filepath);
            }
        }
    }
}
