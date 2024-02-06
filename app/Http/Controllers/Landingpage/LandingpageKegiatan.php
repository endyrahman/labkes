<?php

namespace App\Http\Controllers\Landingpage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Redirect;
use App\Models\Kegiatan\KegiatanTbl;
use Illuminate\Support\Facades\Storage;
use DB;
use Auth;

class LandingpageKegiatan extends Controller
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
        $kegiatan = DB::table('lp_kegiatan')->paginate(10);

        return view('backend.landingpage.halamandepan.kegiatan.index', compact('kegiatan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.landingpage.halamandepan.kegiatan.create');
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
        $nama_kegiatan = $request->nama_kegiatan;
        $lokasi = $request->lokasi;
        $keterangan = $request->keterangan;
        $tgl_kegiatan = $request->tgl_kegiatan;
        $foto_kegiatan = $request->file('foto_kegiatan');
        $pathfoto_kegiatan = Storage::disk('public_uploads')->put('kegiatan', $foto_kegiatan);
        $flfoto_kegiatan = explode('/', $pathfoto_kegiatan);
        $filefoto_kegiatan = $flfoto_kegiatan[1];


        DB::beginTransaction();

        try {
            $kegiatan = KegiatanTbl::create([
                'nama_kegiatan' => $nama_kegiatan,
                'lokasi' => $lokasi,
                'keterangan' => $keterangan,
                'tgl_kegiatan' => date('Y-m-d', strtotime($tgl_kegiatan)),
                'foto_kegiatan' => $filefoto_kegiatan,
                'user_id' => $user_id,
            ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Disimpan');

            return redirect::to('/spr/landingpage/kegiatan');
        } catch (\Throwable $t) {
            dd($t);
            Alert::error('Gagal', 'Data Gagal Disimpan');

            DB::rollback();
            return redirect::to('/spr/landingpage/kegiatan');
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
        $kegiatan = DB::table('lp_kegiatan')->where('id', $id)->first();

        return view('backend.landingpage.halamandepan.kegiatan.edit', compact('kegiatan'));
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
        $nama_kegiatan = $request->nama_kegiatan;
        $lokasi = $request->lokasi;
        $keterangan = $request->keterangan;
        $tgl_kegiatan = $request->tgl_kegiatan;
        $foto_kegiatan = $request->file('foto_kegiatan');

        if ($foto_kegiatan) {
            $pathfoto_kegiatan = $foto_kegiatan->store('public/foto_kegiatan');
            $flfoto_kegiatan = explode('/', $pathfoto_kegiatan);
            $filefoto_kegiatan = $flfoto_kegiatan[2];
        } else {
            $dtkegiatan = KegiatanTbl::where('id', $id)->select('foto_kegiatan')->first();
            $filefoto_kegiatan = $dtkegiatan->foto_kegiatan;
        }

        DB::beginTransaction();

        try {
            $kegiatan = KegiatanTbl::where('id', $id)
                ->update([
                    'nama_kegiatan' => $nama_kegiatan,
                    'lokasi' => $lokasi,
                    'keterangan' => $keterangan,
                    'tgl_kegiatan' => date('Y-m-d', strtotime($tgl_kegiatan)),
                    'foto_kegiatan' => $filefoto_kegiatan,
                    'user_id' => $user_id,
            ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Disimpan');

            return redirect::to('/spr/landingpage/kegiatan');
        } catch (\Throwable $t) {
            Alert::error('Gagal', 'Data Gagal Disimpan');

            DB::rollback();
            return redirect::to('/spr/landingpage/kegiatan');
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
        DB::beginTransaction();

        try {
            DB::table('lp_kegiatan')->where('id',$id)->delete();

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Dihapus');

            return redirect::to('/spr/landingpage/kegiatan');
        } catch (\Throwable $t) {
            dd($t);
            Alert::error('Gagal', 'Data Gagal Dihapus');

            DB::rollback();
            return redirect::to('/spr/landingpage/kegiatan');
        }
    }
}
