<?php

namespace App\Models\StatusWa;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusWa extends Model
{
    use HasFactory;

    protected $table = 'status_wa';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'pemeriksaan_id',
        'no_hp',
        'messages_id',
        'created_at',
        'updated_at'
    ];
}
