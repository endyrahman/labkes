<?php

namespace App\Models\Temp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempPaketPemeriksaanTbl extends Model
{
    use HasFactory;

    protected $table = 'temp_paket_pemeriksaan';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_id',
        'paket_pemeriksaan_id',
        'biaya'
    ];
}
