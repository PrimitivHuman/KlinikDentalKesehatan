<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tentang extends Model
{
    use HasFactory, \App\Models\Concerns\ClearsHomeCache;

    protected $table = 'tentang';
    protected $primaryKey = 'id_tentang';
    protected $keyType = 'string';

    protected $fillable = [
        'id_tentang',
        'informasi_umum',
        'foto_sampul',
        'visi', 
        'misi',
        'tupoksi'
    ];
    
    public $incrementing = false;
    public $timestamps = false;

    public static function deleteImage($data) {
        $filepath = public_path('/main/img/'.$data);
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }
}
