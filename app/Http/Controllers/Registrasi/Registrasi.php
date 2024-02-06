<?php

namespace App\Http\Controllers\Registrasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JenisPemeriksaan\JenisPemeriksaan;
use RealRashid\SweetAlert\Facades\Alert;
use Auth;
use DB;
use App\Models\StatusWa\StatusWa;
use App\Models\PemeriksaanTbl\PemeriksaanTbl;
use App\Models\DetailPemeriksaan\DetailPemeriksaanMdl;
use App\Models\DetailKimia\DetailKimiaMdl;
use App\Models\DetailMikrobiologi\DetailMikrobiologiMdl;
use App\Models\Antrian\Antrian;
use App\Models\JenisSampel\JenisSampel;
use App\Models\Temp\TempPaketPemeriksaanTbl;
use App\Models\Temp\TempParameterPemeriksaanTbl;
use Illuminate\Support\Facades\Redirect;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Redis;

class Registrasi extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function gridRegistrasiVerifikasi() {
        $data = DB::table('view_pemeriksaan')
            ->select('view_pemeriksaan.*', 'users.no_hp')
            ->leftJoin('users', 'view_pemeriksaan.user_id', 'users.id')
            ->orderBy('view_pemeriksaan.created_at', 'desc')
            ->paginate(15, ['*'], 'pagegridverifikasipemeriksaan');

        foreach ($data as $key => $val) {
            $whatsapp = DB::table('status_wa')->where('pemeriksaan_id', $val->id)->first();
            if ($whatsapp) {
                $countWa = DB::table('status_wa')->where('pemeriksaan_id', $val->id)->count();

                $data[$key]->countwa = $countWa;
            } else {
                $data[$key]->countwa = "-";
            }
        }

        return view('registrasi.verifikasi.index', compact('data'));
    }

    public function paginationGridVerifikasiPemeriksaan(Request $request) {
        if($request->ajax())
        {
            $combocari = $request->combocari;
            $pencarian = $request->pencarian;
            $jenis_lab_id = $request->jenis_lab_id;

            $data = DB::table('view_pemeriksaan')->orderBy('created_at', 'desc');

            if ($combocari) {
                $data->where($combocari, 'like', '%'.$pencarian.'%');
            }

            $data = $data->paginate(15, ['*'], 'pagegridverifikasipemeriksaan');

            return view('registrasi.verifikasi.gridverifikasipemeriksaan', compact('data'))->render();
        }
    }

    public function gridKlinik() {
        $data = DB::table('view_pemeriksaan')
            ->where('user_id', Auth::user()->id)->where('jenis_lab_id', 1)
            ->paginate(15, ['*'], 'pagegridpemeriksaan');

        return view('registrasi.klinik.index', compact('data'));
    }

    public function createKlinik() {
        $pemeriksaan = new PemeriksaanTbl();

        DB::table('temp_paket_pemeriksaan')->where('user_id', Auth::id())->delete();
        DB::table('temp_parameter_pemeriksaan')->where('user_id', Auth::id())->delete();

        $pemeriksaan = $pemeriksaan->getPaketPemeriksaan(1);
        $klinik = DB::table('parameter_pemeriksaan_klinik')->where('status', 1)->paginate(16, ['*'], 'pageparameterpemeriksaan');

        $paketterpilih = [];
        $parameterterpilih = [];

        return view('registrasi.klinik.create', compact('pemeriksaan', 'klinik', 'paketterpilih', 'parameterterpilih'));
    }

    public function storeklinik(Request $request) {
        $user = Auth::user();

        DB::beginTransaction();

        $jenis_lab_id = $request->jenis_lab_id;
        $pasien_id = $request->pasien_id;
        $tgl_waktu_kunjungan = $request->tglwaktukunjungan;
        $total_biaya = str_ireplace(".", "", $request->total_biaya);

        $paket_pemeriksaan = DB::table('temp_paket_pemeriksaan')->where('user_id', Auth::id())->get();
        $parameter_pemeriksaan = DB::table('temp_parameter_pemeriksaan')->where('user_id', Auth::id())->get();

        $no_urut = PemeriksaanTbl::where(DB::raw('DATE_FORMAT(tgl_waktu_kunjungan,"%Y-%m-%d")'), date('Y-m-d', strtotime($tgl_waktu_kunjungan)))->max('no_urut');
        $nourut = $no_urut + 1;

        try {
            $pemeriksaan = PemeriksaanTbl::create([
                "jenis_lab_id" => $jenis_lab_id,
                "pasien_id" => $pasien_id,
                "no_urut" => $nourut,
                "tgl_waktu_kunjungan" => date('Y-m-d H:i:s', strtotime($tgl_waktu_kunjungan)),
                "total_biaya" => str_ireplace(".", "", $total_biaya),
                "user_id" => $user->id,
                "status" => 1
            ]);

            if ($paket_pemeriksaan) {
                foreach ($paket_pemeriksaan as $val) {
                    $paket = DB::table('paket_pemeriksaan')->where('id', $val->paket_pemeriksaan_id)->select('nama_pemeriksaan')->first();
                    $detailPemeriksaan = DetailPemeriksaanMdl::create([
                        "pemeriksaan_id" => $pemeriksaan->id,
                        "jenis_pemeriksaan" => 1,
                        "nama_pemeriksaan" => $paket->nama_pemeriksaan,
                        "paket_parameter_id" => $val->paket_pemeriksaan_id,
                        "harga" => $val->biaya
                    ]);
                }
            }

            if ($parameter_pemeriksaan) {
                foreach ($parameter_pemeriksaan as $val) {
                    $paket = DB::table('parameter_pemeriksaan_klinik')->where('id', $val->parameter_id)->select('nama_parameter')->first();
                    $detailPemeriksaan = DetailPemeriksaanMdl::create([
                        "pemeriksaan_id" => $pemeriksaan->id,
                        "jenis_pemeriksaan" => 2,
                        "nama_pemeriksaan" => $paket->nama_parameter,
                        "paket_parameter_id" => $val->parameter_id,
                        "harga" => $val->biaya
                    ]);
                }
            }

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Disimpan');

            if ($jenis_lab_id == 2) {
                $jenislab = 'kimia';
            } elseif ($jenis_lab_id == 3) {
                $jenislab = 'mikrobiologi';
            } elseif ($jenis_lab_id == 1) {
                $jenislab = 'klinik';
            }
            return redirect::to('/registrasi/'.$jenislab);
        } catch (\Throwable $t) {
            dd($t);
            if ($jenis_lab_id == 2) {
                $jenislab = 'kimia';
            } elseif ($jenis_lab_id == 3) {
                $jenislab = 'mikrobiologi';
            } elseif ($jenis_lab_id == 1) {
                $jenislab = 'klinik';
            }

            Alert::error('Gagal', 'Data Gagal Disimpan');

            DB::rollback();
            return redirect::to('/registrasi/'.$jenislab);
        }
    }

    public function editKlinik($pemeriksaan_id) {
        $pemeriksaan = new PemeriksaanTbl();

        DB::table('temp_paket_pemeriksaan')->where('user_id', Auth::id())->delete();
        DB::table('temp_parameter_pemeriksaan')->where('user_id', Auth::id())->delete();

        $data = DB::table('view_pemeriksaan')->where('id', $pemeriksaan_id)->first();

        $detailPemeriksaan = DB::table('detail_pemeriksaan')->where('pemeriksaan_id', $pemeriksaan_id)->get();

        foreach ($detailPemeriksaan as $val) {
            if ($val->jenis_pemeriksaan == 1) {
                TempPaketPemeriksaanTbl::create([
                    "user_id" => Auth::id(),
                    "paket_pemeriksaan_id" => $val->paket_parameter_id,
                    "biaya" => $val->harga
                ]);
            } else {
                TempParameterPemeriksaanTbl::create([
                    "user_id" => Auth::id(),
                    "parameter_id" => $val->paket_parameter_id,
                    "biaya" => $val->harga
                ]);
            }
        }

        $paket = DB::table('temp_paket_pemeriksaan')->where('user_id', '=', Auth::id())->get();
        $parameter = DB::table('temp_parameter_pemeriksaan')->where('user_id', '=', Auth::id())->get();
        $arrpaket = [];
        $arrparameter = [];

        foreach ($paket as $val) {
            array_push($arrpaket, $val->paket_pemeriksaan_id);
        }

        foreach ($parameter as $val) {
            array_push($arrparameter, $val->parameter_id);
        }

        $pemeriksaan = $pemeriksaan->getPaketPemeriksaan(1);
        $klinik = DB::table('parameter_pemeriksaan_klinik')->where('status', 1)->paginate(16, ['*'], 'pageparameterpemeriksaan');
        $paketterpilih = $arrpaket;
        $parameterterpilih = $arrparameter;

        return view('registrasi.klinik.edit', compact('pemeriksaan', 'klinik', 'paketterpilih', 'parameterterpilih', 'data'));
    }

    public function updateKlinik(Request $request) {
        $user = Auth::user();

        DB::beginTransaction();

        $pemeriksaan_id = $request->pemeriksaan_id;
        $jenis_lab_id = $request->jenis_lab_id;
        $pasien_id = $request->pasien_id;
        $tgl_waktu_kunjungan = $request->tglwaktukunjungan;
        $total_biaya = str_ireplace(".", "", $request->total_biaya);

        $paket_pemeriksaan = DB::table('temp_paket_pemeriksaan')->where('user_id', Auth::id())->get();
        $parameter_pemeriksaan = DB::table('temp_parameter_pemeriksaan')->where('user_id', Auth::id())->get();

        try {
            $uPemeriksaan = PemeriksaanTbl::where('id', $pemeriksaan_id)
                ->update([
                    "jenis_lab_id" => $jenis_lab_id,
                    "pasien_id" => $pasien_id,
                    "tgl_waktu_kunjungan" => date('Y-m-d H:i:s', strtotime($tgl_waktu_kunjungan)),
                    "total_biaya" => str_ireplace(".", "", $total_biaya),
                    "user_id" => $user->id,
                    "status" => 1
                ]);

            $detail_pemeriksaan = DB::table('detail_pemeriksaan')->where('pemeriksaan_id', $pemeriksaan_id)->get();

            foreach ($detail_pemeriksaan as $val) {
                DB::table('detail_pemeriksaan')->where('id', $val->id)->delete();
            }

            if ($paket_pemeriksaan) {
                foreach ($paket_pemeriksaan as $val) {
                    $paket = DB::table('paket_pemeriksaan')->where('id', $val->paket_pemeriksaan_id)->select('nama_pemeriksaan')->first();
                    $detailPemeriksaan = DetailPemeriksaanMdl::create([
                        "pemeriksaan_id" => $pemeriksaan_id,
                        "jenis_pemeriksaan" => 1,
                        "nama_pemeriksaan" => $paket->nama_pemeriksaan,
                        "paket_parameter_id" => $val->paket_pemeriksaan_id,
                        "harga" => $val->biaya
                    ]);
                }
            }

            if ($parameter_pemeriksaan) {
                foreach ($parameter_pemeriksaan as $val) {
                    $paket = DB::table('parameter_pemeriksaan_klinik')->where('id', $val->parameter_id)->select('nama_parameter')->first();
                    $detailPemeriksaan = DetailPemeriksaanMdl::create([
                        "pemeriksaan_id" => $pemeriksaan_id,
                        "jenis_pemeriksaan" => 2,
                        "nama_pemeriksaan" => $paket->nama_parameter,
                        "paket_parameter_id" => $val->parameter_id,
                        "harga" => $val->biaya
                    ]);
                }
            }

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Diupdate');

            return redirect::to('/registrasi/klinik');
        } catch (\Throwable $t) {
            dd($t);

            Alert::error('Gagal', 'Data Gagal Diupdate');

            DB::rollback();
            return redirect::to('/registrasi/klinik');
        }
    }

    public function paginationParameterPemeriksaan(Request $request) {
        $pemeriksaan = new PemeriksaanTbl();

        $parameter = DB::table('temp_parameter_pemeriksaan')->where('user_id', '=', Auth::id())->get();
        $parameterterpilih = [];

        foreach ($parameter as $val) {
            array_push($parameterterpilih, $val->parameter_id);
        }

        $klinik = DB::table('parameter_pemeriksaan_klinik')->where('status', 1)->paginate(16, ['*'], 'pageparameterpemeriksaan');

        return view('registrasi.klinik.parameterpemeriksaan', compact('klinik', 'parameterterpilih'))->render();
    }

    public function paginationGridPemeriksaan(Request $request) {
        if($request->ajax())
        {
            $combocari = $request->combocari;
            $pencarian = $request->pencarian;
            $jenis_lab_id = $request->lab_id;

            if ($jenis_lab_id == 1) {
                $data = DB::table('view_pemeriksaan')
                    ->where('user_id', Auth::user()->id)
                    ->where('jenis_lab_id', 1);
                $url = 'registrasi.klinik.gridKlinik';

            } elseif ($jenis_lab_id == 2) {
                $data = DB::table('view_pemeriksaan')
                    ->select('view_pemeriksaan.*', 'detail_pemeriksaan_kimia.volume', 'detail_pemeriksaan_kimia.jmlh_sampel', 'detail_pemeriksaan_kimia.lokasi_sampel', 'kemasan_sampel.nama_kemasan', 'jenis_sampel.nama_sampel')
                    ->leftJoin('detail_pemeriksaan_kimia', 'view_pemeriksaan.id', 'detail_pemeriksaan_kimia.pemeriksaan_id')
                    ->leftJoin('kemasan_sampel', 'detail_pemeriksaan_kimia.kemasan_sampel_id', 'kemasan_sampel.id')
                    ->leftJoin('jenis_sampel', 'detail_pemeriksaan_kimia.jenis_sampel_id', 'jenis_sampel.id')
                    ->where('user_id', Auth::user()->id)->where('jenis_lab_id', 2);
                $url = 'registrasi.kimia.gridKimia';

            } elseif ($jenis_lab_id == 3) {
                $data = DB::table('view_pemeriksaan')
                    ->select('view_pemeriksaan.*', 'detail_pemeriksaan_mikrobiologi.volume', 'detail_pemeriksaan_mikrobiologi.jmlh_sampel', 'detail_pemeriksaan_mikrobiologi.lokasi_sampel', 'kemasan_sampel.nama_kemasan', 'jenis_sampel.nama_sampel')
                    ->leftJoin('detail_pemeriksaan_mikrobiologi', 'view_pemeriksaan.id', 'detail_pemeriksaan_mikrobiologi.pemeriksaan_id')
                    ->leftJoin('kemasan_sampel', 'detail_pemeriksaan_mikrobiologi.kemasan_sampel_id', 'kemasan_sampel.id')
                    ->leftJoin('jenis_sampel', 'detail_pemeriksaan_mikrobiologi.jenis_sampel_id', 'jenis_sampel.id')
                    ->where('user_id', Auth::user()->id)->where('jenis_lab_id', 3);
                $url = 'registrasi.mikrobiologi.gridMikrobiologi';
            }

            if ($combocari) {
                if ($combocari != 'tgl_waktu_kunjungan') {
                    $data->where($combocari, 'like', '%'.$pencarian.'%');
                } else {
                    $data->where($combocari, 'like', '%'.date('Y-m-d', strtotime($pencarian)).'%');
                }
            }

            $data = $data->paginate(15, ['*'], 'pagegridpemeriksaan');

            return view($url, compact('data'))->render();
        }
    }

    public function paginationPaketPemeriksaan(Request $request) {
        $pemeriksaan = new PemeriksaanTbl();
        $paket = DB::table('temp_paket_pemeriksaan')->where('user_id', '=', Auth::id())->get();
        $paketterpilih = [];

        foreach ($paket as $val) {
            array_push($paketterpilih, $val->paket_pemeriksaan_id);
        }

        $pemeriksaan = $pemeriksaan->getPaketPemeriksaan(1);

        return view('registrasi.klinik.paketpemeriksaan', compact('pemeriksaan', 'paketterpilih'))->render();
    }

    public function getPaketPemeriksaanKlinik(Request $request) {
        $jenisPemeriksaans = new JenisPemeriksaan();

        $pemeriksaan_id = $request->pemeriksaan_id;
        $jenis_pemeriksaan_klinik_ids = $request->jenis_pemeriksaan_klinik_ids;
        $arrKlinikIds = explode(",", $request->jenis_pemeriksaan_klinik_ids);
        $jenis = $jenisPemeriksaans->getComboJenisLabPemeriksaan(1);

        $arrPemeriksaanKlinikIds = explode(",", $jenis_pemeriksaan_klinik_ids);

        $html = '';

        foreach($jenis as $val) {
            $arrParameter = explode(",", $val->arr_parameter_id);
            $harga = $this->getHargaPemeriksaanKlinik($arrParameter);

            $html .= '<div class="card component-card_3 col-sm-2">
                <div class="card-body">
                    <p class="card-user_occupation mb-1">'.$val->nama_pemeriksaan.'</p>';
            if (in_array($val->id, $arrPemeriksaanKlinikIds)) {
                $html .= '<input type="hidden" name="jenis_pemeriksaan_id[]" id="jenis_pemeriksaan_id'.$val->id.'" class="form-control" value='.$val->id.'>';
            } else {
                $html .= '<input type="hidden" name="jenis_pemeriksaan_id[]" id="jenis_pemeriksaan_id'.$val->id.'" class="form-control">';
            }

            $html .= '<input type="hidden" name="biaya[]" id="biaya'.$val->id.'" class="form-control" value="'.$harga.'">
                    <span class="badge badge-primary mt-0 mb-1" id="hargaPaketKlinik'.$val->id.'">Rp. '.number_format($harga, 0, ',', '.').'</span>
                    <span class="badge badge-success mt-0 mb-1" id="textTerpilih'.$val->id.'"></span>
                    <div class="btn-group" role="group" aria-label="Basic example">';
            if (!in_array($val->id, $arrKlinikIds)) {
            $html .= '  <button type="button" class="btn btn-sm btn-secondary" id="pilih'.$val->id.'" onclick="pilihPemeriksaanKlinik('.$val->id.')">Pilih</button>
                        <button type="button" class="btn btn-sm btn-danger" id="batal'.$val->id.'" onclick="batalPemeriksaanKlinik('.$val->id.')" style="display:none;">Batal</button>';
            } else {
            $html .= '  <button type="button" class="btn btn-sm btn-secondary" id="pilih'.$val->id.'" onclick="pilihPemeriksaanKlinik('.$val->id.')" style="display:none;">Pilih</button>
                        <button type="button" class="btn btn-sm btn-danger" id="batal'.$val->id.'" onclick="batalPemeriksaanKlinik('.$val->id.')">Batal</button>';
            }

            $html .= '<button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#mdlDetailPemeriksaanKlinik" onclick="getDetailPemeriksaanKlinik('.$val->id.',\''.$val->arr_parameter_id.'\')">
                            Detail
                        </button>
                    </div>
                </div>
            </div>';
        }
        return response()->json($html);
    }

    public function getHargaPemeriksaanKlinik($arrParameter) {
        $harga = DB::table('parameter_pemeriksaan_klinik')
            ->whereIn('id', $arrParameter)
            ->sum('harga');

        return $harga;
    }

    public function hitungBiayaPaketPemeriksaan(Request $request) {
        $pemeriksaan = new PemeriksaanTbl();
        $total_biaya = str_ireplace('.', '', $request->total_biaya);
        $biaya = str_ireplace('.', '', $request->biaya);
        $status = $request->status;
        $jenis_lab_id = $request->jenis_lab_id;
        $paket_pemeriksaan_id = $request->paket_pemeriksaan_id;

        if ($status == 'pilih') {
            TempPaketPemeriksaanTbl::create([
                "user_id" => Auth::id(),
                "paket_pemeriksaan_id" => $paket_pemeriksaan_id,
                "biaya" => $biaya 
            ]);
        } else {
            DB::table('temp_paket_pemeriksaan')->where([
                ['user_id', '=', Auth::id()],
                ['paket_pemeriksaan_id', '=', $paket_pemeriksaan_id]
             ])->delete();
        }

        $paket = DB::table('temp_paket_pemeriksaan')->where('user_id', '=', Auth::id())->get();
        $parameter = DB::table('temp_parameter_pemeriksaan')->where('user_id', '=', Auth::id())->get();
        $arrpaket = [];
        $arrparameter = [];

        foreach ($paket as $val) {
            array_push($arrpaket, $val->paket_pemeriksaan_id);
        }

        foreach ($parameter as $val) {
            array_push($arrparameter, $val->parameter_id);
        }

        $total_paket = $pemeriksaan->getHargaPaketPemeriksaan($jenis_lab_id, $arrpaket);
        $total_parameter = $pemeriksaan->getHargaParameterPemeriksaan($jenis_lab_id, $arrparameter);
        $total_biaya = $total_paket + $total_parameter;

        $data['total_biaya'] = number_format($total_biaya, 0, ',', '.');
        $data['paket'] = $arrpaket;
        $data['parameter'] = $arrparameter;
        $data['total_paket'] = $total_paket;
        $data['total_parameter'] = $total_parameter;

        return response()->json($data);
    }

    public function hitungBiayaParameterPemeriksaan(Request $request) {
        $pemeriksaan = new PemeriksaanTbl();
        $total_biaya = str_ireplace('.', '', $request->total_biaya);
        $biaya = str_ireplace('.', '', $request->biaya);
        $status = $request->status;
        $jenis_lab_id = $request->jenis_lab_id;
        $id = $request->id;

        if ($status == 'pilih') {
            TempParameterPemeriksaanTbl::create([
                "user_id" => Auth::id(),
                "parameter_id" => $id,
                "biaya" => $biaya
            ]);
        } else {
            DB::table('temp_parameter_pemeriksaan')->where([
                ['user_id', '=', Auth::id()],
                ['parameter_id', '=', $id]
             ])->delete();
        }

        $paket = DB::table('temp_paket_pemeriksaan')->where('user_id', '=', Auth::id())->get();
        $parameter = DB::table('temp_parameter_pemeriksaan')->where('user_id', '=', Auth::id())->get();
        $arrpaket = [];
        $arrparameter = [];

        foreach ($paket as $val) {
            array_push($arrpaket, $val->paket_pemeriksaan_id);
        }

        foreach ($parameter as $val) {
            array_push($arrparameter, $val->parameter_id);
        }

        $total_paket = $pemeriksaan->getHargaPaketPemeriksaan($jenis_lab_id, $arrpaket);
        $total_parameter = $pemeriksaan->getHargaParameterPemeriksaan($jenis_lab_id, $arrparameter);
        $total_biaya = $total_paket + $total_parameter;

        $data['total_biaya'] = number_format($total_biaya, 0, ',', '.');
        $data['paket'] = $arrpaket;
        $data['parameter'] = $arrparameter;
        $data['total_paket'] = $total_paket;
        $data['total_parameter'] = $total_parameter;

        return response()->json($data);
    }

    public function hitungBiayaPaketPemeriksaanKimia(Request $request) {
        $pemeriksaan = new PemeriksaanTbl();
        $total_biaya = str_ireplace('.', '', $request->total_biaya);
        $biaya = str_ireplace('.', '', $request->biaya);
        $status = $request->status;
        $jenis_lab_id = $request->jenis_lab_id;
        $jmlh_sampel = $request->jmlh_sampel;
        $paket_pemeriksaan_id = $request->paket_pemeriksaan_id;

        if ($status == 'pilih') {
            TempPaketPemeriksaanTbl::create([
                "user_id" => Auth::id(),
                "paket_pemeriksaan_id" => $paket_pemeriksaan_id,
                "biaya" => $biaya 
            ]);
        } else {
            DB::table('temp_paket_pemeriksaan')->where([
                ['user_id', '=', Auth::id()],
                ['paket_pemeriksaan_id', '=', $paket_pemeriksaan_id]
             ])->delete();
        }

        $paket = DB::table('temp_paket_pemeriksaan')->where('user_id', '=', Auth::id())->get();
        $parameter = DB::table('temp_parameter_pemeriksaan')->where('user_id', '=', Auth::id())->get();
        $arrpaket = [];
        $arrparameter = [];

        foreach ($paket as $val) {
            array_push($arrpaket, $val->paket_pemeriksaan_id);
        }

        foreach ($parameter as $val) {
            array_push($arrparameter, $val->parameter_id);
        }

        $total_paket = $pemeriksaan->getHargaPaketPemeriksaan($jenis_lab_id, $arrpaket);
        $total_parameter = $pemeriksaan->getHargaParameterPemeriksaan($jenis_lab_id, $arrparameter);
        $total_biaya = $total_paket + $total_parameter;

        $data['total_biaya'] = number_format($total_biaya * $jmlh_sampel, 0, ',', '.');
        $data['paket'] = $arrpaket;
        $data['parameter'] = $arrparameter;
        $data['total_paket'] = $total_paket;
        $data['total_parameter'] = $total_parameter;

        return response()->json($data);
    }

    public function hitungBiayaParameterPemeriksaanKimia(Request $request) {
        $pemeriksaan = new PemeriksaanTbl();
        $total_biaya = str_ireplace('.', '', $request->total_biaya);
        $biaya = str_ireplace('.', '', $request->biaya);
        $status = $request->status;
        $jenis_lab_id = $request->jenis_lab_id;
        $jmlh_sampel = $request->jmlh_sampel;
        $id = $request->id;

        if ($status == 'pilih') {
            TempParameterPemeriksaanTbl::create([
                "user_id" => Auth::id(),
                "parameter_id" => $id,
                "biaya" => $biaya
            ]);
        } else {
            DB::table('temp_parameter_pemeriksaan')->where([
                ['user_id', '=', Auth::id()],
                ['parameter_id', '=', $id]
             ])->delete();
        }

        $paket = DB::table('temp_paket_pemeriksaan')->where('user_id', '=', Auth::id())->get();
        $parameter = DB::table('temp_parameter_pemeriksaan')->where('user_id', '=', Auth::id())->get();
        $arrpaket = [];
        $arrparameter = [];

        foreach ($paket as $val) {
            array_push($arrpaket, $val->paket_pemeriksaan_id);
        }

        foreach ($parameter as $val) {
            array_push($arrparameter, $val->parameter_id);
        }

        $total_paket = $pemeriksaan->getHargaPaketPemeriksaan($jenis_lab_id, $arrpaket);
        $total_parameter = $pemeriksaan->getHargaParameterPemeriksaan($jenis_lab_id, $arrparameter);
        $total_biaya = $total_paket + $total_parameter;

        $data['total_biaya'] = number_format($total_biaya * $jmlh_sampel, 0, ',', '.');
        $data['paket'] = $arrpaket;
        $data['parameter'] = $arrparameter;
        $data['total_paket'] = $total_paket;
        $data['total_parameter'] = $total_parameter;

        return response()->json($data);
    }

    public function getDetailTotalBiaya(Request $request) {
        $pemeriksaan = new PemeriksaanTbl();
        $jenis_lab_id = $request->jenis_lab_id;

        $paket = DB::table('temp_paket_pemeriksaan')->where('user_id', '=', Auth::id())->get();
        $parameter = DB::table('temp_parameter_pemeriksaan')->where('user_id', '=', Auth::id())->get();
        $arrpaket = [];
        $arrparameter = [];

        foreach ($paket as $val) {
            array_push($arrpaket, $val->paket_pemeriksaan_id);
        }

        foreach ($parameter as $val) {
            array_push($arrparameter, $val->parameter_id);
        }

        $datapaket = $pemeriksaan->getDataPaketPemeriksaan($jenis_lab_id, $arrpaket);
        $dataparameter = $pemeriksaan->getDataParameterPemeriksaan($jenis_lab_id, $arrparameter);

        $html = '';
        $sumtotal = 0;

        foreach ($datapaket as $valpaket) {
            $html .= '<tr>';
            $html .= '<td>'.$valpaket->nama_pemeriksaan.'</td>';
            $html .= '<td style="text-align:right;">'. number_format($valpaket->sumtotal, 0, ',', '.').'</td>';
            $html .= '<tr>';
            $sumtotal += str_ireplace(".", "", $valpaket->sumtotal);
        }

        foreach ($dataparameter as $valparameter) {
            $html .= '<tr>';
            $html .= '<td>'.$valparameter->nama_pemeriksaan.'</td>';
            $html .= '<td style="text-align:right;">'. number_format($valparameter->sumtotal, 0, ',', '.').'</td>';
            $html .= '<tr>';
            $sumtotal += str_ireplace(".", "", $valparameter->sumtotal);
        }

        $html .= '<tr>';
        $html .= '<th>Total</th>';
        $html .= '<th style="text-align:right;">'. number_format($sumtotal, 0, ',', '.').'</th>';
        $html .= '<tr>';

        $data['html'] = $html;

        return response()->json($data);
    }

    public function getDetailTotalBiayaKimiaMikro(Request $request) {
        $pemeriksaan = new PemeriksaanTbl();
        $jenis_lab_id = $request->jenis_lab_id;
        $jmlh_sampel = $request->jmlh_sampel;

        $paket = DB::table('temp_paket_pemeriksaan')->where('user_id', '=', Auth::id())->get();
        $parameter = DB::table('temp_parameter_pemeriksaan')->where('user_id', '=', Auth::id())->get();
        $arrpaket = [];
        $arrparameter = [];

        foreach ($paket as $val) {
            array_push($arrpaket, $val->paket_pemeriksaan_id);
        }

        foreach ($parameter as $val) {
            array_push($arrparameter, $val->parameter_id);
        }
        $datapaket = $pemeriksaan->getDataPaketPemeriksaan($jenis_lab_id, $arrpaket);
        $dataparameter = $pemeriksaan->getDataParameterPemeriksaan($jenis_lab_id, $arrparameter);

        $html = '';
        $sumtotal = 0;

        foreach ($datapaket as $valpaket) {
            $html .= '<tr>';
            $html .= '<td>'.$valpaket->nama_pemeriksaan.'</td>';
            $html .= '<td style="text-align:right;">'. number_format($valpaket->sumtotal, 0, ',', '.').'</td>';
            $html .= '<tr>';
            $sumtotal += str_ireplace(".", "", $valpaket->sumtotal);
        }

        foreach ($dataparameter as $valparameter) {
            $html .= '<tr>';
            $html .= '<td>'.$valparameter->nama_pemeriksaan.'</td>';
            $html .= '<td style="text-align:right;">'. number_format($valparameter->sumtotal, 0, ',', '.').'</td>';
            $html .= '<tr>';
            $sumtotal += str_ireplace(".", "", $valparameter->sumtotal);
        }

        $html .= '<tr>';
        $html .= '<th>Subtotal</th>';
        $html .= '<th style="text-align:right;">'. number_format($sumtotal, 0, ',', '.').'</th>';
        $html .= '<tr>';
        $html .= '<tr>';
        $html .= '<th>Jumlah Sampel</th>';
        $html .= '<th style="text-align:right;">'. number_format($jmlh_sampel, 0, ',', '.').'</th>';
        $html .= '<tr>';
        $html .= '<tr>';
        $html .= '<th>Total ( Subtotal x Jumlah Sampel )</th>';
        $html .= '<th style="text-align:right;">'. number_format($sumtotal * $jmlh_sampel, 0, ',', '.').'</th>';
        $html .= '<tr>';

        $data['html'] = $html;

        return response()->json($data);
    }

    public function getDetailPemeriksaan(Request $request) {
        $pemeriksaan_id = $request->id;
        $jmlh_sampel = $request->jmlh_sampel;
        $jenis_lab_id = $request->jenis_lab_id;
        $user_id = $request->user_id;

        $pemeriksaan = DB::table('view_pemeriksaan')->where('id', $pemeriksaan_id)->first();
        $detail_pemeriksaan = DB::table('detail_pemeriksaan')->where('pemeriksaan_id', $pemeriksaan_id)->get();

        $total = 0;
        $subtotal = 0;
        $html = '';
        $i = 1;

        foreach ($detail_pemeriksaan as $val) {
            $html .= '<tr>';
            $html .= '<td class="text-center">'.$i++.'</td>';
            $html .= '<td>'.$val->nama_pemeriksaan.'</td>';
            $html .= '<td style="text-align:right;">'. number_format($val->harga, 0, ',', '.').'</td>';
            $html .= '<tr>';
            $total += str_ireplace(".", "", $val->harga);
        }

        if ($jenis_lab_id == 1) {
            $html .= '<tr>';
            $html .= '<th colspan="2">Total</th>';
            $html .= '<th style="text-align:right;">'. number_format($total, 0, ',', '.').'</th>';
            $html .= '<tr>';
        } elseif ($jenis_lab_id == 2) {
            $detail_kimia = DB::table('detail_pemeriksaan_kimia')
                ->leftJoin('kemasan_sampel', 'detail_pemeriksaan_kimia.kemasan_sampel_id', 'kemasan_sampel.id')
                ->leftJoin('jenis_sampel', 'detail_pemeriksaan_kimia.jenis_sampel_id', 'jenis_sampel.id')
                ->select('detail_pemeriksaan_kimia.volume', 'detail_pemeriksaan_kimia.jmlh_sampel', 'detail_pemeriksaan_kimia.lokasi_sampel', 'kemasan_sampel.nama_kemasan', 'jenis_sampel.nama_sampel')
                ->where('pemeriksaan_id', $pemeriksaan_id)->first();
            $jmlh_sampel = $detail_kimia->jmlh_sampel;
            $nama_sampel = $detail_kimia->nama_sampel;

            $html .= '<tr>';
            $html .= '<th colspan="2">Subtotal</th>';
            $html .= '<th style="text-align:right;">'. number_format($total, 0, ',', '.').'</th>';
            $html .= '<tr>';
            $html .= '<tr>';
            $html .= '<th colspan="2">Jumlah Sampel</th>';
            $html .= '<th style="text-align:right;">'. number_format($jmlh_sampel, 0, ',', '.').'</th>';
            $html .= '<tr>';
            $html .= '<tr>';
            $html .= '<th colspan="2">Total ( Subtotal x Jumlah Sampel )</th>';
            $html .= '<th style="text-align:right;">'. number_format($total * $jmlh_sampel, 0, ',', '.').'</th>';
            $html .= '<tr>';
        } elseif ($jenis_lab_id == 3) {
            $detail_kimia = DB::table('detail_pemeriksaan_mikrobiologi')
                ->leftJoin('kemasan_sampel', 'detail_pemeriksaan_mikrobiologi.kemasan_sampel_id', 'kemasan_sampel.id')
                ->leftJoin('jenis_sampel', 'detail_pemeriksaan_mikrobiologi.jenis_sampel_id', 'jenis_sampel.id')
                ->select('detail_pemeriksaan_mikrobiologi.volume', 'detail_pemeriksaan_mikrobiologi.jmlh_sampel', 'detail_pemeriksaan_mikrobiologi.lokasi_sampel', 'kemasan_sampel.nama_kemasan', 'jenis_sampel.nama_sampel')
                ->where('pemeriksaan_id', $pemeriksaan_id)->first();
            $jmlh_sampel = $detail_kimia->jmlh_sampel;
            $nama_sampel = $detail_kimia->nama_sampel;
            $nama = Auth::user()->nama_lengkap;

            $html .= '<tr>';
            $html .= '<th colspan="2">Subtotal</th>';
            $html .= '<th style="text-align:right;">'. number_format($total, 0, ',', '.').'</th>';
            $html .= '<tr>';
            $html .= '<tr>';
            $html .= '<th colspan="2">Jumlah Sampel</th>';
            $html .= '<th style="text-align:right;">'. number_format($jmlh_sampel, 0, ',', '.').'</th>';
            $html .= '<tr>';
            $html .= '<tr>';
            $html .= '<th colspan="2">Total ( Subtotal x Jumlah Sampel )</th>';
            $html .= '<th style="text-align:right;">'. number_format($total * $jmlh_sampel, 0, ',', '.').'</th>';
            $html .= '<tr>';
        }

        $data['html'] = $html;
        $data['nama_lengkap'] = $pemeriksaan->nama_lengkap;

        return response()->json($data);
    }

    public function gridKimia() {
        $data = DB::table('view_pemeriksaan')
            ->select('view_pemeriksaan.*', 'detail_pemeriksaan_kimia.volume', 'detail_pemeriksaan_kimia.jmlh_sampel', 'detail_pemeriksaan_kimia.lokasi_sampel', 'kemasan_sampel.nama_kemasan', 'jenis_sampel.nama_sampel')
            ->leftJoin('detail_pemeriksaan_kimia', 'view_pemeriksaan.id', 'detail_pemeriksaan_kimia.pemeriksaan_id')
            ->leftJoin('kemasan_sampel', 'detail_pemeriksaan_kimia.kemasan_sampel_id', 'kemasan_sampel.id')
            ->leftJoin('jenis_sampel', 'detail_pemeriksaan_kimia.jenis_sampel_id', 'jenis_sampel.id')
            ->where('user_id', Auth::user()->id)->where('jenis_lab_id', 2)
            ->paginate(15, ['*'], 'pagegridpemeriksaan');

        return view('registrasi.kimia.index', compact('data'));
    }

    public function createKimia() {
        $jenisPemeriksaans = new JenisPemeriksaan();
        $pemeriksaan = new PemeriksaanTbl();

        DB::table('temp_paket_pemeriksaan')->where('user_id', Auth::id())->delete();
        DB::table('temp_parameter_pemeriksaan')->where('user_id', Auth::id())->delete();

        $jenis = $jenisPemeriksaans->getComboJenisLabPemeriksaan(2);
        $jenisSampel = $jenisPemeriksaans->getComboJenisSample(2);
        $kemasan = $jenisPemeriksaans->getComboKemasan(2);
        $pemeriksaan = $pemeriksaan->getPaketPemeriksaan(2);
        $kimia = DB::table('parameter_pemeriksaan_kimia')->where('status', 1)->paginate(16, ['*'], 'pageparameterpemeriksaan');
        $paketterpilih = [];
        $parameterterpilih = [];

        return view('registrasi.kimia.create', compact('jenis', 'jenisSampel', 'kemasan', 'pemeriksaan', 'kimia', 'paketterpilih', 'parameterterpilih'));
    }

    public function paginationParameterPemeriksaanKimia(Request $request) {
        $pemeriksaan = new PemeriksaanTbl();
        $parameter = DB::table('temp_parameter_pemeriksaan')->where('user_id', '=', Auth::id())->get();
        $parameterterpilih = [];

        foreach ($parameter as $val) {
            array_push($parameterterpilih, $val->parameter_id);
        }

        $kimia = DB::table('parameter_pemeriksaan_kimia')->where('status', 1)->paginate(16, ['*'], 'pageparameterpemeriksaan');

        return view('registrasi.kimia.parameterpemeriksaan', compact('kimia', 'parameterterpilih'))->render();
    }


    public function paginationPaketPemeriksaanKimia(Request $request) {
        $pemeriksaan = new PemeriksaanTbl();
        $paket = DB::table('temp_paket_pemeriksaan')->where('user_id', '=', Auth::id())->get();
        $paketterpilih = [];

        foreach ($paket as $val) {
            array_push($paketterpilih, $val->paket_pemeriksaan_id);
        }

        $pemeriksaan = $pemeriksaan->getPaketPemeriksaan(2);

        return view('registrasi.kimia.paketpemeriksaan', compact('pemeriksaan', 'paketterpilih'))->render();
    }

    public function paginationParameterPemeriksaanMikrobiologi(Request $request) {
        $pemeriksaan = new PemeriksaanTbl();
        $parameter = DB::table('temp_parameter_pemeriksaan')->where('user_id', '=', Auth::id())->get();
        $parameterterpilih = [];

        foreach ($parameter as $val) {
            array_push($parameterterpilih, $val->parameter_id);
        }

        $mikrobiologi = DB::table('parameter_pemeriksaan_mikrobiologi')->where('status', 1)->paginate(16, ['*'], 'pageparameterpemeriksaan');

        return view('registrasi.mikrobiologi.parameterpemeriksaan', compact('mikrobiologi', 'parameterterpilih'))->render();
    }


    public function paginationPaketPemeriksaanMikrobiologi(Request $request) {
        $pemeriksaan = new PemeriksaanTbl();
        $paket = DB::table('temp_paket_pemeriksaan')->where('user_id', '=', Auth::id())->get();
        $paketterpilih = [];

        foreach ($paket as $val) {
            array_push($paketterpilih, $val->paket_pemeriksaan_id);
        }

        $pemeriksaan = $pemeriksaan->getPaketPemeriksaan(3);

        return view('registrasi.mikrobiologi.paketpemeriksaan', compact('pemeriksaan', 'paketterpilih'))->render();
    }

    public function editKimia($pemeriksaan_id) {
        $jenisPemeriksaan = new JenisPemeriksaan();
        $pemeriksaantbl = new PemeriksaanTbl();

        DB::table('temp_paket_pemeriksaan')->where('user_id', Auth::id())->delete();
        DB::table('temp_parameter_pemeriksaan')->where('user_id', Auth::id())->delete();

        $pemeriksaan = $pemeriksaantbl->getPaketPemeriksaan(2);
        $jenis = $jenisPemeriksaan->getComboJenisLabPemeriksaan(2);
        $jenisSampel = $jenisPemeriksaan->getComboJenisSample(2);
        $kemasan = $jenisPemeriksaan->getComboKemasan(2);
        $data = DB::table('view_pemeriksaan')
            ->select('view_pemeriksaan.*', 'detail_pemeriksaan_kimia.volume', 'detail_pemeriksaan_kimia.jmlh_sampel', 'detail_pemeriksaan_kimia.lokasi_sampel', 'detail_pemeriksaan_kimia.jenis_sampel_id', 'detail_pemeriksaan_kimia.kemasan_sampel_id', 'kemasan_sampel.nama_kemasan', 'jenis_sampel.nama_sampel')
            ->leftJoin('detail_pemeriksaan_kimia', 'view_pemeriksaan.id', 'detail_pemeriksaan_kimia.pemeriksaan_id')
            ->leftJoin('kemasan_sampel', 'detail_pemeriksaan_kimia.kemasan_sampel_id', 'kemasan_sampel.id')
            ->leftJoin('jenis_sampel', 'detail_pemeriksaan_kimia.jenis_sampel_id', 'jenis_sampel.id')
            ->where('user_id', Auth::id())->where([['jenis_lab_id', 2], ['view_pemeriksaan.id', $pemeriksaan_id]])->first();

        $detailPemeriksaan = DB::table('detail_pemeriksaan')->where('pemeriksaan_id', $pemeriksaan_id)->get();

        foreach ($detailPemeriksaan as $val) {
            if ($val->jenis_pemeriksaan == 1) {
                TempPaketPemeriksaanTbl::create([
                    "user_id" => Auth::id(),
                    "paket_pemeriksaan_id" => $val->paket_parameter_id,
                    "biaya" => $val->harga
                ]);
            } else {
                TempParameterPemeriksaanTbl::create([
                    "user_id" => Auth::id(),
                    "parameter_id" => $val->paket_parameter_id,
                    "biaya" => $val->harga
                ]);
            }
        }

        $paket = DB::table('temp_paket_pemeriksaan')->where('user_id', '=', Auth::id())->get();
        $parameter = DB::table('temp_parameter_pemeriksaan')->where('user_id', '=', Auth::id())->get();
        $arrpaket = [];
        $arrparameter = [];

        foreach ($paket as $val) {
            array_push($arrpaket, $val->paket_pemeriksaan_id);
        }

        foreach ($parameter as $val) {
            array_push($arrparameter, $val->parameter_id);
        }

        $kimia = DB::table('parameter_pemeriksaan_kimia')->where('status', 1)->paginate(16, ['*'], 'pageparameterpemeriksaan');
        $paketterpilih = $arrpaket;
        $parameterterpilih = $arrparameter;

        return view('registrasi.kimia.edit', compact('jenis', 'jenisSampel', 'kemasan', 'pemeriksaan', 'kimia', 'paketterpilih', 'parameterterpilih', 'data'));
    }

    public function getDataJenisPemeriksaan(Request $request) {
        $jenisPemeriksaans = new JenisPemeriksaan();

        $arrParameterIds = explode(',', $request->parameter_ids);
        $pemeriksaan_id = $request->pemeriksaan_id;

        $jenis = $jenisPemeriksaans->getJenisPemeriksaan($request->jenis_pemeriksaan_id, $request->jenis_lab_id);

        if ($jenis) {
            $arrParameterWajib = explode(",", $jenis->arr_parameter_id);
            $arrParameterTambahan = explode(",", $jenis->arr_parameter_tambahan_id);
            $arrParameterPemeriksaan = explode(",", $jenis->arr_parameter_id.','.$jenis->arr_parameter_tambahan_id);

            if ($request->jenis_lab_id == 1) {
                $parameterPemeriksaan = $jenisPemeriksaans->getParameterDarah($request->jenis_lab_id, $request->jenis_pemeriksaan_id);
            }
            if ($request->jenis_lab_id == 2) {
                $parameterPemeriksaan = $jenisPemeriksaans->getParameterKimia($arrParameterPemeriksaan);
            }
            if ($request->jenis_lab_id == 3) {
                $parameterPemeriksaan = $jenisPemeriksaans->getParameterMikrobiologi($arrParameterPemeriksaan);
            }
        }

        $html = '';
        $count = 1;
        $biaya = 0;
        $html = '
        <fieldset class="border p-2">
            <legend class="w-auto">Parameter Pemeriksaan</legend>
            <table class="table-striped" cellpadding = "0">';
        foreach($parameterPemeriksaan as $val) {
            if ($count % 4 == 1) {
                $html .= '<tr>';
            }

            if ($count % 4 != 0) {

                if ($jenis) {
                    if (in_array($val->id, $arrParameterWajib)) {
                        if ($pemeriksaan_id) {
                            if (in_array($val->id, $arrParameterIds)) {
                                $html .= '<td style="vertical-align:top;padding-bottom:15px;"><input type="checkbox" class="form-controll" name="parameter[]" value="'.$val->id.'" checked onchange="hitungpemeriksaankimiamikro('.$val->harga.',this);"></td>';

                                $biaya += $val->harga;
                            } else {
                                $html .= '<td style="vertical-align:top;padding-bottom:15px;"><input type="checkbox" class="form-controll" name="parameter[]" value="'.$val->id.'" onchange="hitungpemeriksaankimiamikro('.$val->harga.',this);"></td>';
                            }
                        } else {
                            $html .= '<td style="vertical-align:top;padding-bottom:15px;"><input type="checkbox" class="form-controll" name="parameter[]" value="'.$val->id.'" checked onchange="hitungpemeriksaankimiamikro('.$val->harga.',this);"></td>';

                            $biaya += $val->harga;
                        }
                    } elseif (in_array($val->id, $arrParameterTambahan)) {
                        if ($pemeriksaan_id) {
                            if (in_array($val->id, $arrParameterIds)) {
                                $html .= '<td style="vertical-align:top;padding-bottom:15px;"><input type="checkbox" class="form-controll" name="parameter[]" value="'.$val->id.'" checked onchange="hitungpemeriksaankimiamikro('.$val->harga.',this);"></td>';

                                $biaya += $val->harga;
                            } else {
                                $html .= '<td style="vertical-align:top;padding-bottom:15px;"><input type="checkbox" class="form-controll" name="parameter[]" value="'.$val->id.'" onchange="hitungpemeriksaankimiamikro('.$val->harga.',this);"></td>';
                            }
                        } else {
                            $html .= '<td style="vertical-align:top;padding-bottom:15px;"><input type="checkbox" class="form-controll" name="parameter[]" value="'.$val->id.'" onchange="hitungpemeriksaankimiamikro('.$val->harga.',this);"></td>';
                        }
                    }
                    $html .= '<td style="vertical-align:top;padding-bottom:15px;"><label class="control-label" style="font-size:13px;"> &nbsp;&nbsp;'.$val->nama_parameter.' - '.number_format($val->harga, '0', ',', '.').'</label></td>';
                }
                // else {
                //     $html .= '<td style="vertical-align:top;padding-bottom:15px;"><input type="checkbox" class="form-controll" name="parameter[]" value="'.$val->id.'" onchange="hitungpemeriksaankimiamikro('.$val->harga.',this);"></td>';
                //     $html .= '<td style="vertical-align:top;padding-bottom:15px;"><label class="control-label" style="font-size:13px;"> &nbsp;&nbsp;'.$val->nama_parameter.' - '.number_format($val->harga, '0', ',', '.').'</label></td>';
                // }
            }

            if ($count % 4 == 0) {
                if ($jenis) {
                    if (in_array($val->id, $arrParameterWajib)) {
                        if ($pemeriksaan_id) {
                            if (in_array($val->id, $arrParameterIds)) {
                                $html .= '<td style="vertical-align:top;padding-bottom:15px;"><input type="checkbox" class="form-controll" name="parameter[]" value="'.$val->id.'" checked onchange="hitungpemeriksaankimiamikro('.$val->harga.',this);"></td>';

                                $biaya += $val->harga;
                            } else {
                                $html .= '<td style="vertical-align:top;padding-bottom:15px;"><input type="checkbox" class="form-controll" name="parameter[]" value="'.$val->id.'" onchange="hitungpemeriksaankimiamikro('.$val->harga.',this);"></td>';
                            }
                        } else {
                            $html .= '<td style="vertical-align:top;padding-bottom:15px;"><input type="checkbox" class="form-controll" name="parameter[]" value="'.$val->id.'" checked onchange="hitungpemeriksaankimiamikro('.$val->harga.',this);"></td>';

                            $biaya += $val->harga;
                        }
                    } elseif (in_array($val->id, $arrParameterTambahan)) {
                        if ($pemeriksaan_id) {
                            if (in_array($val->id, $arrParameterIds)) {
                                $html .= '<td style="vertical-align:top;padding-bottom:15px;"><input type="checkbox" class="form-controll" name="parameter[]" value="'.$val->id.'" checked onchange="hitungpemeriksaankimiamikro('.$val->harga.',this);"></td>';

                                $biaya += $val->harga;
                            } else {
                                $html .= '<td style="vertical-align:top;padding-bottom:15px;"><input type="checkbox" class="form-controll" name="parameter[]" value="'.$val->id.'" onchange="hitungpemeriksaankimiamikro('.$val->harga.',this);"></td>';
                            }
                        } else {
                            $html .= '<td style="vertical-align:top;padding-bottom:15px;"><input type="checkbox" class="form-controll" name="parameter[]" value="'.$val->id.'" onchange="hitungpemeriksaankimiamikro('.$val->harga.',this);"></td>';
                        }
                    }
                    $html .= '<td style="vertical-align:top;padding-bottom:15px;"><label class="control-label" style="font-size:13px;"> &nbsp;&nbsp;'.$val->nama_parameter.' - '.number_format($val->harga, '0', ',', '.').'</label></td>';
                }
                // else {
                //     $html .= '<td style="vertical-align:top;padding-bottom:15px;"><input type="checkbox" class="form-controll" name="parameter[]" value="'.$val->id.'" onchange="hitungpemeriksaankimiamikro('.$val->harga.',this);"></td>';
                //     $html .= '<td style="vertical-align:top;padding-bottom:15px;"><label class="control-label" style="font-size:13px;"> &nbsp;&nbsp;'.$val->nama_parameter.' - '.number_format($val->harga, '0', ',', '.').'</label></td>';
                // }
                $html .= '</tr>';
            }
            $count ++;
        }
        $html .= '
            </table>
        </fieldset>';

        $data = array(
            'html' => $html,
            'biaya' => number_format($biaya, 0, ',', '.')
        );

        return response()->json($data);
    }

    public function gridMikrobiologi() {
        $data = DB::table('view_pemeriksaan')
            ->select('view_pemeriksaan.*', 'detail_pemeriksaan_mikrobiologi.volume', 'detail_pemeriksaan_mikrobiologi.jmlh_sampel', 'detail_pemeriksaan_mikrobiologi.lokasi_sampel', 'kemasan_sampel.nama_kemasan', 'jenis_sampel.nama_sampel')
            ->leftJoin('detail_pemeriksaan_mikrobiologi', 'view_pemeriksaan.id', 'detail_pemeriksaan_mikrobiologi.pemeriksaan_id')
            ->leftJoin('kemasan_sampel', 'detail_pemeriksaan_mikrobiologi.kemasan_sampel_id', 'kemasan_sampel.id')
            ->leftJoin('jenis_sampel', 'detail_pemeriksaan_mikrobiologi.jenis_sampel_id', 'jenis_sampel.id')
            ->where('user_id', Auth::user()->id)->where('jenis_lab_id', 3)
            ->paginate(15, ['*'], 'pagegridpemeriksaan');

        return view('registrasi.mikrobiologi.index', compact('data'));
    }

    public function createMikrobiologi() {
        $jenisPemeriksaans = new JenisPemeriksaan();
        $pemeriksaan = new PemeriksaanTbl();

        DB::table('temp_paket_pemeriksaan')->where('user_id', Auth::id())->delete();
        DB::table('temp_parameter_pemeriksaan')->where('user_id', Auth::id())->delete();

        $jenis = $jenisPemeriksaans->getComboJenisLabPemeriksaan(3);
        $jenisSampel = $jenisPemeriksaans->getComboJenisSample(3);
        $kemasan = $jenisPemeriksaans->getComboKemasan(3);
        $pemeriksaan = $pemeriksaan->getPaketPemeriksaan(3);
        $mikrobiologi = DB::table('parameter_pemeriksaan_mikrobiologi')->where('status', 1)->paginate(16, ['*'], 'pageparameterpemeriksaan');
        $paketterpilih = [];
        $parameterterpilih = [];

        return view('registrasi.mikrobiologi.create', compact('jenis', 'jenisSampel', 'kemasan', 'pemeriksaan', 'mikrobiologi', 'paketterpilih', 'parameterterpilih'));

    }

    public function editMikrobiologi($pemeriksaan_id) {
        $jenisPemeriksaan = new JenisPemeriksaan();
        $pemeriksaantbl = new PemeriksaanTbl();

        DB::table('temp_paket_pemeriksaan')->where('user_id', Auth::id())->delete();
        DB::table('temp_parameter_pemeriksaan')->where('user_id', Auth::id())->delete();

        $pemeriksaan = $pemeriksaantbl->getPaketPemeriksaan(3);
        $jenis = $jenisPemeriksaan->getComboJenisLabPemeriksaan(3);
        $jenisSampel = $jenisPemeriksaan->getComboJenisSample(3);
        $kemasan = $jenisPemeriksaan->getComboKemasan(3);
        $data = DB::table('view_pemeriksaan')
            ->select('view_pemeriksaan.*', 'detail_pemeriksaan_mikrobiologi.volume', 'detail_pemeriksaan_mikrobiologi.jmlh_sampel', 'detail_pemeriksaan_mikrobiologi.lokasi_sampel', 'detail_pemeriksaan_mikrobiologi.jenis_sampel_id', 'detail_pemeriksaan_mikrobiologi.kemasan_sampel_id', 'kemasan_sampel.nama_kemasan', 'jenis_sampel.nama_sampel')
            ->leftJoin('detail_pemeriksaan_mikrobiologi', 'view_pemeriksaan.id', 'detail_pemeriksaan_mikrobiologi.pemeriksaan_id')
            ->leftJoin('kemasan_sampel', 'detail_pemeriksaan_mikrobiologi.kemasan_sampel_id', 'kemasan_sampel.id')
            ->leftJoin('jenis_sampel', 'detail_pemeriksaan_mikrobiologi.jenis_sampel_id', 'jenis_sampel.id')
            ->where('user_id', Auth::user()->id)->where([['jenis_lab_id', 3], ['view_pemeriksaan.id', $pemeriksaan_id]])->first();

        $detailPemeriksaan = DB::table('detail_pemeriksaan')->where('pemeriksaan_id', $pemeriksaan_id)->get();

        foreach ($detailPemeriksaan as $val) {
            if ($val->jenis_pemeriksaan == 1) {
                TempPaketPemeriksaanTbl::create([
                    "user_id" => Auth::id(),
                    "paket_pemeriksaan_id" => $val->paket_parameter_id,
                    "biaya" => $val->harga
                ]);
            } else {
                TempParameterPemeriksaanTbl::create([
                    "user_id" => Auth::id(),
                    "parameter_id" => $val->paket_parameter_id,
                    "biaya" => $val->harga
                ]);
            }
        }

        $paket = DB::table('temp_paket_pemeriksaan')->where('user_id', '=', Auth::id())->get();
        $parameter = DB::table('temp_parameter_pemeriksaan')->where('user_id', '=', Auth::id())->get();
        $arrpaket = [];
        $arrparameter = [];

        foreach ($paket as $val) {
            array_push($arrpaket, $val->paket_pemeriksaan_id);
        }

        foreach ($parameter as $val) {
            array_push($arrparameter, $val->parameter_id);
        }

        $mikrobiologi = DB::table('parameter_pemeriksaan_mikrobiologi')->where('status', 1)->paginate(16, ['*'], 'pageparameterpemeriksaan');
        $paketterpilih = $arrpaket;
        $parameterterpilih = $arrparameter;

        return view('registrasi.mikrobiologi.edit', compact('jenis', 'jenisSampel', 'kemasan', 'pemeriksaan', 'mikrobiologi', 'paketterpilih', 'parameterterpilih', 'data'));
    }

    public function gridBiomolekuler() {
        return view('registrasi.biomolekuler.index');
    }

    public function storeKimia(Request $request) {
        $user = Auth::user();

        DB::beginTransaction();

        $jenis_lab_id = $request->jenis_lab_id;
        $tgl_waktu_kunjungan = $request->tglwaktukunjungan;
        $total_biaya = str_ireplace(".", "", $request->total_biaya);
        $paket_pemeriksaan = DB::table('temp_paket_pemeriksaan')->where('user_id', Auth::id())->get();
        $parameter_pemeriksaan = DB::table('temp_parameter_pemeriksaan')->where('user_id', Auth::id())->get();
        $kemasan_sampel_id = $request->kemasan_sampel_id;
        $volume = $request->volume;
        $jmlh_sampel = $request->jmlh_sampel;
        $lokasi_sampel = $request->lokasi_sampel;
        $jenis_sampel_id = $request->jenis_sampel_id;

        $no_urut = PemeriksaanTbl::where(DB::raw('DATE_FORMAT(tgl_waktu_kunjungan,"%Y-%m-%d")'), date('Y-m-d', strtotime($tgl_waktu_kunjungan)))->max('no_urut');
        $nourut = $no_urut + 1;

        try {
            $pemeriksaan = PemeriksaanTbl::create([
                "jenis_lab_id" => $jenis_lab_id,
                "no_urut" => $nourut,
                "tgl_waktu_kunjungan" => date('Y-m-d H:i:s', strtotime($tgl_waktu_kunjungan)),
                "total_biaya" => str_ireplace(".", "", $total_biaya),
                "user_id" => $user->id,
                "status" => 1
            ]);

            $detailkimia = DetailKimiaMdl::create([
                "pemeriksaan_id" => $pemeriksaan->id,
                "jenis_sampel_id" => $jenis_sampel_id,
                "kemasan_sampel_id" => $kemasan_sampel_id,
                "volume" => $volume,
                "jmlh_sampel" => $jmlh_sampel,
                "lokasi_sampel" => $lokasi_sampel,
            ]);

            if ($paket_pemeriksaan) {
                foreach ($paket_pemeriksaan as $val) {
                    $paket = DB::table('paket_pemeriksaan')->where('id', $val->paket_pemeriksaan_id)->select('nama_pemeriksaan')->first();
                    $detailPemeriksaan = DetailPemeriksaanMdl::create([
                        "pemeriksaan_id" => $pemeriksaan->id,
                        "jenis_pemeriksaan" => 1,
                        "nama_pemeriksaan" => $paket->nama_pemeriksaan,
                        "paket_parameter_id" => $val->paket_pemeriksaan_id,
                        "harga" => $val->biaya
                    ]);
                }
            }

            if ($parameter_pemeriksaan) {
                foreach ($parameter_pemeriksaan as $val) {
                    $paket = DB::table('parameter_pemeriksaan_kimia')->where('id', $val->parameter_id)->select('nama_parameter')->first();
                    $detailPemeriksaan = DetailPemeriksaanMdl::create([
                        "pemeriksaan_id" => $pemeriksaan->id,
                        "jenis_pemeriksaan" => 2,
                        "nama_pemeriksaan" => $paket->nama_parameter,
                        "paket_parameter_id" => $val->parameter_id,
                        "harga" => $val->biaya
                    ]);
                }
            }

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Disimpan');

            return redirect::to('/registrasi/kimia');
        } catch (\Throwable $t) {
            dd($t);

            Alert::error('Gagal', 'Data Gagal Disimpan');

            DB::rollback();
            return redirect::to('/registrasi/kimia');
        }
    }

    public function updateKimia(Request $request) {
        $user = Auth::user();

        DB::beginTransaction();

        $pemeriksaan_id = $request->pemeriksaan_id;
        $jenis_lab_id = $request->jenis_lab_id;
        $tgl_waktu_kunjungan = $request->tglwaktukunjungan;
        $total_biaya = str_ireplace(".", "", $request->total_biaya);
        $paket_pemeriksaan = DB::table('temp_paket_pemeriksaan')->where('user_id', Auth::id())->get();
        $parameter_pemeriksaan = DB::table('temp_parameter_pemeriksaan')->where('user_id', Auth::id())->get();
        $kemasan_sampel_id = $request->kemasan_sampel_id;
        $volume = $request->volume;
        $jmlh_sampel = $request->jmlh_sampel;
        $lokasi_sampel = $request->lokasi_sampel;
        $jenis_sampel_id = $request->jenis_sampel_id;

        try {
            $uPemeriksaan = PemeriksaanTbl::where('id', $pemeriksaan_id)
                ->update([
                    "jenis_lab_id" => $jenis_lab_id,
                    "tgl_waktu_kunjungan" => date('Y-m-d H:i:s', strtotime($tgl_waktu_kunjungan)),
                    "total_biaya" => str_ireplace(".", "", $total_biaya),
                    "user_id" => $user->id,
                    "status" => 1
                ]);

            $detail_kimia = DB::table('detail_pemeriksaan_kimia')->where('pemeriksaan_id', $pemeriksaan_id)->first();

            $uDetailkimia = DetailKimiaMdl::where('id', $detail_kimia->id)
                ->update([
                    "pemeriksaan_id" => $pemeriksaan_id,
                    "jenis_sampel_id" => $jenis_sampel_id,
                    "kemasan_sampel_id" => $kemasan_sampel_id,
                    "volume" => $volume,
                    "jmlh_sampel" => $jmlh_sampel,
                    "lokasi_sampel" => $lokasi_sampel,
            ]);

            $detail_pemeriksaan = DB::table('detail_pemeriksaan')->where('pemeriksaan_id', $pemeriksaan_id)->get();

            foreach ($detail_pemeriksaan as $val) {
                DB::table('detail_pemeriksaan')->where('id', $val->id)->delete();
            }

            if ($paket_pemeriksaan) {
                foreach ($paket_pemeriksaan as $val) {
                    $paket = DB::table('paket_pemeriksaan')->where('id', $val->paket_pemeriksaan_id)->select('nama_pemeriksaan')->first();
                    $detailPemeriksaan = DetailPemeriksaanMdl::create([
                        "pemeriksaan_id" => $pemeriksaan_id,
                        "jenis_pemeriksaan" => 1,
                        "nama_pemeriksaan" => $paket->nama_pemeriksaan,
                        "paket_parameter_id" => $val->paket_pemeriksaan_id,
                        "harga" => $val->biaya
                    ]);
                }
            }

            if ($parameter_pemeriksaan) {
                foreach ($parameter_pemeriksaan as $val) {
                    $paket = DB::table('parameter_pemeriksaan_kimia')->where('id', $val->parameter_id)->select('nama_parameter')->first();
                    $detailPemeriksaan = DetailPemeriksaanMdl::create([
                        "pemeriksaan_id" => $pemeriksaan_id,
                        "jenis_pemeriksaan" => 2,
                        "nama_pemeriksaan" => $paket->nama_parameter,
                        "paket_parameter_id" => $val->parameter_id,
                        "harga" => $val->biaya
                    ]);
                }
            }

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Diupdate');

            return redirect::to('/registrasi/kimia');
        } catch (\Throwable $t) {
            dd($t);

            Alert::error('Gagal', 'Data Gagal Diupdate');

            DB::rollback();
            return redirect::to('/registrasi/kimia');
        }
    }

    public function storeMikrobiologi(Request $request) {
        $user = Auth::user();

        DB::beginTransaction();

        $jenis_lab_id = $request->jenis_lab_id;
        $tgl_waktu_kunjungan = $request->tglwaktukunjungan;
        $total_biaya = str_ireplace(".", "", $request->total_biaya);
        $paket_pemeriksaan = DB::table('temp_paket_pemeriksaan')->where('user_id', Auth::id())->get();
        $parameter_pemeriksaan = DB::table('temp_parameter_pemeriksaan')->where('user_id', Auth::id())->get();
        $kemasan_sampel_id = $request->kemasan_sampel_id;
        $volume = $request->volume;
        $jmlh_sampel = $request->jmlh_sampel;
        $lokasi_sampel = $request->lokasi_sampel;
        $jenis_sampel_id = $request->jenis_sampel_id;

        $no_urut = PemeriksaanTbl::where(DB::raw('DATE_FORMAT(tgl_waktu_kunjungan,"%Y-%m-%d")'), date('Y-m-d', strtotime($tgl_waktu_kunjungan)))->max('no_urut');
        $nourut = $no_urut + 1;

        try {
            $pemeriksaan = PemeriksaanTbl::create([
                "jenis_lab_id" => $jenis_lab_id,
                "no_urut" => $nourut,
                "tgl_waktu_kunjungan" => date('Y-m-d H:i:s', strtotime($tgl_waktu_kunjungan)),
                "total_biaya" => str_ireplace(".", "", $total_biaya),
                "user_id" => $user->id,
                "status" => 1
            ]);

            $detailmikrobiologi = DetailMikrobiologiMdl::create([
                "pemeriksaan_id" => $pemeriksaan->id,
                "jenis_sampel_id" => $jenis_sampel_id,
                "kemasan_sampel_id" => $kemasan_sampel_id,
                "volume" => $volume,
                "jmlh_sampel" => $jmlh_sampel,
                "lokasi_sampel" => $lokasi_sampel,
            ]);

            if ($paket_pemeriksaan) {
                foreach ($paket_pemeriksaan as $val) {
                    $paket = DB::table('paket_pemeriksaan')->where('id', $val->paket_pemeriksaan_id)->select('nama_pemeriksaan')->first();
                    $detailPemeriksaan = DetailPemeriksaanMdl::create([
                        "pemeriksaan_id" => $pemeriksaan->id,
                        "jenis_pemeriksaan" => 1,
                        "nama_pemeriksaan" => $paket->nama_pemeriksaan,
                        "paket_parameter_id" => $val->paket_pemeriksaan_id,
                        "harga" => $val->biaya
                    ]);
                }
            }

            if ($parameter_pemeriksaan) {
                foreach ($parameter_pemeriksaan as $val) {
                    $paket = DB::table('parameter_pemeriksaan_mikrobiologi')->where('id', $val->parameter_id)->select('nama_parameter')->first();
                    $detailPemeriksaan = DetailPemeriksaanMdl::create([
                        "pemeriksaan_id" => $pemeriksaan->id,
                        "jenis_pemeriksaan" => 2,
                        "nama_pemeriksaan" => $paket->nama_parameter,
                        "paket_parameter_id" => $val->parameter_id,
                        "harga" => $val->biaya
                    ]);
                }
            }

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Disimpan');

            return redirect::to('/registrasi/mikrobiologi');
        } catch (\Throwable $t) {
            dd($t);

            Alert::error('Gagal', 'Data Gagal Disimpan');

            DB::rollback();
            return redirect::to('/registrasi/mikrobiologi');
        }
    }

    public function updateMikrobiologi(Request $request) {
        $user = Auth::user();

        DB::beginTransaction();

        $pemeriksaan_id = $request->pemeriksaan_id;
        $jenis_lab_id = $request->jenis_lab_id;
        $tgl_waktu_kunjungan = $request->tglwaktukunjungan;
        $total_biaya = str_ireplace(".", "", $request->total_biaya);
        $paket_pemeriksaan = DB::table('temp_paket_pemeriksaan')->where('user_id', Auth::id())->get();
        $parameter_pemeriksaan = DB::table('temp_parameter_pemeriksaan')->where('user_id', Auth::id())->get();
        $kemasan_sampel_id = $request->kemasan_sampel_id;
        $volume = $request->volume;
        $jmlh_sampel = $request->jmlh_sampel;
        $lokasi_sampel = $request->lokasi_sampel;
        $jenis_sampel_id = $request->jenis_sampel_id;

        try {
            $uPemeriksaan = PemeriksaanTbl::where('id', $pemeriksaan_id)
                ->update([
                    "jenis_lab_id" => $jenis_lab_id,
                    "tgl_waktu_kunjungan" => date('Y-m-d H:i:s', strtotime($tgl_waktu_kunjungan)),
                    "total_biaya" => str_ireplace(".", "", $total_biaya),
                    "user_id" => $user->id,
                    "status" => 1
                ]);

            $detail_mikrobiologi = DB::table('detail_pemeriksaan_mikrobiologi')->where('pemeriksaan_id', $pemeriksaan_id)->first();

            $uDetailmikrobiologi = DetailMikrobiologiMdl::where('id', $detail_mikrobiologi->id)
                ->update([
                    "pemeriksaan_id" => $pemeriksaan_id,
                    "jenis_sampel_id" => $jenis_sampel_id,
                    "kemasan_sampel_id" => $kemasan_sampel_id,
                    "volume" => $volume,
                    "jmlh_sampel" => $jmlh_sampel,
                    "lokasi_sampel" => $lokasi_sampel,
            ]);

            $detail_pemeriksaan = DB::table('detail_pemeriksaan')->where('pemeriksaan_id', $pemeriksaan_id)->get();

            foreach ($detail_pemeriksaan as $val) {
                DB::table('detail_pemeriksaan')->where('id', $val->id)->delete();
            }

            if ($paket_pemeriksaan) {
                foreach ($paket_pemeriksaan as $val) {
                    $paket = DB::table('paket_pemeriksaan')->where('id', $val->paket_pemeriksaan_id)->select('nama_pemeriksaan')->first();
                    $detailPemeriksaan = DetailPemeriksaanMdl::create([
                        "pemeriksaan_id" => $pemeriksaan_id,
                        "jenis_pemeriksaan" => 1,
                        "nama_pemeriksaan" => $paket->nama_pemeriksaan,
                        "paket_parameter_id" => $val->paket_pemeriksaan_id,
                        "harga" => $val->biaya
                    ]);
                }
            }

            if ($parameter_pemeriksaan) {
                foreach ($parameter_pemeriksaan as $val) {
                    $paket = DB::table('parameter_pemeriksaan_mikrobiologi')->where('id', $val->parameter_id)->select('nama_parameter')->first();
                    $detailPemeriksaan = DetailPemeriksaanMdl::create([
                        "pemeriksaan_id" => $pemeriksaan_id,
                        "jenis_pemeriksaan" => 2,
                        "nama_pemeriksaan" => $paket->nama_parameter,
                        "paket_parameter_id" => $val->parameter_id,
                        "harga" => $val->biaya
                    ]);
                }
            }

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Diupdate');

            return redirect::to('/registrasi/mikrobiologi');
        } catch (\Throwable $t) {
            dd($t);

            Alert::error('Gagal', 'Data Gagal Diupdate');

            DB::rollback();
            return redirect::to('/registrasi/mikrobiologi');
        }
    }

    public function cetakpenerimaansampel()
    {
        $pdf = PDF::loadView('pencetakan.cetakpenerimaansampel')->setPaper('legal', 'landscape');

        return $pdf->stream("cetakpenerimaansampel.pdf", array("Attachment" => false));
    }

    public function cetakbuktiregistrasi($pemeriksaan_id)
    {
        $user = Auth::user();

        $cekpemeriksaan = DB::table('view_pemeriksaan')->where('id', $pemeriksaan_id)->first('jenis_lab_id');

        if ($cekpemeriksaan->jenis_lab_id == 1) {
            $data = DB::table('view_pemeriksaan')->where('id', $pemeriksaan_id)->first();
        } elseif ($cekpemeriksaan->jenis_lab_id == 2) {
            $data = DB::table('view_pemeriksaan')
                ->select('view_pemeriksaan.*', 'detail_pemeriksaan_kimia.volume', 'detail_pemeriksaan_kimia.jmlh_sampel', 'detail_pemeriksaan_kimia.lokasi_sampel', 'kemasan_sampel.nama_kemasan', 'jenis_sampel.nama_sampel')
                ->leftJoin('detail_pemeriksaan_kimia', 'view_pemeriksaan.id', 'detail_pemeriksaan_kimia.pemeriksaan_id')
                ->leftJoin('kemasan_sampel', 'detail_pemeriksaan_kimia.kemasan_sampel_id', 'kemasan_sampel.id')
                ->leftJoin('jenis_sampel', 'detail_pemeriksaan_kimia.jenis_sampel_id', 'jenis_sampel.id')->where('view_pemeriksaan.id', $pemeriksaan_id)->first();
        } elseif ($cekpemeriksaan->jenis_lab_id == 3) {
            $data = DB::table('view_pemeriksaan')
                ->select('view_pemeriksaan.*', 'detail_pemeriksaan_mikrobiologi.volume', 'detail_pemeriksaan_mikrobiologi.jmlh_sampel', 'detail_pemeriksaan_mikrobiologi.lokasi_sampel', 'kemasan_sampel.nama_kemasan', 'jenis_sampel.nama_sampel')
                ->leftJoin('detail_pemeriksaan_mikrobiologi', 'view_pemeriksaan.id', 'detail_pemeriksaan_mikrobiologi.pemeriksaan_id')
                ->leftJoin('kemasan_sampel', 'detail_pemeriksaan_mikrobiologi.kemasan_sampel_id', 'kemasan_sampel.id')
                ->leftJoin('jenis_sampel', 'detail_pemeriksaan_mikrobiologi.jenis_sampel_id', 'jenis_sampel.id')->where('view_pemeriksaan.id', $pemeriksaan_id)->first();
        }

        $dtPemeriksaan = DB::table('detail_pemeriksaan')->where('pemeriksaan_id', $pemeriksaan_id)->get();

        $qrcode = base64_encode(QrCode::format('svg')->size(90)->errorCorrection('H')->generate($data->no_registrasi.' -- '. date('d-m-Y H:i', strtotime($data->tgl_waktu_kunjungan))));

        $pdf = PDF::loadView('pencetakan.cetakbuktiregistrasi', compact('qrcode', 'data', 'dtPemeriksaan'))->setPaper('letter', 'portrait');

        return $pdf->stream("cetakbuktiregistrasi.pdf", array("Attachment" => false));
    }

    public function getJadwalPemeriksaan(Request $request)
    {
        $tglkunjungan = $request->tgl;

        $data = DB::table('pemeriksaan')
            ->select(DB::raw('COUNT(*) AS jmlh_kunjungan'))
            ->whereRaw("DATE_FORMAT(tgl_waktu_kunjungan, '%Y-%m-%d') = '".date('Y-m-d', strtotime($tglkunjungan))."'")
            ->first();

        $kuota = 3;

        $html = '';
        $count = 1;
        $html = '
        <fieldset class="border p-2">
            <legend class="w-auto">Pilih Jam Kunjungan</legend>
                <div class="col-md-12">
                <table class="table mb-2">
                    <tbody>';

        $time = 8;
        for ($i=0; $i < 9; $i++) {
            $jam = str_pad($time + $i, 2, '0', STR_PAD_LEFT).':00';
            $html .= "<tr>
                        <td>
                            <div class='input-group'>
                                <input type='button' class='form-control' value='$jam' aria-label='checkbox' aria-describedby='basic-addon1' name='jam' id='$i' readonly>
                                <div class='input-group-append'>
                                    <div class='input-group-text'>
                                        <div class='n-chk align-self-end'>
                                            <label class='new-control new-checkbox checkbox-primary' style='height: 21px; margin-bottom: 0; margin-right: 0'>
                                              <input type='checkbox' class='new-control-input' name='jam_kunjungan' value='$jam' id='check$i'>
                                              <span class='new-control-indicator'></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>";
        }

        $html .= "
                </tbody>
            </table>
            </div>
        </fieldset>
        <script>
            $('input[name=jam_kunjungan]').on('change', function() {
                $('input[name=jam_kunjungan]').not(this).prop('checked', false);
                var tgl_kunjungan = $('#tgl_kunjungan').val();
                var jam_kunjungan = $('input[name=jam_kunjungan]:checked').val();
                var jadwalKunjungan = tgl_kunjungan+' '+jam_kunjungan+':00';
                $('#tgl_waktu_kunjungan').val(jadwalKunjungan);
                document.getElementById('tglwaktukunjungan').value = jadwalKunjungan;
                setTimeout(
                function()
                {
                    $('#mdlJadwalKunjungan').modal('hide');
                }, 1000);
            });

            $('input[name=jam]').on('click', function() {
                var id = this.id;
                $('#check'+id).prop('checked', true);
                $('input[name=jam_kunjungan]').not('#check'+id).prop('checked', false);
                var tgl_kunjungan = $('#tgl_kunjungan').val();
                var jam_kunjungan = $('input[name=jam_kunjungan]:checked').val();
                var jadwalKunjungan = tgl_kunjungan+' '+jam_kunjungan+':00';
                console.log(jadwalKunjungan);
                $('#tgl_waktu_kunjungan').val(jadwalKunjungan);
                document.getElementById('tglwaktukunjungan').value = jadwalKunjungan;
                setTimeout(
                function()
                {
                    $('#mdlJadwalKunjungan').modal('hide');
                }, 1000);

            });
        </script>
        ";

        return response()->json($html);
    }

    public function updateStatusLab(Request $request) {
        $users = Auth::user();

        $id = $request->upload_pemeriksaan_id;
        $no_pelanggan_silkes = $request->no_pelanggan_silkes;
        $no_sampel_silkes = $request->no_sampel_silkes;
        $fileLabHasilPemeriksaan = $request->file('fileLabHasilPemeriksaan');
        $pathFileLabHasilPemeriksaan = $fileLabHasilPemeriksaan->store('public/hasil_lab');
        $flLabHasilPemeriksaan = explode('/', $pathFileLabHasilPemeriksaan);
        $fileLaboratoriumHasilPemeriksaan = $flLabHasilPemeriksaan[2];

        DB::beginTransaction();

        try {
            $uPemeriksaan = PemeriksaanTbl::where('id', $id)
                ->update([
                    "status" => 2,
                    "no_pelanggan_silkes" => $no_pelanggan_silkes,
                    "no_sampel_silkes" => $no_sampel_silkes,
                    "fileLaboratoriumHasilPemeriksaan" => $fileLaboratoriumHasilPemeriksaan
            ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Diupdate');

            return redirect::to('/registrasi/verifikasi');
        } catch (\Throwable $t) {
            dd($t);
            Alert::error('Gagal', 'Data Gagal Diupdate');

            DB::rollback();

            return redirect::to('/registrasi/verifikasi');
        }
    }

    public function hapusRegistrasi(Request $request)
    {
        $user = Auth::user();

        DB::beginTransaction();

        $pemeriksaan_id = $request->pemeriksaan_id;
        $lab_id = $request->lab_id;

        $lab = '';
        if ($lab_id == 1) {
            $lab = 'klinik';
        } elseif ($lab_id == 2) {
            $lab = 'kimia';
        } elseif ($lab_id == 3) {
            $lab = 'mikrobiologi';
        }

        try {
            DB::table('pemeriksaan')->where('id', $pemeriksaan_id)->delete();

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Dihapus');

            return redirect::to('registrasi/'.$lab);
        } catch (\Throwable $t) {
            Alert::error('Gagal', 'Data Gagal Dihapus');
            dd($t);
            DB::rollback();

            return redirect::to('registrasi/'.$lab);
        }
    }

    public function setujuRegistrasi(Request $request)
    {
        $user = Auth::user();

        DB::beginTransaction();

        $pemeriksaan_id = $request->pemeriksaan_id;

        try {
            $uPemeriksaan = PemeriksaanTbl::where('id', $pemeriksaan_id)
                ->update([
                    "status" => 2,
                ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Diverifikasi');

            return redirect::to('registrasi/verifikasi');
        } catch (\Throwable $t) {
            Alert::error('Gagal', 'Data Gagal Diverifikasi');
            dd($t);
            DB::rollback();

            return redirect::to('registrasi/verifikasi');
        }
    }

    public function batalRegistrasi(Request $request)
    {
        $user = Auth::user();

        DB::beginTransaction();

        $pemeriksaan_id = $request->batal_pemeriksaan_id;
        $keterangan = $request->keterangan;

        try {
            $uPemeriksaan = PemeriksaanTbl::where('id', $pemeriksaan_id)
                ->update([
                    "status" => 7,
                    "keterangan_batal" => $keterangan,
                    "operator_batal" => $user->id,
                    "tgl_pembatalan" => date('Y-m-d')
                ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Dibatalkan');

            return redirect::to('registrasi/verifikasi');
        } catch (\Throwable $t) {
            Alert::error('Gagal', 'Data Gagal Dibatalkan');
            dd($t);
            DB::rollback();

            return redirect::to('registrasi/verifikasi');
        }
    }

    public function getDataSampel(Request $request) {
        $user = Auth::user();
        $jenisSampel = new JenisSampel();
        $jenisPemeriksaans = new JenisPemeriksaan();

        $jenis_sampel_id = $request->jenis_sampel_id;
        $jenis_lab_id = $request->jenis_lab_id;

        $dataSampel = $jenisSampel->getDataJenisSampel($jenis_sampel_id);

        $kemasan = $jenisPemeriksaans->getComboKemasan($jenis_lab_id);

        $html = "<option value=''>Pilih</option>";

        foreach ($kemasan as $val) {
            if ($dataSampel->kemasan_sampel_id == $val->id) {
                $html .= "<option value='$val->id' selected>$val->nama_kemasan</option>";
            } else {
                $html .= "<option value='$val->id'>$val->nama_kemasan</option>";
            }
        }

        $data = array(
            'option' => $html,
            'volume' => $dataSampel->volume
        );

        return response()->json($data);
    }

    public function getDataKemasan(Request $request) {
        $user = Auth::user();
        $jenisPemeriksaans = new JenisPemeriksaan();

        $jenis_pemeriksaan_id = $request->jenis_pemeriksaan_id;
        $jenis_lab_id = $request->jenis_lab_id;

        $kemasan = $jenisPemeriksaans->getComboKemasan($jenis_lab_id);

        $html = "<option value=''>Pilih</option>";
        $volume = '';

        foreach ($kemasan as $val) {
            if ($jenis_lab_id == 2) {
                if (in_array($jenis_pemeriksaan_id, [20,21,22,31])) {
                    if ($val->id == 1) {
                        $html .= "<option value='$val->id' selected>$val->nama_kemasan</option>";
                        $volume = $val->volume;
                    } else {
                        $html .= "<option value='$val->id'>$val->nama_kemasan</option>";
                    }
                } elseif (in_array($jenis_pemeriksaan_id, [24])) {
                    if ($val->id == 4) {
                        $html .= "<option value='$val->id' selected>$val->nama_kemasan</option>";
                        $volume = $val->volume;
                    } else {
                        $html .= "<option value='$val->id'>$val->nama_kemasan</option>";
                    }
                } else {
                    $html .= "<option value='$val->id'>$val->nama_kemasan</option>";
                }
            }
            if ($jenis_lab_id == 3) {
                if (in_array($jenis_pemeriksaan_id, [25,26,27])) {
                    if ($val->id == 2) {
                        $html .= "<option value='$val->id' selected>$val->nama_kemasan</option>";
                        $volume = $val->volume;
                    } else {
                        $html .= "<option value='$val->id'>$val->nama_kemasan</option>";
                    }
                } elseif (in_array($jenis_pemeriksaan_id, [29])) {
                    if ($val->id == 3) {
                        $html .= "<option value='$val->id' selected>$val->nama_kemasan</option>";
                        $volume = $val->volume;
                    } else {
                        $html .= "<option value='$val->id'>$val->nama_kemasan</option>";
                    }
                } elseif (in_array($jenis_pemeriksaan_id, [28])) {
                    if ($val->id == 4) {
                        $html .= "<option value='$val->id' selected>$val->nama_kemasan</option>";
                        $volume = $val->volume;
                    } else {
                        $html .= "<option value='$val->id'>$val->nama_kemasan</option>";
                    }
                } else {
                    $html .= "<option value='$val->id'>$val->nama_kemasan</option>";
                }
            }
        }

        $data = array(
            'option' => $html,
            'volume' => $volume,
        );

        return response()->json($data);
    }

    public function gridHasilLab() {
        $user = Auth::user();

        $data = DB::table('view_pemeriksaan')
            ->select('view_pemeriksaan.id as pemeriksaan_id', 'view_pemeriksaan.no_registrasi', 'view_pemeriksaan.tgl_waktu_kunjungan', 'view_pemeriksaan.fileLaboratoriumHasilPemeriksaan', 'jenis_lab.nama_jenis_lab')
            ->leftJoin('jenis_lab', 'view_pemeriksaan.jenis_lab_id', 'jenis_lab.id')
            ->where('user_id', $user->id)->whereNotNull('fileLaboratoriumHasilPemeriksaan')
            ->paginate(10, ['*'], 'pagegridhasil');

        return view('hasil.index', compact('data'));
    }

    public function countCetakDownloadHasil(Request $request) {
        $user = Auth::user();

        $pemeriksaan_id = $request->pemeriksaan_id;

        $data = DB::table('pemeriksaan')->where('user_id', $user->id)->where('id', $pemeriksaan_id)->first();

        $cetak = '';

        if ($data) {
            $cCetak = DB::table('pemeriksaan')
                ->where('user_id', $user->id)
                ->where('id', $pemeriksaan_id)
                ->max('count_cetak');

            $cetak = $cCetak + 1;

            $uPemeriksaan = PemeriksaanTbl::where('id', $pemeriksaan_id)
                ->update([
                    "count_cetak" => $cetak
                ]);
        }

        $html = array(
            'cetak' => $cetak
        );

        return response()->json($html);
    }

    public function getDetailPemeriksaanKlinik(Request $request) {
        $id = $request->id;
        $arr_parameter_id = $request->arr_parameter_id;
        $arrParameterId = explode(",", $arr_parameter_id);

        $detailPemeriksaan = DB::table('parameter_pemeriksaan_klinik')->whereIn('id', $arrParameterId)->where('status', 1)->get();

        $countArr = count($detailPemeriksaan);

        $html = '';
        $i = 0;
        foreach($detailPemeriksaan as $val) {
            $i++;
            if ($i != $countArr) {
                $html .= $val->nama_parameter.", ";
            } else {
                $html .= $val->nama_parameter;
            }
        }

        return response()->json($html);
    }

    public function getPemeriksaanKlinik(Request $request) {
        $klinikIds = $request->arrPemeriksaanKlinik;

        $arrNamaPemeriksaan = array();
        foreach ($klinikIds as $val) {
            $data = DB::table('jenis_pemeriksaan')->where('id', $val)->first();
            array_push($arrNamaPemeriksaan, " ".$data->nama_pemeriksaan);
        }

        $namaPemeriksaan = implode(",", $arrNamaPemeriksaan);

        return response()->json($namaPemeriksaan);
    }

    public function getPemeriksaanKimia(Request $request) {
        $kimiaIds = $request->arrPemeriksaanKimia;

        $arrNamaPemeriksaan = array();
        foreach ($kimiaIds as $val) {
            $data = DB::table('parameter_pemeriksaan_kimia')->where('id', $val)->first();
            array_push($arrNamaPemeriksaan, " ".$data->nama_parameter);
        }

        $namaPemeriksaan = implode(",", $arrNamaPemeriksaan);

        return response()->json($namaPemeriksaan);
    }

    public function getPemeriksaanMikrobiologi(Request $request) {
        $mikrobiologiIds = $request->arrPemeriksaanMikrobiologi;

        $arrNamaPemeriksaan = array();
        foreach ($mikrobiologiIds as $val) {
            $data = DB::table('parameter_pemeriksaan_mikrobiologi')->where('id', $val)->first();
            array_push($arrNamaPemeriksaan, " ".$data->nama_parameter);
        }

        $namaPemeriksaan = implode(",", $arrNamaPemeriksaan);

        return response()->json($namaPemeriksaan);
    }

    public function hitungPemeriksaanKimiaMikro(Request $request) {
        $harga = str_ireplace('.', '', $request->harga);
        $total_biaya = str_ireplace('.', '', $request->total_biaya);
        $operand = $request->operand;
        $jmlh_sampel = $request->jmlh_sampel;

        if ($operand == 'tambah') {
            $total_biaya = $total_biaya + ($harga * $jmlh_sampel);
            
        } else {
            if ($total_biaya > 0) {
                $total_biaya = $total_biaya - ($harga * $jmlh_sampel);
            } else {
                $total_biaya = 0;
            }
        }

        $data['total_biaya'] = number_format($total_biaya, 0, ',', '.');

        return response()->json($data);
    }

    public function hitungJumlahSampelKimiaMikro(Request $request) {
        $pemeriksaan = new PemeriksaanTbl();
        $total_biaya = str_ireplace('.', '', $request->total_biaya);
        $jmlh_sampel = $request->jmlh_sampel;
        $jenis_lab_id = $request->jenis_lab_id;

        $paket = DB::table('temp_paket_pemeriksaan')->where('user_id', '=', Auth::id())->get();
        $parameter = DB::table('temp_parameter_pemeriksaan')->where('user_id', '=', Auth::id())->get();
        $arrpaket = [];
        $arrparameter = [];

        foreach ($paket as $val) {
            array_push($arrpaket, $val->paket_pemeriksaan_id);
        }

        foreach ($parameter as $val) {
            array_push($arrparameter, $val->parameter_id);
        }

        $total_paket = $pemeriksaan->getHargaPaketPemeriksaan($jenis_lab_id, $arrpaket);
        $total_parameter = $pemeriksaan->getHargaParameterPemeriksaan($jenis_lab_id, $arrparameter);
        $total_biaya = $total_paket + $total_parameter;

        if ($jmlh_sampel > 0) {
            $total_biaya = $total_biaya * $jmlh_sampel;
        } else {
            $jmlh_sampel = 1;
            $total_biaya = $total_biaya * $jmlh_sampel;
        }

        $data['total_biaya'] = number_format($total_biaya, 0, ',', '.');
        $data['jmlh_sampel'] = number_format($jmlh_sampel, 0, ',', '.');

        return response()->json($data);
    }

    public function getDataPembayaran(Request $request) {
        $pemeriksaan_id = $request->pemeriksaan_id;
        $data = DB::table('pembayaran')->where('pemeriksaan_id', $pemeriksaan_id)->first();

        $data->nominal_transfer = number_format($data->nominal_transfer, 0, ',', '.');

        return response()->json($data);
    }

    public function downloadHasilPemeriksaan($filename) {
        $file = public_path('storage/hasil_lab/' . $filename);

        return response()->download($file, $filename);
    }

    public function dataKirimWhatsapp(Request $request) {
        $pemeriksaan_id = $request->pemeriksaan_id;
        $whatsapp = DB::table('status_wa')->where('pemeriksaan_id', $pemeriksaan_id)->get();
        $html = "";
        $html .= "<div class='table-responsive'><table class='table table-bordered mb-4'>";
        $html .= "<tr>";
        $html .= "<th class='text-center'>No</th>";
        $html .= "<th class='text-center'>No. Whatsapp</th>";
        $html .= "<th class='text-center'>Tgl. Kirim</th>";
        $html .= "<th class='text-center'>Status</th>";
        $html .= "<th class='text-center'>Cek</th>";
        $html .= "</tr>";

        $no = 1;
        foreach($whatsapp as $val) {
            $html .= "<tr>";
            $html .= "<td class='text-center'>".$no++."</td>";
            $html .= "<td class='text-center'>".$val->no_hp."</td>";
            $html .= "<td class='text-center'>".date('d-m-Y H:i:s', strtotime($val->created_at))."</td>";
                $html .= "<td class='text-center'>".$val->status."</td>";
            $html .= "<td class='text-center'><a href='javascript:void(0)' onclick='checkWa(".$val->id.")' class='btn btn-sm btn-info'> <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-info'><circle cx='12' cy='12' r='10'></circle><line x1='12' y1='16' x2='12' y2='12'></line><line x1='12' y1='8' x2='12.01' y2='8'></line></svg></a></td>";
            $html .= "</tr>";
        }

        $html .= "</div></table>";

        $result['table'] = $html;

        return response()->json($result);

    }

    public function kirimWhatsapp(Request $request) {
        $pemeriksaan_id = $request->pemeriksaan_id;
        $data = DB::table('view_pemeriksaan')->where('view_pemeriksaan.id', $pemeriksaan_id)
            ->select('view_pemeriksaan.*', 'users.no_hp')
            ->leftJoin('users', 'view_pemeriksaan.user_id', 'users.id')
            ->first();

        $phone = $data->no_hp;
        $count = strlen((string) $phone);

        if (substr($phone, 0, 2) != 62) {
            $phone = substr($phone,1, (int) $count);
            $phone = '62'.$phone;
        }

        $curl = curl_init();
        $message = urlencode("Kepada Yth,\nBapak/Ibu Pelanggan Laboratorium Kesehatan Semarang\n\nTerima kasih telah berkenan menunggu.\nBerikut ini kami informasikan status pemeriksaan Bapak/Ibu,\n\nNama Pasien:".$data->nama_pasien."\nNo Registrasi:".$data->no_registrasi."\nStatus pemeriksaan: Sudah selesai\n\nUntuk hasil pemeriksaan dapat mengunjungi website Laboratorium Kesehatan Kota Semarang.\nhttp://103.101.52.65/labkes");
        $token = "lh1yD6Bbzf5SeJGUf0qHuXtciUuTfez0LTafkEzxnhP4J2Ez9uhYQHmLMuUWWym5";
        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array(
                "Authorization: $token",
                "Content-Type: application/json"
            )
        );
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL,  "https://jogja.wablas.com/api/send-message?phone=$phone&message=$message&token=$token");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response);

        DB::beginTransaction();

        try {
            $statuswa = StatusWa::create([
                "pemeriksaan_id" => $pemeriksaan_id,
                "no_hp" => $data->no_hp,
                "messages_id" => $result->data->messages[0]->id
            ]);

            DB::commit();

            $countWa = DB::table('status_wa')->where('pemeriksaan_id', $pemeriksaan_id)->count();

            $res['status'] = 'success';
            $res['message'] = 'Proses pengiriman notifikasi Whatsapp berhasil';
            $res['countWa'] = $countWa;

            return response()->json($res);
        } catch (\Throwable $t) {
            $countWa = DB::table('status_wa')->where('pemeriksaan_id', $pemeriksaan_id)->count();

            $res['status'] = 'failed';
            $res['message'] = 'Proses pengiriman notifikasi Whatsapp gagal';
            $res['countWa'] = $countWa;

            return response()->json($res);
        }
    }

    public function checkWhatsapp(Request $request) {
        $statuswa_id = $request->statuswa_id;
        $whatsapp = DB::table('status_wa')->where('id', $statuswa_id)->first();

        $curl = curl_init();
        $message_id = $whatsapp->messages_id;

        $token = "lh1yD6Bbzf5SeJGUf0qHuXtciUuTfez0LTafkEzxnhP4J2Ez9uhYQHmLMuUWWym5";

        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array(
                "Authorization: $token",
                "Content-Type: application/json"
            )
        );
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL,  "https://jogja.wablas.com/api/report-realtime?message_id=$message_id");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response);

        DB::beginTransaction();

        try {
            $statuswa = StatusWa::where('id', $statuswa_id)
                ->update([
                    "status" => $result->data[0]->status
                ]);

            DB::commit();

            $pemeriksaan_id = $whatsapp->pemeriksaan_id;
            $whatsapp = DB::table('status_wa')->where('pemeriksaan_id', $pemeriksaan_id)->get();
            $html = "";
            $html .= "<div class='table-responsive'><table class='table table-bordered mb-4'>";
            $html .= "<tr>";
            $html .= "<th class='text-center'>No</th>";
            $html .= "<th class='text-center'>No. Whatsapp</th>";
            $html .= "<th class='text-center'>Tgl. Kirim</th>";
            $html .= "<th class='text-center'>Status</th>";
            $html .= "<th class='text-center'>Cek</th>";
            $html .= "</tr>";

            $no = 1;
            foreach($whatsapp as $val) {
                $html .= "<tr>";
                $html .= "<td class='text-center'>".$no++."</td>";
                $html .= "<td class='text-center'>".$val->no_hp."</td>";
                $html .= "<td class='text-center'>".date('d-m-Y H:i:s', strtotime($val->created_at))."</td>";
                $html .= "<td class='text-center'>".$val->status."</td>";
                $html .= "<td class='text-center'><a href='javascript:void(0)' onclick='checkWa(".$val->id.")' class='btn btn-sm btn-info'> <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-info'><circle cx='12' cy='12' r='10'></circle><line x1='12' y1='16' x2='12' y2='12'></line><line x1='12' y1='8' x2='12.01' y2='8'></line></svg></a></td>";
                $html .= "</tr>";
            }

            $html .= "</div></table>";

            $res['status'] = 'success';
            $res['table'] = $html;

            return response()->json($res);
        } catch (\Throwable $t) {

            $res['status'] = 'failed';

            return response()->json($res);
        }

    }


}
