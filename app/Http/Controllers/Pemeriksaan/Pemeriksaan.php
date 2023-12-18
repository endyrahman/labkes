<?php

namespace App\Http\Controllers\Pemeriksaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Auth;
use DB;
use App\Models\Antrian\Antrian;
use App\Models\PemeriksaanTbl\PemeriksaanTbl;
use App\Models\JenisPemeriksaan\JenisPemeriksaan;
use Barryvdh\DomPDF\Facade as PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use RealRashid\SweetAlert\Facades\Alert;

class Pemeriksaan extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function gridPemeriksaanLab() {
        $data = DB::table('view_pemeriksaan')->get();

        return view('pemeriksaan.index', compact('data'));
    }
}
