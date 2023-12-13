<?php

namespace App\Models\JenisSampel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DB;

class JenisSampel extends Model
{
    use HasFactory;

    protected $table = 'jenis_sampel';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nama_sampel',
        'klinik',
        'kimia',
        'mikrobiologi',
        'kemasan_sampel_id',
        'status'
    ];

    public function getDataJenisSampel($jenis_sampel_id) {
        $data = DB::table('jenis_sampel')
            ->leftJoin('kemasan_sampel', 'jenis_sampel.kemasan_sampel_id', 'kemasan_sampel.id')
            ->select('jenis_sampel.id as jenis_sampel_id', 'jenis_sampel.nama_sampel', 'jenis_sampel.kemasan_sampel_id', 'kemasan_sampel.volume')
            ->where('jenis_sampel.id', $jenis_sampel_id)
            ->where('jenis_sampel.status', 1)
            ->first();

        return $data;
    }
}
