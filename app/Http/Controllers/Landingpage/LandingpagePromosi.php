<?php

namespace App\Http\Controllers\Landingpage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Redirect;
use App\Models\Pamflet\PamfletTbl;

use DB;
use Auth;

class LandingpagePromosi extends Controller
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
        $promosi = DB::table('pamflet')->paginate(10, ['*'], 'pagebekegiatan');

        return view('backend.landingpage.halamandepan.promosi.index', compact('promosi'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.landingpage.halamandepan.promosi.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $user_id = Auth::id();
        $nama = $request->nama;
        $status = $request->status;

        $foto_promosi = $request->file('foto_promosi');
        $pathfoto_promosi = $foto_promosi->store('public/foto_promosi');
        $flfoto_promosi = explode('/', $pathfoto_promosi);
        $filefoto_promosi = $flfoto_promosi[2];

        DB::beginTransaction();

        try {
            $promosi = PamfletTbl::create([
                'nama' => $nama,
                'status' => $status,
                'nama_file' => $filefoto_promosi,
                'user_id' => $user_id,
            ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Disimpan');

            return redirect::to('/spr/landingpage/promosi');
        } catch (\Throwable $t) {
            dd($t);
            Alert::error('Gagal', 'Data Gagal Disimpan');

            DB::rollback();
            return redirect::to('/spr/landingpage/promosi');
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
        $promosi = DB::table('pamflet')->where('id', $id)->first();

        return view('backend.landingpage.halamandepan.promosi.edit', compact('promosi'));
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
        $user_id = Auth::id();
        $nama = $request->nama;
        $status = $request->status;
        $foto_promosi = $request->file('foto_promosi');

        if ($foto_promosi) {
            $pathfoto_promosi = $foto_promosi->store('public/foto_promosi');
            $flfoto_promosi = explode('/', $pathfoto_promosi);
            $filefoto_promosi = $flfoto_promosi[2];
        } else {
            $dtpamflet = PamfletTbl::where('id', $id)->first();
            $filefoto_promosi = $dtpamflet->nama_file;
        }

        DB::beginTransaction();

        try {
            $promosi = PamfletTbl::where('id', $id)
                ->update([
                'nama' => $nama,
                'status' => $status,
                'nama_file' => $filefoto_promosi,
                'user_id' => $user_id,
            ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Disimpan');

            return redirect::to('/spr/landingpage/promosi');
        } catch (\Throwable $t) {
            dd($t);
            Alert::error('Gagal', 'Data Gagal Disimpan');

            DB::rollback();
            return redirect::to('/spr/landingpage/promosi');
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
