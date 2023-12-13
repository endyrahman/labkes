<?php

namespace App\Models\Pemeriksaan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{
    use HasFactory;

    protected $table = 'pemeriksaan';
    protected $primaryKey = 'id';
    protected $timestamps = false;

    protected $fillable = [
        'id',
        'jenis_lab_id',
        'jenis_sampel_id',
        'kemasan_sampel_id',
        'volume',
        'jmlh_sampel',
        'lokasi_sampel',
        'jenis_pemeriksaan_id',
        'parameter',
        'user_id',
        'tgl_ambil_sampel',
        'tgl_terima_sampel',
        'tgl_input',
        'status'
    ];
}
