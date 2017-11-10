<?php

namespace App\Http\Controllers\Wx;

use App\Http\Controllers\Controller;
use App\Models\Wx\News;
use Illuminate\Http\Request;

/**
 * Class NewsController
 * @package App\Http\Controllers\Wx
 * 订单消息模型
 */
class NewsController extends Controller
{
    /**
     * 检测是当前登录账户是否有未读消息
     */
    function check_unread(){
        $user_id = get_wx_user_id();
        $result = News::check_unread($user_id);
        return responseToJson(1,'获取成功',$result);
    }

    /**
     * 获取消息列表
     */
    function news_list(Request $request){
        $user_id = get_wx_user_id();
        $unread = $request->input('unread');
        $page = $request->input('page');
        $num = $request->input('num');
        if($unread==-1||$unread==0){
            News::change_unread_to_read($user_id,$page,$num);
        }
        $result = News::news_list($user_id,$unread,$page,$num);
        if($result){
            return responseToJson(1,'获取成功',$result);
        }else{
            return responseToJson(0,'获取失败');
        }
    }


}