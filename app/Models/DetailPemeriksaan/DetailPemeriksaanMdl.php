<?php

namespace App\Models\DetailPemeriksaan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPemeriksaanMdl extends Model
{
    use HasFactory;

    protected $table = 'detail_pemeriksaan';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'pemeriksaan_id',
        'jenis_pemeriksaan',
        'nama_pemeriksaan',
        'paket_parameter_id',
        'harga',
    ];
}
