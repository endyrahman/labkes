<?php

namespace App\Models\PasienTbl;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class PasienTbl extends Model
{
    use HasFactory;

    protected $table = 'pasien';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'nama_pasien',
        'nik',
        'tempat_lahir',
        'tgl_lahir',
        'jenis_kelamin',
        'alamat',
        'created_at',
        'updated_at'
    ];

    public static function getDataPasien() {
        $pasien = DB::table('pasien')->where('user_id', Auth::id())->get();

        return $pasien;
    }
}
