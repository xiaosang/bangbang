<?php

namespace App\Http\Controllers\Admin\Notice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use App\Models\Admin\Notice;

class IndexController extends Controller
{

    function get(Request $request)
    {
        return responseToJson(0, 'success', Notice::get($request->id));
    }

    function get_list(Request $request)
    {
        $page_size = $request->page_size;
        return responseToJson(0, 'success', Notice::get_list($page_size,$request->keyword));
    }

    function edit(Request $request)
    {
        $req = [];
        $req['id'] = $request->id;
        $req['content'] = $request->content;
//        dd(Notice::edit($req));
        if(Notice::edit($req))
            return responseToJson(0, '成功');
        else return responseToJson(1, '失败');
    }

    function notice_delete(Request $request)
    {

        $id = $request->id;
        $res = Notice::notice_delete($id);
        if ($res) {
            return responseToJson(0, "删除成功");
        } else {
            return responseToJson(1, "删除失败");
        }

    }

}
