<?php

namespace App\Http\Controllers\Wx;

use Response;
use App\Models\Wx\Note;
use App\Models\Wx\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
	public $limit = 6;
	public function submit_msg(Request $request){
		$data = $request->all();
		$user = get_session_user();
		$data['cid'] = $user->id;
		$data['name'] = $user->name;
		$data['time'] = time();
		if(!empty($data['noteId'])&&!empty($data['content'])){
			$id = Message::submit_msg($data);
			Note::add_comment($data['noteId']);
			$data['id'] = $id;
			$data['reply'] = [];
			$data['time'] = '2秒前';
			return responseToJson($id, $data,'success');
		}else{
			return responseToJson(0, "非法操作",'error');
		}
	}
	public function get_msg(Request $request,$id){
		$limit = $this->limit;
		$num = $limit*$request->page;
		$data = Message::get_msg($id,$num,$limit);
		return responseToJson($data->count(), $data,'success');
	}
	public function msg_record(Request $request){
		$limit = $this->limit;
		$user = get_session_user();
		$num = $limit*$request->page;
		$data = Message::msg_record($num,$limit,$user->id);
		return responseToJson($data->count(), $data,'success');
	}
	public function msg_remind(Request $request){
		$limit = $this->limit;
		$user = get_session_user();
		$num = $limit*$request->page;
		$data = Message::msg_remind($user->id);
		return responseToJson($data->count(), $data,'success');
	}
	public function msg_remind_scorll(Request $request){
		$limit = $this->limit;
		$user = get_session_user();
		$num = $limit*$request->page;
		$data = Message::msg_remind_scorll($num,$limit,$user->id);
		return responseToJson($data->count(), $data,'success');
	}
}
