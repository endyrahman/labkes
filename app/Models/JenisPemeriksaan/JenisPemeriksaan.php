<?php

namespace App\Models\JenisPemeriksaan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class JenisPemeriksaan extends Model
{
    use HasFactory;

    protected $table = 'jenis_pemeriksaan';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'lab_pemeriksaan_id',
        'nama_pemeriksaan',
        'arr_parameter_id',
        'status'
    ];

    public function getComboJenisLabPemeriksaan($lab)
    {
        $data = DB::table('jenis_pemeriksaan')
            ->where('lab_pemeriksaan_id', $lab)
            ->where('status', 1)
            ->get();

        return $data;
    }

    public function getComboJenisSample($lab)
    {
        $data = DB::table('jenis_sampel');
            if ($lab == 1) {
                $data->where('klinik', 1);
            } elseif ($lab == 2) {
                $data->where('kimia', 1);
            } elseif ($lab == 3) {
                $data->where('mikrobiologi', 1);
            }
        $data->where('status', 1);
        $data = $data->get();

        return $data;
    }

    public function getComboKemasan($lab)
    {
        $data = DB::table('kemasan_sampel');
            if ($lab == 1) {
                $data->where('klinik', 1);
            } elseif ($lab == 2) {
                $data->where('kimia', 1);
            } elseif ($lab == 3) {
                $data->where('mikrobiologi', 1);
            }
        $data->where('status', 1);
        $data = $data->get();

        return $data;
    }

    public function getJenisPemeriksaan($jenis, $lab)
    {
        $data = DB::table('jenis_pemeriksaan')
            ->where('lab_pemeriksaan_id', $lab)
            ->where('id', $jenis)
            ->where('status', 1)
            ->first();

        return $data;
    }

    public function getParameterDarah($lab, $jenis_pemeriksaan_id) {
        $data = DB::table('parameter_pemeriksaan_darah')
            ->where('pemeriksaan', $lab)
            ->where('jenis_pemeriksaan_id', $jenis_pemeriksaan_id)
            ->orWhere('jenis_pemeriksaan_id', null)
            ->where('status', 1)
            ->get();

        return $data;
    }

    public function getParameterKimia($arrParameterPemeriksaan) {
        $data = DB::table('parameter_pemeriksaan_kimia')
            ->whereIn('id', $arrParameterPemeriksaan)
            ->where('status', 1)
            ->get();

        return $data;
    }

    public function getParameterMikrobiologi($arrParameterPemeriksaan) {
        $data = DB::table('parameter_pemeriksaan_mikrobiologi')
            ->whereIn('id', $arrParameterPemeriksaan)
            ->where('status', 1)
            ->get();

        return $data;
    }

    public function getParameterAir($lab) {
        $data = DB::table('parameter_pemeriksaan_air')
            ->where('pemeriksaan', $lab)
            ->where('status', 1)
            ->get();

        return $data;
    }

    public function getParameterMamin() {
        $data = DB::table('parameter_pemeriksaan_mamin')
            ->where('status', 1)
            ->get();

        return $data;
    }

    public function getParameterUdara() {
        $data = DB::table('parameter_pemeriksaan_udara')
            ->where('status', 1)
            ->get();

        return $data;
    }
}
