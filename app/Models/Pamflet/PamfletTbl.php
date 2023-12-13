<?php

namespace App\Models\Pamflet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PamfletTbl extends Model
{
    use HasFactory;

    protected $table = 'pamflet';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'nama',
        'nama_file',
        'urutan',
        'status'
    ];
}
