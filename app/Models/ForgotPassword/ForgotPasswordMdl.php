<?php

namespace App\Models\ForgotPassword;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForgotPasswordMdl extends Model
{
    use HasFactory;

    protected $table = 'token_forgot_password';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'no_hp',
        'token',
        'otp',
        'exp_date',
        'status',
        'created_at',
        'updated_at'
    ];
}
