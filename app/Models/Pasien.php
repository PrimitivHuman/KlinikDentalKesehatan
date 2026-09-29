<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pasien extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pasien';
    protected $primaryKey = 'id_pasien';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'nama_pasien',
        'tanggal_janji',
        'email_pasien',
        'no_hp_pasien',
        'alamat_pasien',
        'keluhan_pasien',
        'total_harga_pasien',
        'tindakan_pasien',
        'template',
        'status',
        'dokter_pilihan',
    ];

    /**
     * Format ID Pasien tampilan UI (misal: PSN-001, PSN-002).
     *
     * @return string
     */
    public function getFormattedIdAttribute() {
        return 'PSN-' . str_pad($this->id_pasien, 3, '0', STR_PAD_LEFT);
    }
}