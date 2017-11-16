<?php
//社交页面的控制器
namespace App\Http\Controllers\Wx;

use Response;
use App\Models\Wx\Note;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ConnectController extends Controller
{
	public $limit = 7;	//得到帖子的记录数
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
		$limit = $this->limit;
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
	//请求图片的路由
	public function get_img(Request $request){
		return response()->file($request->img);
	}
	//得到帖子的详细信息
	public function get_detail($id){
		$data = Note::get_detail($id);
		if(!empty($data)){
			return responseToJson(1, $data,'success');
		}else{
			return responseToJson(0, "不存在此文章",'error');
		}
	}
	//得到用户回复帖子记录
	public function note_record(Request $request){
		$limit = $this->limit;
		$user = get_session_user();
		$num = $request->page*$limit;
		$data = Note::note_record($num,$limit,$user->id);
		return responseToJson($data->count(), $data,'success');
	}
	//得到用户的信息
	public function user_info(){
		$user = get_session_user();
		 return responseToJson($user->id, "message",'success');
	}
	//删除帖子的信息
	public function delete_note(Request $request){
		$res = Note::delete_note($request->id);
		if($res)
			return responseToJson(1, "note",'success');
		else
			return responseToJson(0, "note",'error');
	}
	//帖子的敏感信息
	public function sensitive(Request $request){
		return responseToJson(0, "失败，含有敏感词：".$request->word,'error');
	}
}
