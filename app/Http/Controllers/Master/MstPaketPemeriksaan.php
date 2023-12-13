<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\PemeriksaanTbl\PemeriksaanTbl;
use App\Models\PaketPemeriksaan\PaketPemeriksaanMdl;
use DB;
use Auth;

class MstPaketPemeriksaan extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pemeriksaan = new PemeriksaanTbl();
        $data = $pemeriksaan->getDataMasterPaketPemeriksaan();

        return view('master.paketpemeriksaan.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master.paketpemeriksaan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        $lab_pemeriksaan_id = $request->lab_pemeriksaan_id;
        $nama_pemeriksaan = $request->nama_pemeriksaan;
        $parameter_ids = $request->parameter_ids;
        $status = $request->status;

        try {
            $paketpemeriksaan = PaketPemeriksaanMdl::create([
                "lab_pemeriksaan_id" => $lab_pemeriksaan_id,
                "nama_pemeriksaan" => $nama_pemeriksaan,
                "parameter_ids" => $parameter_ids,
                "status" => $status
            ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Disimpan');

            return redirect::to('/spr/master/paketpemeriksaan');
        } catch (\Throwable $t) {
            DB::rollback();
            dd($t);

            Alert::error('Gagal', 'Data Gagal Disimpan');

            return redirect::to('/spr/master/paketpemeriksaan');
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
        $data = DB::table('paket_pemeriksaan')->where('id', $id)->first();
        $lab = DB::table('jenis_lab')->get();

        return view('master.paketpemeriksaan.edit', compact('data', 'lab'));
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

        $lab_pemeriksaan_id = $request->lab_pemeriksaan_id;
        $nama_pemeriksaan = $request->nama_pemeriksaan;
        $parameter_ids = $request->parameter_ids;
        $status = $request->status;

        DB::beginTransaction();

        try {
            $paketpemeriksaan = DB::table('paket_pemeriksaan')->where('id', $id)
                ->update([
                    "lab_pemeriksaan_id" => $lab_pemeriksaan_id,
                    "nama_pemeriksaan" => $nama_pemeriksaan,
                    "arr_parameter_id" => $parameter_ids,
                    "status" => $status
            ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Diupdate');

            return redirect::to('/spr/master/paketpemeriksaan');
        } catch (\Throwable $t) {
            DB::rollback();
            dd($t);

            Alert::error('Gagal', 'Data Gagal Diupdate');

            return redirect::to('/spr/master/paketpemeriksaan');
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

    public function getDetailMstPaketPemeriksaan(Request $request) {
        $detail_paket_pemeriksaan = $request->detail_paket_pemeriksaan;

        $html = '';
        $i = 1;

        foreach ($detail_paket_pemeriksaan as $val) {
            $html .= '<tr>';
            $html .= '<td class="text-center">'.$i++.'</td>';
            $html .= '<td>'.$val['nama_parameter'].'</td>';
            $html .= '<td style="text-align:right;">'. number_format($val['harga'], 0, ',', '.').'</td>';
            $html .= '<tr>';
        }

        $data['html'] = $html;

        return response()->json($data);
    }

    public function paginationGridPaketPemeriksaan(Request $request) {
        if($request->ajax())
        {
            $combocari = $request->combocari;
            $pencarian = $request->pencarian;

            $data = DB::table('paket_pemeriksaan')
                ->leftJoin('jenis_lab', 'paket_pemeriksaan.lab_pemeriksaan_id', 'jenis_lab.id')
                ->select('paket_pemeriksaan.id as paket_pemeriksaan_id', 'nama_pemeriksaan', 'arr_parameter_id', 'lab_pemeriksaan_id', 'nama_jenis_lab', 'paket_pemeriksaan.status');

            if ($combocari) {
                $data->where($combocari, 'like', '%'.$pencarian.'%');
            }

            $data = $data->paginate(10, ['*'], 'pagemstgridpaketpemeriksaan');

            foreach ($data as $key => $val) {
                $arrparameter = explode(',', $val->arr_parameter_id);
                if ($val->lab_pemeriksaan_id == 1) {
                    $parameter = DB::table('parameter_pemeriksaan_klinik')->select('id as parameter_id', 'nama_parameter', 'harga')->whereIn('id', $arrparameter)->get();

                    $data[$key]->detail = $parameter;
                } elseif ($val->lab_pemeriksaan_id == 2) {
                    $parameter = DB::table('parameter_pemeriksaan_kimia')->select('id as parameter_id', 'nama_parameter', 'harga')->whereIn('id', $arrparameter)->get();

                    $data[$key]->detail = $parameter;
                } elseif ($val->lab_pemeriksaan_id == 3) {
                    $parameter = DB::table('parameter_pemeriksaan_mikrobiologi')->select('id as parameter_id', 'nama_parameter', 'harga')->whereIn('id', $arrparameter)->get();

                    $data[$key]->detail = $parameter;
                }
            }

            return view('master.paketpemeriksaan.list', compact('data'))->render();
        }
    }
}
