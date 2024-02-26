<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use RealRashid\SweetAlert\Facades\Alert;

class Pengguna extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $users = Auth::user();

        if ($users->role_id == 1) {
            $data = $users->getDataUser($users->id, $users->role_id);
        } else {
            $data = $users->getDataUser($users->id, $users->role_id);
        }

        return view('pengguna.index', compact('data'));
    }

    public function edit($user_id) {
        $users = Auth::user();

        if ($user_id != Auth::id() and $users->role_id != 1) {
            return view('404.index');
        }

        $user = $users->getDataUserById(Auth::id());

        return view('pengguna.edit', compact('user'));
    }

    public function updatepengguna(Request $request)
    {
        $user_id = $request->user_id;
        $email = $request->email;
        $no_hp = $request->no_hp;
        $password = $request->password;

        DB::beginTransaction();

        try {
            User::find($user_id)
                ->update([
                    'email' => $email,
                    'no_hp' => $no_hp,
                    'password' => Hash::make($password)
                ]);

            DB::commit();

            Alert::success('Berhasil', 'Data Berhasil Disimpan');

            return redirect::to('/pengguna');
        } catch (\Throwable $t) {
            dd($t);
            Alert::error('Gagal', 'Data Gagal Disimpan');

            DB::rollback();
            return redirect::to('/pengguna');
        }
    }
}
