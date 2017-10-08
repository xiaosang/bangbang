<?php

namespace App\Http\Controllers\Wx;

use Response;
use App\Models\Wx\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
	public function submit_msg(Request $request){
		$data = $request->all();
		$user = get_session_user();
		$data['cid'] = $user->id;
		$data['name'] = $user->name;
		$data['time'] = time();
		if(!empty($data['noteId'])&&!empty($data['content'])){
			$id = Message::submit_msg($data);
			$data['id'] = $id;
			$data['reply'] = [];
			$data['time'] = date('Y-m-d H:i');
			return responseToJson($id, $data,'success');
		}else{
			return responseToJson(0, "非法操作",'error');
		}
	}
	public function get_msg(Request $request,$id){
		$limit = 6;
		$num = $limit*$request->page;
		$data = Message::get_msg($id,$num,$limit);
		return responseToJson($data->count(), $data,'success');
	}
}
