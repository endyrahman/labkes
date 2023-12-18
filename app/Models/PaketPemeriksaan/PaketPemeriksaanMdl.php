<?php

namespace App\Models\PaketPemeriksaan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketPemeriksaanMdl extends Model
{
    use HasFactory;
    protected $table = 'paket_pemeriksaan';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'lab_pemeriksaan_id',
        'nama_pemeriksaan',
        'arr_parameter_id',
        'status'
    ];
}
