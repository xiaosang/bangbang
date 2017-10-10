<?php

namespace App\Models\Wx;
use DB;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
	public static function submit_msg($obj){
		$id = DB::table('comment')->insertGetId(
			['note_id' => $obj['noteId'], 'content' => $obj['content'],'create_user_id'=>$obj['cid'],
			'create_time'=>$obj['time'],'reply_id'=>$obj['reply_id'],'reply_user_id'=>$obj['userId'],
			'user_name'=>$obj['name'],'reply_name'=>$obj['reply_name']]);
		return $id;
	}

	public static function get_msg($id,$num,$limit){
		$data = DB::table('comment')->where([['is_delete',0],['reply_id',0],['note_id',$id]])
			->select('comment.id', 'content','create_user_id as cid','comment.create_time as time','user_name as name')
			->orderBy('comment.create_time', 'desc')->offset($num)->limit($limit)->get();
		$msg_id = [];
		foreach ($data as $key => $value) {
			$value->time = date("Y-m-d H:i",$value->time);
			$msg_id[] = $value->id;
			$value->reply = [];
		}
		$response = DB::table('comment')->where([['is_delete',0],['reply_id','<>',0],['note_id',$id]])->whereIn('reply_id',$msg_id)
			->select('id','reply_id', 'content','create_user_id as cid','comment.create_time as time','user_name as name','reply_name')
			->orderBy('comment.create_time', 'asc')->get();
		foreach ($response as $key => $value) {
			foreach ($data as $key => $val) {
				if($val->id==$value->reply_id){
					$value->time = date("Y-m-d H:i:s",$value->time);
					$val->reply[] = $value;
				}
			}
		}
		return $data;
	}
}
