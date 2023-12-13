<?php

namespace App\Models\Antrian;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    use HasFactory;

    protected $table = 'antrian';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'tgl_waktu_kunjungan',
        'pemeriksaan_id',
        'user_id',
        'sequence',
        'created_at',
        'updated_at'
    ];
}
