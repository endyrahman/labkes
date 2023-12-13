<?php

namespace App\Http\Controllers\Parameter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Auth;
use DB;
use App\Models\PemeriksaanTbl\PemeriksaanTbl;
use App\Models\JenisPemeriksaan\JenisPemeriksaan;
use Barryvdh\DomPDF\Facade as PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use RealRashid\SweetAlert\Facades\Alert;

class Parameter extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function gridParameterPemeriksaan() {
        $data = DB::table('view_pemeriksaan')->get();

        return view('parameter.pemeriksaan.index', compact('data'));
    }
}
