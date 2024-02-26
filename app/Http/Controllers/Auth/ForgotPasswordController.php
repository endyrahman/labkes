<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use App\Models\ForgotPassword\ForgotPasswordMdl;
use App\Models\User;
use Auth;
use DB;
use RealRashid\SweetAlert\Facades\Alert;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function lupaPassword(Request $request) {
        $no_hp = $request->no_hp;
        $no_wa = $no_hp;

        $users = DB::table('users')->where([['no_hp', $no_hp],['role_id',4]])->first();
        $user_id = $users->id;

        if ($users) {
            $tokenotp = $this->generateRandomString();
            $otp = $randomNumber = rand(1000, 9999);

            $uForgotpassword = ForgotPasswordMdl::where('user_id', $user_id)
                ->update([
                    "status" => 1
                ]);

            $forgotpassword = ForgotPasswordMdl::create([
                'user_id' => $users->id,
                'no_hp' => $no_hp,
                'token' => $tokenotp,
                'otp' => $otp,
                'exp_date' => date('Y-m-d H:i:s', strtotime('+30 minutes')),
                'status' => null
            ]);

            $count = strlen((string) $no_hp);

            if (substr($no_hp, 0, 2) != 62) {
                $no_hp = substr($no_hp, 1, (int) $count);
                $no_hp = '62'.$no_hp;
            }

            $curl = curl_init();

            $token = "lh1yD6Bbzf5SeJGUf0qHuXtciUuTfez0LTafkEzxnhP4J2Ez9uhYQHmLMuUWWym5";

            $payload = [
                "data" => [
                    [
                        'phone' => $no_hp,
                        'message' => [
                            'title' => [
                                'type' => 'text',
                                'content' => 'OTP Lupa Password Labkes Kota Semarang',
                            ],
                            'buttons' => [
                                'url' => [
                                    'display' => 'Copy',
                                    'link' => "https://www.whatsapp.com/otp/copy/".$otp,
                                ],
                            ],
                            'content' => "Kode OTP lupa password : ".$otp,
                            'footer' => "UPTD Laboratorium Kesehatan, Cepat, Terjangkau, Informatif, Akurat",
                        ],
                    ]
                ]
            ];
            curl_setopt($curl, CURLOPT_HTTPHEADER,
                array(
                    "Authorization: $token",
                    "Content-Type: application/json"
                )
            );
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload) );
            curl_setopt($curl, CURLOPT_URL,  "https://jogja.wablas.com/api/v2/send-template");
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

            $result = curl_exec($curl);
            curl_close($curl);

            return view('auth.lupapassword', compact('tokenotp', 'no_wa', 'user_id'));
        }
    }

    public function resetPassword(Request $request) {
        $username = $request->username;
        $tokenotp = $request->tokenotp;
        $user_id = $request->user_id;
        $password = $request->password;

        DB::beginTransaction();
        
        try {
            $cek = DB::table('token_forgot_password')->where([['no_hp', $username],['user_id', $user_id],['token', $tokenotp],['exp_date', '>', date('Y-m-d H:i:s')],['status', null]])->first();

            $uForgotpassword = ForgotPasswordMdl::where('user_id', $user_id)
                ->update([
                    "status" => 1
                ]);

            if ($cek) {
                $users = User::where('id', $user_id)
                    ->update([
                        "password" => Hash::make($request->password)
                    ]);

                DB::commit();

                $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'no_hp';

                if (auth()->attempt(array($fieldType => $username, 'password' => $password))) {
                    Alert::success('Berhasil Login', 'Selamat datang di halaman dashboard register online Labkes Kota Semarang');

                    if (Auth::user()->role_id == 4) {
                        return redirect::to('dashboard');
                    } elseif (Auth::user()->role_id == 1) {
                        return redirect::to('/spr/dashboard');
                    } else {
                        return redirect::to('/');
                    }
                } else {
                    Alert::error('Gagal Reset Password', 'Ulangi kembali ubah password');

                    return redirect::to('/');
                }
            }
        } catch (\Throwable $t) {
            DB::rollback();

            Alert::error('Gagal Reset Password', 'Ulangi kembali ubah password');

            return redirect::to('/');
        }
    }

    public function generateRandomString()
    {
        $length = 9;
        $str = "";

        for ($i = 0; $i < $length; $i++) {
            $rand_num = rand(0, 1); // Randomly select 0 or 1
            if ($rand_num == 0) {
                $rand_char = rand(97, 122); // ASCII values for lowercase alphabets
            } else {
                $rand_char = rand(65, 90); // ASCII values for uppercase alphabets
            }
            $str .= chr($rand_char);
        }

        return $str;
    }
}
