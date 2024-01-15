<?php

namespace App\Models\Temp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempParameterPemeriksaanTbl extends Model
{
    use HasFactory;

    protected $table = 'temp_parameter_pemeriksaan';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'user_id',
        'parameter_id',
        'biaya'
    ];
}
