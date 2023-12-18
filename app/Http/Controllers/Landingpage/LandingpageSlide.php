<?php

namespace App\Http\Controllers\Landingpage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Redirect;
use App\Models\Landingpage\SlideMdl;
use Auth;
use DB;

class LandingpageSlide extends Controller
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
        $slide = DB::table('lp_slide')->paginate(10, ['*'], 'pageslide');

        return view('backend.landingpage.halamandepan.slide.index', compact('slide'));
    }

    public function paginationHeader(Request $request) {
        if($request->ajax()) {
            $combopencarian = $request->combopencarian;
            $pencarian = $request->pencarian;

            $slide = DB::table('lp_slide');

            if ($pencarian) {
                $slide->where($combopencarian, 'like', '%'.$pencarian.'%');
            }

            $slide = $slide->paginate(10, ['*'], 'pageslide');

            return view('backend.landingpage.halamandepan.slide.list', compact('slide'))->render();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.landingpage.halamandepan.slide.create');
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
        $foto_slide = $request->file('foto_slide');

        $pathfoto_slide = $foto_slide->store('public/foto_slide');
        $flfoto_slide = explode('/', $pathfoto_slide);
        $filefoto_slide = $flfoto_slide[2];

        DB::beginTransaction();

        try {
            $kegiatan = SlideMdl::create([
                'nama' => $nama,
                'file' => $filefoto_slide,
                'status' => $status,
                'user_id' => $user_id
            ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Disimpan');

            return redirect::to('/spr/landingpage/slide');
        } catch (\Throwable $t) {
            dd($t);
            Alert::error('Gagal', 'Data Gagal Disimpan');

            DB::rollback();
            return redirect::to('/spr/landingpage/slide');
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
        $slide = SlideMdl::find($id);

        return view('backend.landingpage.halamandepan.slide.edit', compact('slide'));
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
        $nama = $request->nama;
        $status = $request->status;
        $foto_slide = $request->file('foto_slide');

        if ($foto_slide) {
            $pathfoto_slide = $foto_slide->store('public/foto_slide');
            $flfoto_slide = explode('/', $pathfoto_slide);
            $filefoto_slide = $flfoto_slide[2];
        } else {
            $dtslide = SlideMdl::where('id', $id)->select('file')->first();
            $filefoto_slide = $dtslide->file;
        }

        DB::beginTransaction();

        try {
            $slide = SlideMdl::where('id', $id)
                ->update([
                    'nama' => $nama,
                    'status' => $status,
                    'file' => $filefoto_slide
            ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Disimpan');

            return redirect::to('/spr/landingpage/slide');
        } catch (\Throwable $t) {
            dd($t);
            Alert::error('Gagal', 'Data Gagal Disimpan');

            DB::rollback();
            return redirect::to('/spr/landingpage/slide');
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
            DB::table('lp_slide')->where('id',$id)->delete();

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Dihapus');

            return redirect::to('/spr/landingpage/slide');
        } catch (\Throwable $t) {
            dd($t);
            Alert::error('Gagal', 'Data Gagal Dihapus');

            DB::rollback();
            return redirect::to('/spr/landingpage/slide');
        }
    }
}
