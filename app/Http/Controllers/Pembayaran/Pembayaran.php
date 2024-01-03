<?php

namespace App\Http\Controllers\Pembayaran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use DB;
use Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\PembayaranTbl\PembayaranTbl;
use App\Models\PemeriksaanTbl\PemeriksaanTbl;

class Pembayaran extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // public function gridPembayaranVerifikasi() {
    //     $data = DB::table('view_pembayaran')->get();

    //     return view('pembayaran.verifikasi.laboratorium.index', compact('data'));
    // }

    public function gridPembayaranVerifikasi() {
        $data = DB::table('view_pemeriksaan')->orderBy('created_at', 'desc')
            ->paginate(15, ['*'], 'pagegridverifikasipemeriksaan');

        return view('pembayaran.verifikasi.laboratorium.index', compact('data'));
    }

    public function paginationGridVerifikasiPembayaran(Request $request) {
        if($request->ajax())
        {
            $combocari = $request->combocari;
            $pencarian = $request->pencarian;
            $jenis_lab_id = $request->jenis_lab_id;

            $data = DB::table('view_pemeriksaan')->orderBy('created_at', 'desc');

            if ($combocari) {
                $data->where($combocari, 'like', '%'.$pencarian.'%');
            }

            $data = $data->paginate(15, ['*'], 'pagegridverifikasipembayaran');

            return view('pembayaran.verifikasi.laboratorium.list', compact('data'))->render();
        }
    }


    public function gridPembayaranVerifikasiHomecare() {
        $data = DB::table('view_homecare')->whereNotNull('pembayaran_id')->get();

        return view('pembayaran.verifikasi.homecare.index', compact('data'));
    }

    public function indexLaboratorium() {
        $user = Auth::user();

        $data = DB::table('view_pemeriksaan')
            ->where('user_id', $user->id)
            ->get();

        return view('pembayaran.laboratorium.index', compact('data'));
    }

    public function indexHomecare() {
        $user = Auth::user();

        $data = DB::table('view_homecare')
            ->where('user_id', $user->id)
            ->whereIn('status_homecare_id', array(2,3,4))->get();

        return view('pembayaran.homecare.index', compact('data'));
    }

    public function createPembayaranHomecare($homecare_id) {
        $user = Auth::user();

        $data = DB::table('view_homecare')->where('user_id', $user->id)->where('homecare_id', $homecare_id)->first();

        if ($data) {
            return view('pembayaran.homecare.create', compact('data'));
        } else {
            return view('404.index');
        }
    }

    public function createPembayaran($pemeriksaan_id) {
        $user = Auth::user();

        $data = DB::table('view_pemeriksaan')
            ->where([['user_id', Auth::user()->id], ['id', $pemeriksaan_id]])->first();

        if ($data) {
            return view('pembayaran.laboratorium.create', compact('data'));
        } else {
            return view('404.index');
        }
    }

    public function storePembayaranHomecare(Request $request) {
        $user = Auth::user();

        DB::beginTransaction();

        $homecare_id = $request->homecare_id;
        $no_registrasi = $request->no_registrasi;
        $no_rekening = $request->no_rekening;
        $nama_rekening = $request->nama_rekening;
        $asal_bank = $request->asal_bank;
        $tgl_transfer = $request->tgl_transfer;
        $nominal_transfer = $request->nominal_transfer;
        $bukti_bayar = $request->file('bukti_bayar');

        $pathbukti_bayar = $bukti_bayar->store('public/bukti_bayar');
        $flbukti_bayar = explode('/', $pathbukti_bayar);
        $filebukti_bayar = $flbukti_bayar[2];

        try {
            $pembayaran = PembayaranTbl::create([
                'no_registrasi' => $no_registrasi,
                'homecare_id' => $homecare_id,
                'no_rekening' => $no_rekening,
                'nama_rekening' => $nama_rekening,
                'asal_bank' => $asal_bank,
                'tgl_transfer' => date('Y-m-d', strtotime($tgl_transfer)),
                'nominal_transfer' => $nominal_transfer,
                'bukti_bayar' => $filebukti_bayar,
                'status' => 1
            ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Disimpan');

            return redirect::to('/pembayaran/homecare');
        } catch (\Throwable $t) {
            Alert::error('Gagal', 'Data Gagal Disimpan');

            DB::rollback();
            return redirect::to('/pembayaran/homecare');
        }
    }

    public function storePembayaran(Request $request) {
        $user = Auth::user();

        DB::beginTransaction();

        $pemeriksaan_id = $request->pemeriksaan_id;
        $no_registrasi = $request->no_registrasi;
        $nama_rekening = $request->nama_rekening;
        $asal_bank = $request->asal_bank;
        $tgl_transfer = $request->tgl_transfer;
        $nominal_transfer = $request->nominal_transfer;
        $bukti_bayar = $request->file('bukti_bayar');

        $pathbukti_bayar = $bukti_bayar->store('public/bukti_bayar');
        $flbukti_bayar = explode('/', $pathbukti_bayar);
        $filebukti_bayar = $flbukti_bayar[2];

        try {
            $pembayaran = PembayaranTbl::create([
                'no_registrasi' => $no_registrasi,
                'pemeriksaan_id' => $pemeriksaan_id,
                'nama_rekening' => $nama_rekening,
                'asal_bank' => $asal_bank,
                'tgl_transfer' => date('Y-m-d', strtotime($tgl_transfer)),
                'nominal_transfer' => str_ireplace(".", "", $nominal_transfer),
                'bukti_bayar' => $filebukti_bayar,
                'status' => 1
            ]);

            $uPemeriksaan = PemeriksaanTbl::where('id', $pemeriksaan_id)
                ->update([
                'status_bayar' => 1
            ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Disimpan');

            return redirect::to('/pembayaran/laboratorium');
        } catch (\Throwable $t) {
            Alert::error('Gagal', 'Data Gagal Disimpan');

            DB::rollback();
            return redirect::to('/pembayaran/laboratorium');
        }
    }

    public function updateStatusBayar(Request $request) {
        $user = Auth::user();

        DB::beginTransaction();

        $pemeriksaan_id = $request->validasi_pemeriksaan_id;
        $homecare_id = $request->homecare_id;
        $pembayaran_id = $request->validasi_pembayaran_id;
        $nominal_transfer = $request->validasi_nominal_transfer;

        try {
            $uPembayaran = PembayaranTbl::where('id', $pembayaran_id)
                ->update([
                'nominal_bayar' => $nominal_transfer,
                'verifikasi_user_id' => $user->id,
                'tgl_verifikasi' => date('Y-m-d H:i:s'),
                'status' => 2
            ]);

            $uRegistrasi = PemeriksaanTbl::where('id', $pemeriksaan_id)
                ->update([
                'status_bayar' => 2
            ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Disimpan');

            if (isset($pemeriksaan_id)) {
                return redirect::to('/registrasi/verifikasi');
            } else {
                return redirect::to('/pembayaran/verifikasi/homecare');
            }
        } catch (\Throwable $t) {
            Alert::error('Gagal', 'Data Gagal Disimpan');

            DB::rollback();

            if (isset($pemeriksaan_id)) {
                return redirect::to('/registrasi/verifikasi');
            } else {
                return redirect::to('/pembayaran/verifikasi/homecare');
            }
        }
    }
}
