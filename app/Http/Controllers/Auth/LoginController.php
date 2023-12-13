<?php

namespace App\Http\Controllers\Auth;

use Redirect;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $input = $request->all();

        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'no_hp';

        if (auth()->attempt(array($fieldType => $input['username'], 'password' => $input['password']))) {
            Alert::success('Berhasil Login', 'Selamat datang di halaman dashboard register online Labkes Kota Semarang');

            if (Auth::user()->role_id == 4) {
                return redirect::to('dashboard');
            } elseif (Auth::user()->role_id == 1) {
                return redirect::to('/spr/dashboard');
            } else {
                return redirect::to('/');
            }
        } else {
            Alert::error('Gagal Login', 'Periksa kembali username dan password');

            return redirect::to('/');
        }

    }
}
