<?php

namespace App\Models\Kegiatan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class KegiatanTbl extends Model
{
    use HasFactory;

    protected $table = 'lp_kegiatan';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'nama_kegiatan',
        'lokasi',
        'keterangan',
        'tgl_kegiatan',
        'user_id',
        'foto_kegiatan',
        'created_at',
        'updated_at'
    ];

    public static function getDataLpKegiatan() {
        $kegiatan = DB::table('lp_kegiatan')->paginate(3, ['*'], 'pagelpkegiatan');

        return $kegiatan;
    }

    public function getDataKegiatan() {
        $kegiatan = DB::table('lp_kegiatan')->paginate(9, ['*'], 'pagekegiatan');

        return $kegiatan;
    }
}
