<?php

namespace App\Http\Controllers\Admin\Loafo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use App\Models\Admin\Loafo;

class IndexController extends Controller
{


    function get_list(Request $request)
    {
        $page_size = $request->page_size;
        return responseToJson(0, 'success', Loafo::get_list($page_size,$request->keyword));
    }


    function loafo_delete(Request $request)
    {

        $id = $request->id;
        $res = Loafo::loafo_delete($id);
        if ($res) {
            return responseToJson(0, "删除成功");
        } else {
            return responseToJson(1, "删除失败");
        }

    }

}
