<?php

namespace App\Models\ParameterPemeriksaan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParameterKlinikTbl extends Model
{
    use HasFactory;

    protected $table = 'parameter_pemeriksaan_klinik';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nama_parameter',
        'harga',
        'status'
    ];
}
