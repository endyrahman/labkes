<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Redirect;
use App\Models\JenisPemeriksaan\JenisPemeriksaan;

use DB;

class MstJenisPemeriksaan extends Controller
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
        $data = DB::table('view_jenis_pemeriksaan')->get();

        foreach ($data as $val) {
            $arrParameterPemeriksaan = explode(",", $val->arr_parameter_id);
            $arrNamaParameterPemeriksaan = array();
            $arrNamaParameterPemeriksaan2 = array();
            foreach($arrParameterPemeriksaan as $res) {
                if ($val->lab_pemeriksaan_id == 1) {
                    $param_klinik = DB::table('parameter_pemeriksaan_klinik')->select('nama_parameter', 'harga')->where('id', $res)->first();
                    if ($param_klinik) {
                        if (count($arrNamaParameterPemeriksaan) >= 7) {
                            array_push($arrNamaParameterPemeriksaan2, $param_klinik->nama_parameter);
                        } else {
                            array_push($arrNamaParameterPemeriksaan, $param_klinik->nama_parameter);
                        }

                        $namaPemeriksaan = implode(", ", $arrNamaParameterPemeriksaan);
                        $namaPemeriksaan2 = implode(", ", $arrNamaParameterPemeriksaan2);
                    } else {
                        array_push($arrNamaParameterPemeriksaan, "");
                        $namaPemeriksaan = "Tidak Ada";
                    }
                } elseif ($val->lab_pemeriksaan_id == 2) {
                    $param_kimia = DB::table('parameter_pemeriksaan_kimia')->select('nama_parameter', 'harga')->where('id', $res)->first();
                    if ($param_kimia) {
                        if (count($arrNamaParameterPemeriksaan) == 7) {
                            array_push($arrNamaParameterPemeriksaan2, $param_kimia->nama_parameter);
                        } else {
                            array_push($arrNamaParameterPemeriksaan, $param_kimia->nama_parameter);
                        }

                        $namaPemeriksaan = implode(", ", $arrNamaParameterPemeriksaan);
                        $namaPemeriksaan2 = implode(", ", $arrNamaParameterPemeriksaan2);
                    } else {
                        array_push($arrNamaParameterPemeriksaan, "");
                        $namaPemeriksaan = "Tidak Ada";
                    }
                } elseif ($val->lab_pemeriksaan_id == 3) {
                    $param_mikro = DB::table('parameter_pemeriksaan_mikrobiologi')->select('nama_parameter', 'harga')->where('id', $res)->first();
                    if ($param_mikro) {
                        if (count($arrNamaParameterPemeriksaan) == 7) {
                            array_push($arrNamaParameterPemeriksaan2, $param_mikro->nama_parameter);
                        } else {
                            array_push($arrNamaParameterPemeriksaan, $param_mikro->nama_parameter);
                        }

                        $namaPemeriksaan = implode(", ", $arrNamaParameterPemeriksaan);
                        $namaPemeriksaan2 = implode(", ", $arrNamaParameterPemeriksaan2);
                    } else {
                        array_push($arrNamaParameterPemeriksaan, "");
                        $namaPemeriksaan = "Tidak Ada";
                    }
                } else {
                    array_push($arrNamaParameterPemeriksaan, "");
                    $namaPemeriksaan = "Tidak Ada";
                }

                $val->arr_nama_parameter = $namaPemeriksaan;
                $val->arr_nama_parameter2 = $namaPemeriksaan2;
            }
        }

        return view('master.jenispemeriksaan.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master.jenispemeriksaan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $lab_pemeriksaan_id = $request->lab_pemeriksaan_id;
        $nama_pemeriksaan = $request->nama_pemeriksaan;
        $parameter_ids = $request->parameter_ids;
        $status = $request->status;

        DB::beginTransaction();

        try {
            $kimia = JenisPemeriksaan::create([
                "lab_pemeriksaan_id" => $lab_pemeriksaan_id,
                "nama_pemeriksaan" => $nama_pemeriksaan,
                "arr_parameter_id" => $parameter_ids,
                "status" => $status
            ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Disimpan');

            return redirect::to('/master/jenispemeriksaan');
        } catch (\Throwable $t) {
            dd($t);
            DB::rollback();

            Alert::error('Gagal', 'Data Gagal Disimpan');
            return redirect::to('/master/jenispemeriksaan');
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
        $data = DB::table('jenis_pemeriksaan')->where('id', $id)->first();
        $lab = DB::table('jenis_lab')->get();

        return view('master.jenispemeriksaan.edit', compact('data', 'lab'));
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
            $parameterKimia = DB::table('jenis_pemeriksaan')->where('id', $id)
                ->update([
                    "lab_pemeriksaan_id" => $lab_pemeriksaan_id,
                    "nama_pemeriksaan" => $nama_pemeriksaan,
                    "arr_parameter_id" => $parameter_ids,
                    "status" => $status
            ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Diupdate');

            return redirect::to('/master/jenispemeriksaan');
        } catch (\Throwable $t) {
            dd($t);
            DB::rollback();

            Alert::error('Gagal', 'Data Gagal Diupdate');

            return redirect::to('/master/jenispemeriksaan');
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

    public function getParameterJenisPemeriksaan(Request $request) {
        $parameterPemeriksaanIds = $request->arrParameterPemeriksaan;
        $jenis_lab_id = $request->jenis_lab_id;

        $arrNamaParameterPemeriksaan = array();
        foreach($parameterPemeriksaanIds as $val) {
            if ($jenis_lab_id == 1) {
                $data = DB::table('parameter_pemeriksaan_klinik')->select('nama_parameter', 'harga')->where('id', $val)->first();
                array_push($arrNamaParameterPemeriksaan, $data->nama_parameter);
            }
        }

        $namaPemeriksaan = implode(",", $arrNamaParameterPemeriksaan);

        return response()->json($namaPemeriksaan);


        // $total_biaya = 0;
        // foreach ($klinikIds as $val) {
        //     if ($lab_pemeriksaan_id == 1) {
        //         $data = DB::table('jenis_pemeriksaan')->where('id', $val)->first();
        //         dd($data);
        //         array_push($arrNamaPemeriksaan, $data->nama_pemeriksaan);
        //         // $total_biaya += $data->
        //     } else {
        //         $data = DB::table('jenis_pemeriksaan')->where('id', $val)->first();
        //     }
        // }

        // $namaPemeriksaan = implode(",", $arrNamaPemeriksaan);

        // return response()->json($namaPemeriksaan);
    }

    public function getDataParameterPemeriksaan(Request $request) {
        $lab_pemeriksaan_id = $request->lab_pemeriksaan_id;

        if ($lab_pemeriksaan_id == 1) {
            $param = DB::table('parameter_pemeriksaan_klinik')->get();
        } elseif ($lab_pemeriksaan_id == 2) {
            $param = DB::table('parameter_pemeriksaan_kimia')->get();
        } elseif ($lab_pemeriksaan_id == 3) {
            $param = DB::table('parameter_pemeriksaan_mikrobiologi')->get();
        }

        $html = '<table id="dt-mstjenisparameterpemeriksaan" class="table dt-table-hover mb-4">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="data_parameter_pemeriksaan">';

        foreach($param as $val) {
            $html .= '<tr>';
            $html .= '<td>'.$val->nama_parameter.'</td>';
            $html .= '<td>'.number_format($val->harga, '0', ',', '.').'</td>';
            $html .= "<td><button type='button' class='btn btn-sm btn-info' id='tambahparameter$val->id' onclick='tambahparameterpemeriksaan($val->id,\"$val->nama_parameter\")'>Tambahkan</button><button type='button' class='btn btn-sm btn-danger' id='hapusparameter$val->id' onclick='hapusparameterpemeriksaan($val->id)' style='display:none;'>Hapus</button></td>";
            $html .= '</tr>';
        }

        $html .= '    </tbody>
                </table>';

        $data['html'] = $html;

        return response()->json($data);
    }

    public function hapusDataParameterPemeriksaan(Request $request) {
        $param_ids = $request->parameter_ids;
        $nama_param = $request->nama_parameter;
        $id = $request->id;
        $lab_pemeriksaan_id = $request->lab_pemeriksaan_id;

        $param = '';
        if ($lab_pemeriksaan_id == 1) {
            $param = DB::table('parameter_pemeriksaan_klinik')->where('id',$id)->first();
        } elseif ($lab_pemeriksaan_id == 2) {
            $param = DB::table('parameter_pemeriksaan_kimia')->where('id',$id)->first();
        } elseif ($lab_pemeriksaan_id == 3) {
            $param = DB::table('parameter_pemeriksaan_mikrobiologi')->where('id',$id)->first();
        }

        $arrids = explode(",", $param_ids);
        $arrkeyids = array_search($id, $arrids);
        unset($arrids[$arrkeyids]);
        $result['ids'] = implode(",", $arrids);

        $arrnama = explode(",", $nama_param);
        $arrkeynama = array_search($param->nama_parameter, $arrnama);
        unset($arrnama[$arrkeynama]);
        $result['nama_parameter'] = implode(",", $arrnama);

        return response()->json($result);
    }
}
