<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Redirect;
use App\Models\Pamflet\PamfletTbl;

class MstPamflet extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('pamflet')->get();

        return view('master.pamflet.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
    public function edit($id)
    {
        $data = DB::table('pamflet')->where('id', $id)->first();

        return view('master.pamflet.edit', compact('data'));
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
        $urutan = $request->urutan;
        $status = $request->status;

        $ft_pamflet = $request->file('foto_pamflet');
        $pathft_pamflet = $ft_pamflet->store('public/pamflet');
        $flft_pamflet = explode('/', $pathft_pamflet);
        $fileft_pamflet = $flft_pamflet[2];

        DB::beginTransaction();

        try {
            $pamflet = DB::table('pamflet')->where('id', $id)
                ->update([
                    "nama_file" => $fileft_pamflet,
                    "urutan" => $urutan,
                    "updated_at" => date('Y-m-d H:i:s'),
                    "status" => $status,
            ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Diupdate');

            return redirect::to('/master/pamflet');
        } catch (\Throwable $t) {
            DB::rollback();

            Alert::error('Gagal', 'Data Gagal Diupdate');

            return redirect::to('/master/pamflet');
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
