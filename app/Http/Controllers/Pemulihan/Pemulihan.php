<?php

namespace App\Http\Controllers\Pemulihan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Pemulihan extends Controller
{
    public function index() {

        return view('pemulihan.index');
    }

    public function storePendaftaran(Request $request) {

    }
}
