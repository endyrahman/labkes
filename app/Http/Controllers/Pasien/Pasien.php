<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\PasienTbl\PasienTbl;
use Auth;
use DB;


class Pasien extends Controller
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
    public function index()
    {
        $pasien = DB::table('pasien')->paginate(10);

        return view('pasien.index', compact('pasien'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pasien.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        DB::beginTransaction();

        $nama_pasien = $request->nama_pasien;
        $nik = $request->nik;
        $tempat_lahir = $request->tempat_lahir;
        $tgl_lahir = date('Y-m-d', strtotime($request->tgl_lahir));
        $jenis_kelamin = $request->jenis_kelamin;
        $alamat = $request->alamat;
        $html = "";

        try {
            $pasien = PasienTbl::create([
                "user_id" => $user->id,
                "nama_pasien" => strtoupper($nama_pasien),
                "nik" => $nik,
                "tempat_lahir" => strtoupper($tempat_lahir),
                "tgl_lahir" => $tgl_lahir,
                "jenis_kelamin" => $jenis_kelamin,
                "alamat" => $alamat
            ]);

            DB::commit();

            $dataPasien = DB::table('pasien')->get();

            $html .= "<option value=''>Pilih Pasien</option>";
            foreach ($dataPasien as $val) {
                if ($val->id == $pasien->id) {
                    $html .= "<option value='".$val->id."' selected>".$val->nama_pasien."</option>";
                } else {
                    $html .= "<option value='".$val->id."'>".$val->nama_pasien."</option>";
                }
            }

            $data['html'] = $html;
            $data['status'] = 'success';
            $data['message'] = 'Data Berhasil Disimpan';

            return response()->json($data);
        } catch (\Throwable $t) {
            dd($t);
            DB::rollback();

            $data['html'] = '';
            $data['status'] = 'gagal';
            $data['message'] = 'Data Gagal Disimpan';

            return response()->json($data);
        }
    }

    public function storePasienBaru(Request $request)
    {
        $user = Auth::user();

        DB::beginTransaction();

        $nama_pasien = $request->nama_pasien;
        $nik = $request->nik;
        $tempat_lahir = $request->tempat_lahir;
        $tgl_lahir = date('Y-m-d', strtotime($request->tgl_lahir));
        $jenis_kelamin = $request->jenis_kelamin;
        $alamat = $request->alamat;
        $html = "";

        try {
            $pasien = PasienTbl::create([
                "user_id" => $user->id,
                "nama_pasien" => strtoupper($nama_pasien),
                "nik" => $nik,
                "tempat_lahir" => strtoupper($tempat_lahir),
                "tgl_lahir" => $tgl_lahir,
                "jenis_kelamin" => $jenis_kelamin,
                "alamat" => $alamat
            ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Disimpan');

            return redirect::to('/pasien');
        } catch (\Throwable $t) {
            dd($t);

            DB::rollback();
            Alert::error('Gagal', 'Data Gagal Disimpan');

            return redirect::to('/pasien');
        }
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
    public function edit($id)
    {
        $data = DB::table('pasien')->where('id', $id)->first();

        return view('pasien.edit', compact('data'));
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

        $user = Auth::user();

        DB::beginTransaction();

        $nama_pasien = $request->nama_pasien;
        $nik = $request->nik;
        $tempat_lahir = $request->tempat_lahir;
        $tgl_lahir = date('Y-m-d', strtotime($request->tgl_lahir));
        $jenis_kelamin = $request->jenis_kelamin;
        $alamat = $request->alamat;

        try {
            $pasien = PasienTbl::where('id', $id)
                ->update([
                    "user_id" => $user->id,
                    "nama_pasien" => strtoupper($nama_pasien),
                    "nik" => $nik,
                    "tempat_lahir" => strtoupper($tempat_lahir),
                    "tgl_lahir" => $tgl_lahir,
                    "jenis_kelamin" => $jenis_kelamin,
                    "alamat" => $alamat
                ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Disimpan');

            return redirect::to('/pasien');
        } catch (\Throwable $t) {
            dd($t);

            DB::rollback();
            Alert::error('Gagal', 'Data Gagal Disimpan');

            return redirect::to('/pasien');
        }
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
}
