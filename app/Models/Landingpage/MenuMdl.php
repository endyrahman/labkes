<?php

namespace App\Models\Landingpage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class MenuMdl extends Model
{
    use HasFactory;

    protected $table = 'lp_menu';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nama_menu',
        'urutan',
        'url',
        'status'
    ];

    public static function getMenu() {
        $menu = DB::table('lp_menu')->get();

        foreach ($menu as $key => $val) {
            $sub_menu = DB::table('lp_sub_menu')->where('menu_id', $val->id)->get();
            $menu[$key]->sub_menu = $sub_menu;
        }

        return $menu;
    }

    public static function getMenuHalamanDepan() {
        $halamandepan = DB::table('lp_halaman_depan')->get();

        return $halamandepan;
    }
}
