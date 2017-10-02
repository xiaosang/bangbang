<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\User;
use App\Models\Admin\Role;
use App\Models\WxLogin;

use Response;
use DB;

class UserController extends Controller
{
    //
    function get_user_list_paginate(Request $request){
        $page = intval($request->page);
        $pageSize = intval($request->pageSize);
        $users = User::get_user_list_paginate($page,$pageSize);
        return Response::json($users);
    }

    function get_role_list(){
        return Role::get_role_list();
    }

    //编辑用户信息时用
    function get_user_info(Request $request){
        $id = intval($request->id);
        $info = User::get_user_info($id);
        return Response::json($info);
    }

    function relieve(Request $request)
    {
        $object_id = $request->object_id;
        $type = $request->type;
        $info = User::relieve($object_id,$type);
        if($info){
            $l_session = DB::table('user')->where(['object_id'=>$object_id,'type'=>$type])->select('login_session_wx')->first();
            WxLogin::destroy_session($request->session(),$l_session->login_session_wx);
        }
        return Response::json($info);
    }

    function add(Request $request){
        $id = intval($request->id);
        $data['name'] = $request->name;
        $data['phone'] = $request->phone;
        $data['role_id'] = $request->role_id;

        if(strlen($data['name']) <=5 && strlen($data['name']) >= 20){
            return Response::json(['status'=>1,'msg'=>'用户名长度需在5~20位']);
        }
        if(!$data['role_id']){
            return Response::json(['status'=>1,'msg'=>'需要选择用户角色']);
        }

        //用户名不能重复
        if (!User::is_unique_name($data['name'],$id)) {
            return Response::json(['status' => 1, 'msg' => '用户名已存在，请更改']);
        }

        if(User::save_user($id,$data)){
            return Response::json(['status'=>0,'msg'=>'保存成功']);
        }else{
            return Response::json(['status'=>1,'msg'=>'保存失败，请重试']);
        }
    }

    function reset_pwd(Request $request){
        $id = intval($request->id);
        if(User::reset_pwd($id)){
            return Response::json(['status'=>0,'msg'=>'密码重置为“123456”']);
        }else{
            return Response::json(['status'=>1,'msg'=>'重置失败，请重试']);
        }
    }

    function soft_delete(Request $request){
        $id = intval($request->id);
        if(User::soft_delete($id)){
            return Response::json(['status'=>0,'msg'=>'删除成功']);
        }else{
            return Response::json(['status'=>1,'msg'=>'删除失败，请重试']);
        }
    }

    function password(Request $resquest){
        $old_pwd = $resquest->old_pwd;
        $new_pwd = $resquest->new_pwd;
        $re_pwd = $resquest->re_pwd;

        if (strlen($new_pwd) < 5 || strlen($new_pwd) > 20) {
            return Response::json(['status' => 0, 'msg' => '密码长度需在5~20位']);
        }
        if ($new_pwd != $re_pwd) {
            return Response::json(['status' => 0, 'msg' => '新密码与重复密码不同']);
        }

        if(!User::is_password_right($old_pwd)){
            return Response::json(['status' => 0, 'msg' => '原密码输入错误']);
        }

        $res = User::change_password($new_pwd);
        if ($res !== false) {
            return Response::json(['status' => 1, 'msg' => '更改成功']);
        } else {
            return Response::json(['status' => 0, 'msg' => '更改失败，请重试']);
        }
    }
}
