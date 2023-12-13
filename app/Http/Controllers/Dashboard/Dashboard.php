<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PemeriksaanTbl\PemeriksaanTbl;
use Carbon\Carbon;
use DB;

class Dashboard extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $pamflet  = DB::table('pamflet')->get();

        foreach ($pamflet as $key => $val) {
            $foto['ft'.$val->urutan] = $val->nama_file;
            $foto['status'.$val->urutan] = $val->status;
        }

        return view('dashboard.index', compact('foto'));
    }

    public function adminIndex() {
        $pemeriksaan = DB::table('pemeriksaan')
                    ->selectRaw('sum(if(jenis_lab_id = 1, 1, 0)) as klinik, sum(if(jenis_lab_id = 2, 1, 0)) as kimia, sum(if(jenis_lab_id = 3, 1, 0)) as mikrobiologi')
                    ->first();

        return view('dashboard.admin.index', compact('pemeriksaan'));
    }
}
