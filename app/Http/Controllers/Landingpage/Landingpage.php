<?php

namespace App\Http\Controllers\Landingpage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Landingpage\MenuMdl;
use DB;

class Landingpage extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $menuMdl = new MenuMdl;

        $menu = $menuMdl->getMenu();
        $promosi = DB::table('pamflet')->paginate(3, ['*'], 'pagelppromosi');
        $kegiatan = DB::table('lp_kegiatan')->paginate(3, ['*'], 'pagehdkegiatan');
        $slide = DB::table('lp_slide')->get();
        $arrslide = [];

        foreach ($slide as $val) {
            array_push($arrslide, "storage/foto_slide/".$val->file);
        }

        return view('layouts.applanding', compact('menu', 'promosi', 'kegiatan', 'arrslide'));
    }

    public function indexLabKlinik() {
        $pemeriksaan = DB::table('parameter_pemeriksaan_klinik')->paginate(8, ['*'], 'pagepemeriksaan');

        return view('landingpage.submenu.klinik.index', compact('pemeriksaan'));
    }

    public function indexLabKimia() {
        $pemeriksaan = DB::table('parameter_pemeriksaan_kimia')->paginate(8, ['*'], 'pagepemeriksaan');

        return view('landingpage.submenu.kimia.index', compact('pemeriksaan'));
    }

    public function indexLabMikrobiologi() {
        $pemeriksaan = DB::table('parameter_pemeriksaan_mikrobiologi')->paginate(8, ['*'], 'pagepemeriksaan');

        return view('landingpage.submenu.mikrobiologi.index', compact('pemeriksaan'));
    }

    public function indexKegiatan() {
        $kegiatan = DB::table('lp_kegiatan')->paginate(9, ['*'], 'pagekegiatan');

        return view('landingpage.kegiatan.index', compact('kegiatan'));
    }

    public function paginationKegiatan(Request $request) {
        if($request->ajax()) {
            $kegiatan = DB::table('lp_kegiatan')->paginate(9, ['*'], 'pagekegiatan');

            return view('landingpage.kegiatan.list', compact('kegiatan'));
        }
    }

    public function paginationPemeriksaan(Request $request) {
        if($request->ajax()) {
            $laboratorium = $request->laboratorium;
            $pencarian = $request->pencarian;

            if ($laboratorium == 1) {
                $pemeriksaan = DB::table('parameter_pemeriksaan_klinik');
                $url = 'klinik';
            } elseif ($laboratorium == 2) {
                $pemeriksaan = DB::table('parameter_pemeriksaan_kimia');
                $url = 'kimia';
            } elseif ($laboratorium == 3) {
                $pemeriksaan = DB::table('parameter_pemeriksaan_mikrobiologi');
                $url = 'mikrobiologi';
            }

            if ($pencarian) {
                $pemeriksaan->where('nama_parameter', 'like', '%'.$pencarian.'%');
            }

            $pemeriksaan = $pemeriksaan->paginate(8, ['*'], 'pagepemeriksaan');

            return view('landingpage.submenu.'.$url.'.list', compact('pemeriksaan'))->render();
        }
    }

    public function homecare() {

        return view('landingpage.submenu.homecare');
    }

    public function kontak() {

        return view('landingpage.kontak.index');
    }

    public function paginationLpPromosi(Request $request) {
        if($request->ajax())
        {
            $promosi = DB::table('pamflet')->paginate(3, ['*'], 'pagelppromosi');

            return view('landingpage.halamandepan.promosi.list', compact('promosi'))->render();
        }
    }

    public function paginationHdKegiatan(Request $request) {
        if($request->ajax())
        {
            $kegiatan = DB::table('lp_kegiatan')->paginate(3, ['*'], 'pagehdkegiatan');

            return view('landingpage.halamandepan.kegiatan.list', compact('kegiatan'))->render();
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
