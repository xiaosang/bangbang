<?php
//社交页面的控制器
namespace App\Http\Controllers\Wx;

use Response;
use App\Models\Wx\Note;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
		$limit = 2;
		$num = $request->page*$limit;
		$data = Note::get_note($num,$limit);
		return responseToJson($data->count(), $data,'success');
	}
}
