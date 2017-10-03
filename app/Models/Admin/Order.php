<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;

class Order extends Model
{

    public static function get_uorder_list($page_size = 50,$status = -1,$type = -1,$startDate = 0,$endDate = 0)
    {
        $sql = DB::table('transaction_order')->join('task','task.id','=','transaction_order.task_id')->where('transaction_order.is_delete',0);
        $sql = $sql -> select('transaction_order.*','task.name as task_name','task.content as task_content');
        if($status!=-1){
            $sql->where('transaction_order.status',$status);
        }
        if($type != -1){
            $sql->where('task.type',$type);
        }
        if($startDate != 0){
            $sql->where('transaction_order.create_time','>=',$startDate);
        }
        if($endDate != 0){
            $sql->where('transaction_order.create_time','<=',$endDate);
        }
        return $sql->paginate($page_size);
    }

    public static function uorder_delete($id)
    {
        DB::table('transaction_order')->where('id', $id)->update([
            'is_delete' => 1,
            'delete_at' => time()
        ]);

        return true;
        
    }

    public static function get_pay_order_list($page_size = 50,$status = -1,$type = -1,$startDate = 0,$endDate = 0)
    {
        $sql = DB::table('pay_order')->join('task','task.id','=','pay_order.task_id')->where('pay_order.is_delete',0);
        $sql = $sql -> select('pay_order.*','task.name as task_name','task.content as task_content');
        if($status!=-1){
            $sql->where('pay_order.status',$status);
        }
        if($type != -1){
            $sql->where('task.type',$type);
        }
        if($startDate != 0){
            $sql->where('pay_order.create_time','>=',$startDate);
        }
        if($endDate != 0){
            $sql->where('pay_order.create_time','<=',$endDate);
        }
        return $sql->paginate($page_size);
    }

    public static function pay_order_delete($id)
    {

        DB::table('pay_order')->where('id', $id)->update([
            'is_delete' => 1,
            'delete_at' => time()
        ]);

        return true;
    }

}
