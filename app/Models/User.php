<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use DB;
use Auth;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nama_lengkap',
        'alamat_lengkap',
        'email',
        'no_hp',
        'nik',
        'tempat_lahir',
        'tgl_lahir',
        'role_id',
        'jenis_kelamin',
        'jenis_pelanggan',
        'password',
        'verifikasi_daftar',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getDataUser($user_id, $role_id) {
        $data = DB::table('users');
        if ($role_id != 1) {
            $data->where('id', $user_id);
            $data = $data->get();
        } else {
            $data = $data->get();
        }

        return $data;
    }

    public function getDataUserById($user_id) {
        $data = DB::table('users')
            ->where('id', $user_id)
            ->first();

        return $data;
    }
}
