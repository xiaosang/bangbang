<?php

namespace App\Http\Controllers\Admin\Order;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use App\Models\Admin\Order;

class OrderController extends Controller
{


    function get_uorder_list(Request $request)
    {
        $page_size = $request->page_size;
        $startDate = $request->startDate;
        if($startDate != null){
            $startDate = time($startDate);
        }
        $endDate = $request->endDate;
        if($endDate != null){
            $endDate = time($endDate);
        }
        return responseToJson(0, 'success', Order::get_uorder_list($page_size,$request->status,$request->type,$startDate,$endDate));
    }

    function uorder_delete(Request $request){
        $id = $request->id;
        $res = Order::uorder_delete($id);
        if($res){
            return responseToJson(0,"删除成功");
        }else{
            return responseToJson(1, "删除失败");
        }
    }

    function get_pay_order_list(Request $request)
    {
        $page_size = $request->page_size;
        $startDate = $request->startDate;
        if($startDate != null){
            $startDate = time($startDate);
        }
        $endDate = $request->endDate;
        if($endDate != null){
            $endDate = time($endDate);
        }
        return responseToJson(0, 'success', Order::get_pay_order_list($page_size,$request->status,$request->type,$startDate,$endDate));
    }

    function pay_order_delete(Request $request){
        $id = $request->id;
        $res = Order::pay_order_delete($id);
        if($res){
            return responseToJson(0,"删除成功");
        }else{
            return responseToJson(1, "删除失败");
        }
    }


}
