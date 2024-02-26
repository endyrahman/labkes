<?php

namespace App\Http\Controllers\HomeCare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use App\Models\HomeCareTbl\HomeCareTbl;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Redirect;

class HomeCare extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexPengguna()
    {
        $user = Auth::user();

        $data = DB::table('view_homecare')->where('user_id', Auth::user()->id)->get();

        return view('homecare.pengguna.index', compact('data'));
    }

    public function indexAdmin()
    {
        $user = Auth::user();

        $data = DB::table('view_homecare')->orderBy('status_homecare', 'asc')->get();

        return view('homecare.admin.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createHomecare()
    {
        $user = Auth::user();

        return view('homecare.pengguna.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editHomecare($homecare_id)
    {
        $user = Auth::user();

        $data = DB::table('view_homecare')->where('user_id', Auth::user()->id)->where('homecare_id', $homecare_id)->first();

        if ($data) {
            return view('homecare.pengguna.edit', compact('data'));
        } else {
            return view('404.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public static function hitungJarakLokasi(Request $request, $latitudeFrom = -6.9917601582, $longitudeFrom = 110.3853251451, $earthRadius = 6371) {

        $latitudeTo = $request->lat;
        $longitudeTo = $request->lng;

        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) + pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);

        $jarak = $angle * $earthRadius;

        return response()->json($jarak);
    }

    public function storeHomeCare(Request $request) {
        $user = Auth::user();

        DB::beginTransaction();

        $jenis_homecare_id = $request->jenis_homecare_id;
        $tgl_waktu_kunjungan = $request->tgl_waktu_kunjungan;
        $nama_lengkap = $request->nama_lengkap;
        $no_hp = $request->no_hp;
        $alamat_lengkap = $request->alamat_lengkap;
        $catatan_homecare = $request->catatan_homecare;

        try {
            $homecare = HomeCareTbl::create([
                "jenis_homecare_id" => $jenis_homecare_id,
                "tgl_waktu_kunjungan" => date('Y-m-d H:i:s', strtotime($tgl_waktu_kunjungan)),
                "tgl_input" => date('Y-m-d H:i:s'),
                "nama_lengkap" => $nama_lengkap,
                "no_hp" => $no_hp,
                "catatan_homecare" => $catatan_homecare,
                "alamat_lengkap" => $alamat_lengkap,
                "user_id" => $user->id,
                "status" => 1
            ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Disimpan');

            return redirect::to('/registrasi/homecare');
        } catch (\Throwable $t) {

            dd($t);

            DB::rollback();
            return redirect::to('/registrasi/homecare');
        }
    }

    public function hapusRegistrasi(Request $request)
    {
        $user = Auth::user();

        DB::beginTransaction();

        $homecare_id = $request->homecare_id;

        try {
            DB::table('homecare')->where('id', $homecare_id)->delete();

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Dihapus');
            if ($user->role_id == 1) {
                return redirect::to('/homecare/verifikasi');
            } else {
                return redirect::to('/registrasi/homecare');
            }
        } catch (\Throwable $t) {
            Alert::error('Gagal', 'Data Gagal Dihapus');
            dd($t);
            DB::rollback();

            if ($user->role_id == 1) {
                return redirect::to('/homecare/verifikasi');
            } else {
                return redirect::to('/registrasi/homecare');
            }
        }
    }

    public function updateHomeCare(Request $request) {
        $user = Auth::user();

        DB::beginTransaction();

        $homecare_id = $request->homecare_id;
        $jenis_homecare_id = $request->jenis_homecare_id;
        $tgl_waktu_kunjungan = $request->tgl_waktu_kunjungan;
        $nama_lengkap = $request->nama_lengkap;
        $no_hp = $request->no_hp;
        $alamat_lengkap = $request->alamat_lengkap;
        $catatan_homecare = $request->catatan_homecare;

        try {
            $homecare = HomeCareTbl::where('id', $homecare_id)
                ->update([
                    "jenis_homecare_id" => $jenis_homecare_id,
                    "tgl_waktu_kunjungan" => date('Y-m-d H:i:s', strtotime($tgl_waktu_kunjungan)),
                    "tgl_edit" => date('Y-m-d H:i:s'),
                    "nama_lengkap" => $nama_lengkap,
                    "no_hp" => $no_hp,
                    "catatan_homecare" => $catatan_homecare,
                    "alamat_lengkap" => $alamat_lengkap,
                    "user_id" => $user->id
            ]);

            Alert::success('Berhasil', 'Data Berhasil Diupdate');

            DB::commit();

            return redirect::to('/registrasi/homecare');
        } catch (\Throwable $t) {

            dd($t);

            DB::rollback();
            return redirect::to('/registrasi/homecare');
        }
    }

    public function getJadwalPemeriksaanHomecare(Request $request)
    {
        $tglkunjungan = $request->tgl;

        $dataKunjungan = DB::table('homecare')
            ->select(DB::raw('COUNT(*) AS jmlh_kunjungan'))
            ->whereRaw("DATE_FORMAT(tgl_waktu_kunjungan, '%Y-%m-%d') = '".date('Y-m-d', strtotime($tglkunjungan))."'")
            ->first();

        $numbOfDay = date('w', strtotime($tglkunjungan));

        $kuota = '';
        $sisaKuota = 0;

        if (in_array($numbOfDay, ['1','2','3','4'])) {
            $kuota = 3;
            $sisaKuota = $kuota - $dataKunjungan->jmlh_kunjungan;
            $countJamKunjungan = 3;
        } elseif ($numbOfDay == '5') {
            $kuota = 2;
            $countJamKunjungan = 2;
            $sisaKuota = $kuota - $dataKunjungan->jmlh_kunjungan;
        } elseif ($numbOfDay == '6') {
            $kuota = 1;
            $countJamKunjungan = 1;
            $sisaKuota = $kuota - $dataKunjungan->jmlh_kunjungan;
        }

        $html = '';
        $count = 1;
        $html = '
        <fieldset class="border p-2">
            <legend class="w-auto">Pilih Jam Kunjungan</legend>
                <div class="col-md-12">
                <table class="table mb-2">
                    <tbody>';

        $time = 8;

        if ($sisaKuota == 0) {
            $html .= "<tr>
                <td><span style='color:red;'>Kuota tanggal ".date('d-m-Y', strtotime($tglkunjungan))." sudah penuh.</span></td>
            </tr>";
        } else {
            for ($i=0; $i < $countJamKunjungan; $i++) {
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
                $('#tgl_waktu_kunjungan').val(jadwalKunjungan);
                $('#tglwaktukunjungan').val(jadwalKunjungan);
                setTimeout(
                function()
                {
                    $('#mdlJadwalKunjungan').modal('hide');
                }, 1000);

            });
        </script>
        ";

        $data['html'] = $html;
        $data['sisaKuota'] = $sisaKuota;

        return response()->json($data);
    }

    public function setujuHomecare(Request $request)
    {
        $user = Auth::user();
        $homecare_id = $request->homecare_setuju_id;

        DB::beginTransaction();

        try {
            $uHomecare = HomeCareTbl::where('id', $homecare_id)
                ->update([
                    "status" => 2,
                ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Diverifikasi');

            return redirect::to('homecare/verifikasi');
        } catch (\Throwable $t) {
            Alert::error('Gagal', 'Data Gagal Diverifikasi');
            dd($t);
            DB::rollback();

            return redirect::to('homecare/verifikasi');
        }
    }

    public function updateStatusLab(Request $request)
    {
        $user = Auth::user();
        $homecare_id = $request->homecare_proseslab_id;
        $no_pelanggan_silkes = $request->no_pelanggan_silkes;
        $no_sample_silkes = $request->no_sample_silkes;

        DB::beginTransaction();

        try {
            $uHomecare = HomeCareTbl::where('id', $homecare_id)
                ->update([
                    "status" => 3,
                    "no_pelanggan_silkes" => $no_pelanggan_silkes,
                    "no_sample_silkes" => $no_sample_silkes
                ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Diverifikasi');

            return redirect::to('homecare/verifikasi');
        } catch (\Throwable $t) {
            Alert::error('Gagal', 'Data Gagal Diverifikasi');
            dd($t);
            DB::rollback();

            return redirect::to('homecare/verifikasi');
        }
    }

    public function updateStatusSelesaiLab(Request $request)
    {
        $user = Auth::user();
        $homecare_id = $request->homecare_hasillab_id;
        $fileLabHasilPemeriksaan = $request->file('fileLabHasilPemeriksaan');

        DB::beginTransaction();

        try {
            $pathFileLabHasilPemeriksaan = $fileLabHasilPemeriksaan->store('public/hasil_lab');
            $flLabHasilPemeriksaan = explode('/', $pathFileLabHasilPemeriksaan);
            $fileLaboratoriumHasilPemeriksaan = $flLabHasilPemeriksaan[2];

            $uHomecare = HomeCareTbl::where('id', $homecare_id)
                ->update([
                    "status" => 4,
                    "fileLabHasilPemeriksaan" => $fileLaboratoriumHasilPemeriksaan
            ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Diverifikasi');

            return redirect::to('homecare/verifikasi');
        } catch (\Throwable $t) {
            Alert::error('Gagal', 'Data Gagal Diverifikasi');
            dd($t);
            DB::rollback();

            return redirect::to('homecare/verifikasi');
        }
    }

    public function updateStatusBatalHomecare(Request $request)
    {
        $user = Auth::user();
        $homecare_id = $request->homecare_batal_id;

        DB::beginTransaction();

        try {
            $uHomecare = HomeCareTbl::where('id', $homecare_id)
                ->update([
                    "status" => 5
            ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Diverifikasi');

            return redirect::to('homecare/verifikasi');
        } catch (\Throwable $t) {
            Alert::error('Gagal', 'Data Gagal Diverifikasi');
            dd($t);
            DB::rollback();

            return redirect::to('homecare/verifikasi');
        }
    }
}
