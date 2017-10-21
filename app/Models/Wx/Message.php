<?php

namespace App\Models\Wx;
use DB;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
	public static function submit_msg($obj){
		$id = DB::table('comment')->insertGetId(
			['note_id' => $obj['noteId'], 'content' => $obj['content'],'create_user_id'=>$obj['cid'],
			'create_time'=>$obj['time'],'parent_id'=>$obj['reply_id'],'reply_user_id'=>$obj['userId'],
			'create_user_name'=>$obj['name'],'reply_name'=>$obj['reply_name']]);
		return $id;
	}
	//id=>(评论的id),type=>(-1删除的是一级评论，0<=type代表二级评论)、nid=>(帖子ID)
	public static function delete_msg($id,$type,$nid){
		if($type==-1){
			$res = DB::table('comment')->where('id',$id)->update(['is_delete' => 1]);
			if($res==1){
				$result = DB::table('comment')->where('parent_id',$id)->update(['is_delete' => 1]);
				self::del_comment($nid,$result+1);
			}else
				return -1;
		}else{
			$res = DB::table('comment')->where('id',$id)->update(['is_delete' => 1]);
			if($res==1)
				self::del_comment($nid,1);
			else
				return -1;
		}
		return $res;
	}

	public static function get_msg($id,$num,$limit){
		$data = DB::table('comment')->where([['is_delete',0],['parent_id',0],['note_id',$id]])
			->select('comment.id', 'content','create_user_id as cid','comment.create_time as time','create_user_name as name')
			->orderBy('comment.create_time', 'desc')->offset($num)->limit($limit)->get();
		$msg_id = [];
		$time = time();
		foreach ($data as $key => $value) {
			$value->time = time_diff($time,$value->time);
			$msg_id[] = $value->id;
			$value->reply = [];
		}
		$response = DB::table('comment')->where([['is_delete',0],['parent_id','<>',0],['note_id',$id]])->whereIn('parent_id',$msg_id)
			->select('id','parent_id as reply_id', 'content','create_user_id as cid','comment.create_time as time','create_user_name as name','reply_name')
			->orderBy('comment.create_time', 'asc')->get();
		foreach ($response as $key => $value) {
			foreach ($data as $key => $val) {
				if($val->id==$value->reply_id){
					$value->time = time_diff($time,$value->time);
					$val->reply[] = $value;
				}
			}
		}
		return $data;
	}

	public static function msg_record($num,$limit,$id){
		$data = DB::table('comment')->where([['comment.is_delete',0],['comment.create_user_id',$id]])->leftJoin('note', 'note.id', '=', 'comment.note_id')->leftJoin('user', 'user.id', '=', 'note.create_user_id')
			->select('comment.id', 'comment.content','parent_id','comment.create_user_id as cid','comment.create_time as time','create_user_name as name','note.name as title','note.id as note_id','user.name as author')
			->orderBy('comment.create_time', 'desc')->offset($num)->limit($limit)->get();
		$time = time();
		foreach ($data as $key => $value) {
			$value->time = time_diff($time,$value->time);
		}
		return $data;
	}

	//得到消息记录
	public static function msg_remind($id){
		$data = DB::table('comment')->where([['comment.is_delete',0],['comment.reply_user_id',$id],['is_view',0]])->leftJoin('note', 'note.id', '=', 'comment.note_id')->leftJoin('user', 'user.id', '=', 'comment.create_user_id')
			->select('comment.id','parent_id','comment.content','comment.create_user_id as cid','comment.create_time as time','create_user_name as name','note.name as title','note.id as note_id','user.name as author')
			->orderBy('comment.create_time', 'desc')->get();
		$time = time();
		foreach ($data as $key => $value) {
			$value->time = time_diff($time,$value->time);
		}
		return $data;
	}
	public static function msg_remind_scorll($num,$limit,$id){
		$data = DB::table('comment')->where([['comment.is_delete',0],['comment.reply_user_id',$id],['is_view',1]])->leftJoin('note', 'note.id', '=', 'comment.note_id')->leftJoin('user', 'user.id', '=', 'comment.create_user_id')
			->select('comment.id', 'comment.content','parent_id','comment.create_user_id as cid','comment.create_time as time','create_user_name as name','note.name as title','note.id as note_id','user.name as author')
			->orderBy('comment.create_time', 'desc')->offset($num)->limit($limit)->get();
		$time = time();
		foreach ($data as $key => $value) {
			$value->time = time_diff($time,$value->time);
		}
		return $data;
	}
	//更新消息记录的阅读状态
	public static function read_msg($data){
		$res =   DB::table('comment')->whereIn('id',$data)->update(['is_view' => 1]);
		return $res;
	}
	//更新用户未读信息的数据
	public static function msg_count($id){
		$res = DB::table('comment')->where([['comment.is_delete',0],['comment.reply_user_id',$id],['is_view',0]])->count();
		return $res;
	}
	//减少阅读量
	public static function del_comment($id,$num){
		$res = DB::table('note')->where('note.id',$id)->decrement('comment_num',$num);
		return $res;
	}
}
