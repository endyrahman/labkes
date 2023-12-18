<?php

namespace App\Models\HomeCareTbl;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeCareTbl extends Model
{
    use HasFactory;

    protected $table = 'homecare';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'jenis_homecare_id',
        'jenis_sampel_id',
        'no_pelanggan_silkes',
        'no_sample_silkes',
        'fileLabHasilPemeriksaan',
        'nama_lengkap',
        'tgl_waktu_kunjungan',
        'tgl_input',
        'tgl_edit',
        'no_hp',
        'jarak',
        'catatan_homecare',
        'latitude',
        'longitude',
        'alamat_lengkap',
        'user_id',
        'status'
    ];
}
