<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Redirect;
use App\Models\ParameterPemeriksaan\ParameterKlinikTbl;
use App\Models\ParameterPemeriksaan\ParameterKimiaTbl;
use App\Models\ParameterPemeriksaan\ParameterMikrobiologiTbl;

class MstParameterPemeriksaan extends Controller
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
    public function indexMstParameterKlinik()
    {
        $data = DB::table('parameter_pemeriksaan_klinik')->get();

        return view('master.parameterpemeriksaan.klinik.index', compact('data'));
    }

    public function indexMstParameterKimia()
    {
        $data = DB::table('parameter_pemeriksaan_kimia')->get();

        return view('master.parameterpemeriksaan.kimia.index', compact('data'));
    }

    public function indexMstParameterMikrobiologi()
    {
        $data = DB::table('parameter_pemeriksaan_mikrobiologi')->get();

        return view('master.parameterpemeriksaan.mikrobiologi.index', compact('data'));
    }
    public function createMstParameterKlinik()
    {
        return view('master.parameterpemeriksaan.klinik.create');
    }

    public function createMstParameterKimia()
    {
        return view('master.parameterpemeriksaan.kimia.create');
    }

    public function createMstParameterMikrobiologi()
    {
        return view('master.parameterpemeriksaan.mikrobiologi.create');
    }

    public function editMstParameterKlinik($parameter_id)
    {
        $data = DB::table('parameter_pemeriksaan_klinik')->where('id', $parameter_id)->first();

        return view('master.parameterpemeriksaan.klinik.edit', compact('data'));
    }

    public function editMstParameterKimia($parameter_id)
    {
        $data = DB::table('parameter_pemeriksaan_kimia')->where('id', $parameter_id)->first();

        return view('master.parameterpemeriksaan.kimia.edit', compact('data'));
    }

    public function editMstParameterMikrobiologi($parameter_id)
    {
        $data = DB::table('parameter_pemeriksaan_mikrobiologi')->where('id', $parameter_id)->first();

        return view('master.parameterpemeriksaan.mikrobiologi.edit', compact('data'));
    }

    public function updateMstParameterKlinik(Request $request)
    {
        $parameter_id = $request->parameter_id;
        $nama_parameter = $request->nama_parameter;
        $harga = $request->harga;
        $status = $request->status;

        DB::beginTransaction();

        try {
            $parameterKimia = DB::table('parameter_pemeriksaan_klinik')->where('id', $parameter_id)
                ->update([
                    "nama_parameter" => $nama_parameter,
                    "harga" => str_ireplace('.', '', $harga),
                    "status" => $status,
            ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Diupdate');

            return redirect::to('/master/parameterpemeriksaan/1');
        } catch (\Throwable $t) {
            DB::rollback();

            Alert::error('Gagal', 'Data Gagal Diupdate');

            return redirect::to('/master/parameterpemeriksaan/1');
        }
    }

    public function updateMstParameterKimia(Request $request)
    {
        $parameter_id = $request->parameter_id;
        $nama_parameter = $request->nama_parameter;
        $harga = $request->harga;
        $status = $request->status;

        DB::beginTransaction();

        try {
            $parameterKimia = DB::table('parameter_pemeriksaan_kimia')->where('id', $parameter_id)
                ->update([
                    "nama_parameter" => $nama_parameter,
                    "harga" => str_ireplace('.', '', $harga),
                    "status" => $status,
            ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Diupdate');

            return redirect::to('/master/parameterpemeriksaan/2');
        } catch (\Throwable $t) {
            DB::rollback();

            Alert::error('Gagal', 'Data Gagal Diupdate');

            return redirect::to('/master/parameterpemeriksaan/2');
        }
    }

    public function updateMstParameterMikrobiologi(Request $request)
    {
        $parameter_id = $request->parameter_id;
        $nama_parameter = $request->nama_parameter;
        $harga = $request->harga;
        $status = $request->status;

        DB::beginTransaction();

        try {
            $parameterKimia = DB::table('parameter_pemeriksaan_mikrobiologi')->where('id', $parameter_id)
                ->update([
                    "nama_parameter" => $nama_parameter,
                    "harga" => str_ireplace('.', '', $harga),
                    "status" => $status,
            ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Diupdate');

            return redirect::to('/master/parameterpemeriksaan/3');
        } catch (\Throwable $t) {
            DB::rollback();

            Alert::error('Gagal', 'Data Gagal Diupdate');

            return redirect::to('/master/parameterpemeriksaan/3');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeKlinik(Request $request)
    {
        $nama_parameter = $request->nama_parameter;
        $harga = $request->harga;
        $status = $request->status;

        DB::beginTransaction();

        try {
            $klinik = ParameterKlinikTbl::create([
                "nama_parameter" => $nama_parameter,
                "harga" => str_ireplace('.', '', $harga),
                "status" => $status
            ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Disimpan');

            return redirect::to('/master/parameterpemeriksaan/1');
        } catch (\Throwable $t) {
            dd($t);
            DB::rollback();

            Alert::error('Gagal', 'Data Gagal Disimpan');
            return redirect::to('/master/parameterpemeriksaan/1');
        }
    }

    public function storeKimia(Request $request)
    {
        $nama_parameter = $request->nama_parameter;
        $harga = $request->harga;
        $status = $request->status;

        DB::beginTransaction();

        try {
            $kimia = ParameterKimiaTbl::create([
                "nama_parameter" => $nama_parameter,
                "harga" => str_ireplace('.', '', $harga),
                "status" => $status
            ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Disimpan');

            return redirect::to('/master/parameterpemeriksaan/2');
        } catch (\Throwable $t) {
            dd($t);
            DB::rollback();

            Alert::error('Gagal', 'Data Gagal Disimpan');
            return redirect::to('/master/parameterpemeriksaan/2');
        }
    }
    public function storeMikrobiologi(Request $request)
    {
        $nama_parameter = $request->nama_parameter;
        $harga = $request->harga;
        $status = $request->status;

        DB::beginTransaction();

        try {
            $mikrobiologi = ParameterMikrobiologiTbl::create([
                "nama_parameter" => $nama_parameter,
                "harga" => str_ireplace('.', '', $harga),
                "status" => $status
            ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Disimpan');

            return redirect::to('/master/parameterpemeriksaan/3');
        } catch (\Throwable $t) {
            dd($t);
            DB::rollback();

            Alert::error('Gagal', 'Data Gagal Disimpan');
            return redirect::to('/master/parameterpemeriksaan/3');
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
        //
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
}
