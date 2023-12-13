<?php

namespace App\Models\DetailMikrobiologi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailMikrobiologiMdl extends Model
{
    use HasFactory;

    protected $table = 'detail_pemeriksaan_mikrobiologi';
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
