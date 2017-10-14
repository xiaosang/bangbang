<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;

class Menu extends Model
{

    private static function format_menu($menus)
    {
        $data = [];
        foreach ($menus as $v) {
            if ($v->depth == 1) {
                $data[$v->id] = $v;
            }
        }
        //使sort_factor起作用，分两次循环（仅用code排序时，循环一次就够了）
        foreach ($menus as $v) {
            if ($v->depth == 2) {
                $data[$v->pid]->children[] = $v;
            }
        }
        return array_values($data);
    }

    static function get_format_menu()
    {
        $menus = self::get_menu();
        return self::format_menu($menus);
    }

    static function get_menu()
    {
        return DB::table('node')->where(['type'=>0,'status'=>0])
        ->orderBy('sort_factor')->get();
    }
}
