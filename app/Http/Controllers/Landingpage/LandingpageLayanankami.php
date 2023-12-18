<?php

namespace App\Http\Controllers\Landingpage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Redirect;
use App\Models\Landingpage\Layanankami\LayanankamiTbl;
use DB;
use Auth;

class LandingpageLayanankami extends Controller
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
        $layanankami = DB::table('lp_layanan_kami')->get();

        return view('backend.landingpage.halamandepan.layanankami.index', compact('layanankami'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.landingpage.halamandepan.layanankami.create');
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
        $nama_layanan = $request->nama_layanan;
        $keterangan = $request->keterangan;
        $foto_layanan = $request->file('foto_layanan');

        $pathfoto_layanan = $foto_layanan->store('public/foto_layanan');
        $flfoto_layanan = explode('/', $pathfoto_layanan);
        $filefoto_layanan = $flfoto_layanan[2];


        DB::beginTransaction();

        try {
            $layanankami = LayanankamiTbl::create([
                'nama_layanan' => $nama_layanan,
                'keterangan' => $keterangan,
                'foto_layanan' => $filefoto_layanan,
                'user_id' => $user_id,
            ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Disimpan');

            return redirect::to('/spr/landingpage/layanankami');
        } catch (\Throwable $t) {
            dd($t);
            Alert::error('Gagal', 'Data Gagal Disimpan');

            DB::rollback();
            return redirect::to('/spr/landingpage/layanankami');
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
        $layanan = DB::table('lp_layanan_kami')->where('id', $id)->first();

        return view('backend.landingpage.halamandepan.layanankami.edit', compact('layanan'));
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
        $nama_layanan = $request->nama_layanan;
        $keterangan = $request->keterangan;
        $foto_layanan = $request->file('foto_layanan');

        if ($foto_layanan) {
            $pathfoto_layanan = $foto_layanan->store('public/foto_layanan');
            $flfoto_layanan = explode('/', $pathfoto_layanan);
            $filefoto_layanan = $flfoto_layanan[2];
        } else {
            $dtlayanan = LayanankamiTbl::where('id', $id)->select('foto_layanan')->first();
            $filefoto_layanan = $dtlayanan->foto_layanan;
        }

        DB::beginTransaction();

        try {
            $layanan = LayanankamiTbl::where('id', $id)
                ->update([
                    'nama_layanan' => $nama_layanan,
                    'keterangan' => $keterangan,
                    'foto_layanan' => $filefoto_layanan,
                    'user_id' => $user_id,
            ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Disimpan');

            return redirect::to('/spr/landingpage/layanankami');
        } catch (\Throwable $t) {
            dd($t);
            Alert::error('Gagal', 'Data Gagal Disimpan');

            DB::rollback();
            return redirect::to('/spr/landingpage/layanankami');
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
