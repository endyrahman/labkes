<?php

namespace App\Models\DetailKimia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailKimiaMdl extends Model
{
    use HasFactory;

    protected $table = 'detail_pemeriksaan_kimia';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'pemeriksaan_id',
        'kemasan_sampel_id',
        'jenis_sampel_id',
        'volume',
        'jmlh_sampel',
        'lokasi_sampel',
    ];
}
