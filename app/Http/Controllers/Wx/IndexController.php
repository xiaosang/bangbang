<?php

namespace App\Http\Controllers\Wx;

use App\Models\User;
use App\Models\Template;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class IndexController extends Controller
{

    public function index()
    {
        //依据角色判断跳转到哪里
        $user = get_wx_user();
        // dd(;
        if($user->is_v==1){
            return view('weixin.index');
        } else {
            return view('weixin.wx');
        }
    }

    public function send(Request $request){
        $code = sendMsg($request->phone);
        if($code == 0){
            return responseToJson(0, '发送成功');
        }else if($code == 2){
            return responseToJson(1, '已经发过');
            
        }else{
            return responseToJson(2, '服务器繁忙');
        }
    }

    public function check(Request $request){
        if(check_msg($request->code,$request->phone)){
            User::set_auth(get_wx_user_openid());
            $res = User::set_phone($request->phone);
            session(['user' => $res]);
            return responseToJson(0, '认证成功');
        }else{
            return responseToJson(1, '认证失败，验证码错误');
        }
    }

    public function test(){
        // return  "123";
    //    Template::test('ordUR0llJqpXNTh4yP-C0ofKX8gw','撕名牌提醒','周旭','开发一组','今天','7','加油加油');
    //    Template::task_stop('ordUR0llJqpXNTh4yP-C0ofKX8gw','您发布的任务到了截止时间已经被取消，如若还需帮忙，需要重新发布。','我是标题','无偿','2017-10-10 12：12：12','2017-10-10 12：12：12','如若任务类型为有偿任务，赏金将在二十四小时内到账，敬请等待。');
        Template::task_accept('ordUR0llJqpXNTh4yP-C0ofKX8gw','您发布的任务到了截止时间已经被取消，如若还需帮忙，需要重新发布。','我是标题','无偿','2017-10-10 12：12：12','感谢',"15937368751",'如还有什么需要，赶快联系接收人吧 ~');
    }




}