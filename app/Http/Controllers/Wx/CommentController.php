<?php

namespace App\Http\Controllers\Wx;

use Response;
use App\Models\Wx\Note;
use App\Events\PusherEvent;
use App\Models\Wx\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
	public $limit = 6;
	//帖子id、信息、被回复用户id、回复帖子的id、回复姓名
	public function submit_msg(Request $request){
		$data = $request->all();
		$user = get_session_user();
		if($data['userId']>0){
			if($data['userId']!=$user->id)
				event(new PusherEvent(['userId'=>$data['userId']]));
		}else{
			$note = Note::get_a_note($data['noteId']);
			$data['userId'] = $note[0]->uid;
			$data['reply_name'] = $note[0]->name;
		}
		if($data['userId']==$user->id)
			$data['is_view'] = 1;
		$data['cid'] = $user->id;
		$data['name'] = $user->nick_name;
		$data['time'] = time();
		if(!empty($data['noteId'])&&!empty($data['content'])){
			$id = Message::submit_msg($data);
			Note::add_comment($data['noteId']);
			$data['id'] = $id;
			$data['reply'] = [];
			$data['time'] = '2秒前';
			$data['avatar'] = $user->avatar;
			return responseToJson($id, $data,'success');
		}else{
			return responseToJson(0, "非法操作",'error');
		}
	}
	public function delete_msg(Request $request){
		$res = Message::delete_msg($request->id,$request->type,$request->nId);
		if($res>-1){
			return responseToJson(1,'true','success');
		}else
			return responseToJson(0,'false','success');
	}
	public function get_msg(Request $request,$id){
		$limit = $this->limit;
		$num = $limit*$request->page;
		$data = Message::get_msg($id,$num,$limit);
		return responseToJson($data->count(), $data,'success');
	}
	//回复通知页面
	public function msg_record(Request $request){
		$limit = $this->limit;
		$user = get_session_user();
		$num = $limit*$request->page;
		$data = Message::msg_record($num,$limit,$user->id);
		return responseToJson($data->count(), $data,'success');
	}
	//消息通知页面
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
	public function read_msg(Request $request){
		$res = Message::read_msg($request->data);
		return responseToJson($res,'success','success');
	}
	//得到未读消息的数量
	public function msg_count(){
		$user = get_session_user();
		$res = Message::msg_count($user->id);
		return responseToJson($res,'success','success');
	}
}
