<?php
//社交页面的控制器
namespace App\Http\Controllers\Wx;

use Response;
use App\Models\Wx\Note;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ConnectController extends Controller
{
	public function set_note(Request $request){
		$obj = $request->all();
		$user = get_session_user();
		$obj['user_id'] = $user->id;
		if(empty($obj['title'])||empty($obj['content'])){
			return responseToJson(0, "文章内容不规范,发表失败",'error');
		}
		$id = Note::set_note($obj);
		return responseToJson(1, "文章发表成功",'success');
	}
	public function index(Request $request){
		$limit = 7;
		$type = $request->type;
		$num = $request->page*$limit;
		$data = Note::get_note($num,$limit,$type);
		return responseToJson($data->count(), $data,'success');
	}
	public function upload(Request $request){
		if(!empty($_FILES)){
			$img = $_FILES['img'];
			if($img['size']>8*1024*1024)
				return responseToJson(0, '上传文件过大，上传失败','success');
			$result = $request->file('img')->store('note/img');
			if($result){
				return responseToJson(1, $result,'success');
			}else{
				return responseToJson(0, "上传服务器失败",'error');
			}
		}else{
			return responseToJson(0, '非法操作','success');
		}
	}
	public function get_img(Request $request){
		return response()->file($request->img);
	}
	public function get_detail($id){
		Note::add_read($id);//阅读量增加
		$data = Note::get_detail($id);
		if(!empty($data)){
			return responseToJson(1, $data,'success');
		}else{
			return responseToJson(0, "不存在此文章",'error');
		}
	}
}
