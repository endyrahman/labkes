<?php

namespace App\Models\PemeriksaanTbl;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class PemeriksaanTbl extends Model
{
    use HasFactory;

    protected $table = 'pemeriksaan';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'jenis_lab_id',
        'tgl_waktu_kunjungan',
        'no_urut',
        'no_pelanggan_silkes',
        'no_sampel_silkes',
        'total_biaya',
        'status_bayar',
        'fileLaboratoriumHasilPemeriksaan',
        'status',
        'user_id',
        'pasien_id',
        'count_cetak',
        'created_at',
        'updated_at',
    ];

    public function getPaketPemeriksaan($jenis_lab) {
        $paketpemeriksaan = DB::table('paket_pemeriksaan')->select('id as paket_pemeriksaan_id', 'nama_pemeriksaan', 'arr_parameter_id', 'arr_parameter_tambahan_id', 'lab_pemeriksaan_id')->where('lab_pemeriksaan_id', $jenis_lab)->paginate(8, ['*'], 'pagepaketpemeriksaan');

        foreach ($paketpemeriksaan as $key => $val) {
            $sumharga = 0;
            $arrparameter = explode(',', $val->arr_parameter_id);
            $parameter = DB::table('parameter_pemeriksaan_klinik')->select('id as parameter_id', 'nama_parameter', 'harga')->whereIn('id', $arrparameter)->get();

            foreach ($parameter as $valparam) {
                $sumharga += $valparam->harga;
            }

            $paketpemeriksaan[$key]->detail = $parameter;
            $paketpemeriksaan[$key]->total_harga = number_format($sumharga, 0, ',', '.');
        }

        return $paketpemeriksaan;
    }

    public function getHargaPaketPemeriksaan($jenis_lab, $arrval) {
        $paketpemeriksaan = DB::table('paket_pemeriksaan')->select('id as paket_pemeriksaan_id', 'nama_pemeriksaan', 'arr_parameter_id', 'arr_parameter_tambahan_id', 'lab_pemeriksaan_id')->where('lab_pemeriksaan_id', $jenis_lab)->whereIn('id', $arrval)->get();

        $sumtotal = 0;

        foreach ($paketpemeriksaan as $key => $val) {
            $sumharga = 0;
            $arrparameter = explode(',', $val->arr_parameter_id);
            $parameter = DB::table('parameter_pemeriksaan_klinik')->select('id as parameter_id', 'nama_parameter', 'harga')->whereIn('id', $arrparameter)->get();

            foreach ($parameter as $valparam) {
                $sumharga += $valparam->harga;
            }

            $sumtotal += $sumharga;
        }

        return $sumtotal;
    }

    public function getHargaParameterPemeriksaan($jenis_lab, $arrval) {
        $sumtotal = 0;

        if ($jenis_lab == 1) {
            $sumtotal = DB::table('parameter_pemeriksaan_klinik')->whereIn('id', $arrval)->sum('harga');
        } elseif ($jenis_lab == 2) {
            $sumtotal = DB::table('parameter_pemeriksaan_kimia')->whereIn('id', $arrval)->sum('harga');
        } elseif ($jenis_lab == 3) {
            $sumtotal = DB::table('parameter_pemeriksaan_mikrobiologi')->whereIn('id', $arrval)->sum('harga');
        }

        return $sumtotal;
    }

    public function getDataPaketPemeriksaan($jenis_lab, $arrval) {
        $paketpemeriksaan = DB::table('paket_pemeriksaan')->select('id', 'nama_pemeriksaan', 'arr_parameter_id', 'lab_pemeriksaan_id')
        ->where('lab_pemeriksaan_id', $jenis_lab)->whereIn('id', $arrval)->get();

        $sumtotal = 0;

        foreach ($paketpemeriksaan as $key => $val) {
            $sumharga = 0;
            $arrparameter = explode(',', $val->arr_parameter_id);
            $parameter = DB::table('parameter_pemeriksaan_klinik')->select('id as parameter_id', 'nama_parameter', 'harga')->whereIn('id', $arrparameter)->get();

            foreach ($parameter as $valparam) {
                $sumharga += $valparam->harga;
            }

            $paketpemeriksaan[$key]->sumtotal = $sumharga;
            $paketpemeriksaan[$key]->is_paket = 1;
        }

        return $paketpemeriksaan;
    }

    public function getDataParameterPemeriksaan($jenis_lab, $arrval) {

        if ($jenis_lab == 1) {
            $parameter = DB::table('parameter_pemeriksaan_klinik')->select('id', 'nama_parameter as nama_pemeriksaan', 'harga as sumtotal')->whereIn('id', $arrval)->get();
        } elseif ($jenis_lab == 2) {
            $parameter = DB::table('parameter_pemeriksaan_kimia')->select('id', 'nama_parameter as nama_pemeriksaan', 'harga as sumtotal')->whereIn('id', $arrval)->get();
        } elseif ($jenis_lab == 3) {
            $parameter = DB::table('parameter_pemeriksaan_mikrobiologi')->select('id', 'nama_parameter as nama_pemeriksaan', 'harga as sumtotal')->whereIn('id', $arrval)->get();
        }

        foreach ($parameter as $key => $val) {
            $parameter[$key]->lab_pemeriksaan_id = $jenis_lab;
            $parameter[$key]->is_paket = null;
        }

        return $parameter;
    }

    public function getDataPemeriksaanByLab() {
        $data = DB::select(DB::raw(
            "SELECT b.nama_jenis_lab, COUNT(*) as jumlah FROM pemeriksaan a
            LEFT JOIN jenis_lab b
            ON a.jenis_lab_id = b.id
            WHERE MONTH(tgl_waktu_kunjungan) = MONTH(CURRENT_DATE())
            GROUP BY a.jenis_lab_id"
        ));

        return $data;
    }

    public function getPemeriksaanLp($jenis_lab) {
        if ($jenis_lab == 1) {
            $laboratorium = DB::table('parameter_pemeriksaan_klinik');
        } elseif ($jenis_lab == 2) {
            $laboratorium = DB::table('parameter_pemeriksaan_kimia');
        } elseif ($jenis_lab == 3) {
            $laboratorium = DB::table('parameter_pemeriksaan_mikrobiologi');
        }

        $laboratorium = $laboratorium->paginate(8, ['*'], 'pagepemeriksaan');

        return $laboratorium;
    }

    public function getDataMasterPaketPemeriksaan() {
        $paketpemeriksaan = DB::table('paket_pemeriksaan')
            ->leftJoin('jenis_lab', 'paket_pemeriksaan.lab_pemeriksaan_id', 'jenis_lab.id')
            ->select('paket_pemeriksaan.id as paket_pemeriksaan_id', 'nama_pemeriksaan', 'arr_parameter_id', 'lab_pemeriksaan_id', 'nama_jenis_lab', 'paket_pemeriksaan.status')
            ->paginate(10, ['*'], 'pagemstgridpaketpemeriksaan');

        foreach ($paketpemeriksaan as $key => $val) {
            $arrparameter = explode(',', $val->arr_parameter_id);
            if ($val->lab_pemeriksaan_id == 1) {
                $parameter = DB::table('parameter_pemeriksaan_klinik')->select('id as parameter_id', 'nama_parameter', 'harga')->whereIn('id', $arrparameter)->get();

                $paketpemeriksaan[$key]->detail = $parameter;
            } elseif ($val->lab_pemeriksaan_id == 2) {
                $parameter = DB::table('parameter_pemeriksaan_kimia')->select('id as parameter_id', 'nama_parameter', 'harga')->whereIn('id', $arrparameter)->get();

                $paketpemeriksaan[$key]->detail = $parameter;
            } elseif ($val->lab_pemeriksaan_id == 3) {
                $parameter = DB::table('parameter_pemeriksaan_mikrobiologi')->select('id as parameter_id', 'nama_parameter', 'harga')->whereIn('id', $arrparameter)->get();

                $paketpemeriksaan[$key]->detail = $parameter;
            }
        }

        return $paketpemeriksaan;
    }
}
