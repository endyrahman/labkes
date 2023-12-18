<?php

namespace App\Models\Landingpage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class SlideMdl extends Model
{
    use HasFactory;

    protected $table = 'lp_slide';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'nama',
        'file',
        'urutan',
        'status',
        'user_id',
        'created_at',
        'updated_at',
    ];
}
