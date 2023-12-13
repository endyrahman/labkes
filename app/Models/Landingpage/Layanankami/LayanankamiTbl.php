<?php

namespace App\Models\Landingpage\Layanankami;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class LayanankamiTbl extends Model
{
    use HasFactory;

    protected $table = 'lp_layanan_kami';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'nama_layanan',
        'keterangan',
        'user_id',
        'foto_layanan',
        'created_at',
        'updated_at'
    ];

    public function getDataLayananKami($id) {
        $kegiatan = DB::table('lp_layanan_kami')->where('id', $id)->first();

        return $kegiatan;
    }
}
