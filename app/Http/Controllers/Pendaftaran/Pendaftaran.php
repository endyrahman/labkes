<?php

namespace App\Http\Controllers\Pendaftaran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\RegistersUsers;

class Pendaftaran extends Controller
{
    use RegistersUsers;

    public function index() {

        return view('pendaftaran.index');
    }

    public function storePendaftaran(Request $request) {

        DB::beginTransaction();

        $jenis_pelanggan = $request->jenis_pelanggan;
        $nama_lengkap = $request->nama_lengkap;
        $alamat_lengkap = $request->alamat_lengkap;
        $email = $request->email;
        $no_hp = $request->no_hp;
        $nik = $request->nik;
        $tempat_lahir = $request->tempat_lahir;
        $tgl_lahir = $request->tgl_lahir;
        $jenis_kelamin = $request->jenis_kelamin;
        $password = $request->password;
        $verifikasi_daftar = $request->verifikasi_daftar;
        $role_id = 4;

        try {
            $pengguna = User::create([
                "name" => ucfirst($nama_lengkap),
                "jenis_pelanggan" => $jenis_pelanggan,
                "nama_lengkap" => ucfirst($nama_lengkap),
                "alamat_lengkap" => $alamat_lengkap,
                "email" => $email,
                "no_hp" => $no_hp,
                "nik" => $nik,
                "role_id" => 4,
                // "tempat_lahir" => $tempat_lahir,
                // "tgl_lahir" => date('Y-m-d', strtotime($tgl_lahir)),
                // "jenis_kelamin" => $jenis_kelamin,
                "password" => Hash::make($password),
                // "verifikasi_daftar" => $verifikasi_daftar,
                "created_at" => date('Y-m-d h:i:s'),
                "updated_at" => date('Y-m-d h:i:s')
            ]);

            DB::commit();

            if (auth()->attempt(array('no_hp' => $no_hp, 'password' => $password))) {
                Alert::success('Selamat Datang', 'Pendaftaran berhasil disimpan');

                return redirect::to('dashboard');
            } else {
                Alert::error('Gagal', 'Data Gagal Disimpan');

                return redirect::to('login');
            }
        } catch (\Throwable $t) {
            dd($t);
            DB::rollback();

            Alert::error('Gagal', 'Data Gagal Disimpan');
            return redirect::to('login');
        }
    }

    protected function redirectTo()
    {
        /* generate URL dynamicaly */
         return '/dashboard';
    }

    public function cekNoHp(Request $request) {
        $no_hp = $request->no_hp;

        if ($no_hp) {
            $user = User::where('no_hp', $no_hp)->first();

            if ($user) {
                $result['status'] = '200';
            } else {
                $result['status'] = '204';
            }
        } else {
            $result['status'] = '204';
        }

        return response()->json($result);
    }

    public function cekNik(Request $request) {
        $nik = $request->nik;

        if ($nik) {
            $user = User::where('nik', $nik)->first();

            if ($user) {
                $result['status'] = '200';
            } else {
                $result['status'] = '204';
            }
        } else {
            $result['status'] = '204';
        }

        return response()->json($result);
    }

    public function cekEmail(Request $request) {
        $email = $request->email;

        if ($email) {
            $user = User::where('email', $email)->first();

            if ($user) {
                $result['status'] = '200';
            } else {
                $result['status'] = '204';
            }
        } else {
            $result['status'] = '204';
        }

        return response()->json($result);
    }
}
