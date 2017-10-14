<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use App\Models\Admin\Menu;

class MenuController extends Controller
{
    function get_menu()
    {
        return Response::json(Menu::get_format_menu());
    }
}
