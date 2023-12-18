<?php

namespace App\Models\PembayaranTbl;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranTbl extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'no_registrasi',
        'pemeriksaan_id',
        'homecare_id',
        'no_rekening',
        'nama_rekening',
        'asal_bank',
        'tgl_transfer',
        'nominal_transfer',
        'nominal_bayar',
        'bukti_bayar',
        'status',
        'verifikasi_user_id',
        'tgl_verifikasi'
    ];
}
