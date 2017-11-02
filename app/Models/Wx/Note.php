<?php

namespace App\Models\Wx;
use DB;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
	static public $label = array("无","分享","讨论","提问");
	/*
		1、新建帖子的功能
		2、插入的数据
		3、帖子ID
	*/
	public static function set_note($obj){
		$time = time();
		$id = DB::table('note')->insert(
			['name' => $obj['title'], 'content' => $obj['content'],'label'=>$obj['type'],
			'create_time'=>$time,'create_user_id'=>$obj['user_id'],'update_time'=>$time]);
		return $id;
	}
	/*
		1、首页得到数据
		2、跳过的数据条数，得到几条,帖子的类型
		3、返回文章数据
	*/
	public static function get_note($num,$limit,$type){
		if($type>0)
			$serch = ['label',$type];
		else
			$serch = ['label','>',$type];
		$data = DB::table('note')->where([['is_delete',0],$serch])->leftJoin('user', 'user.id', '=', 'note.create_user_id')
			->select('note.id','note.name as title','user.name as author','avatar','content','read_num','comment_num',
				'label','note.update_time')
			->orderBy('note.create_time', 'desc')->offset($num)->limit($limit)->get();
		$time = time();
		foreach ($data as $key => $value) {
			$value->update_time = time_diff($time,$value->update_time);
			$value->label = self::$label[$value->label];
		}
		return $data;
	}
	/*
		1、文章详情页
		2、文章的id
		3、返回文章数据
	*/
	public static function get_detail($id){
		$data = DB::table('note')->where([['is_delete',0],['note.id',$id]])->leftJoin('user', 'user.id', '=', 'note.create_user_id')
			->select('note.id', 'note.name as title','content','describe','note.create_time as time','user.name as name')->get();
		if($data->count()==1)
			$data[0]->time = date("Y-m-d",$data[0]->time);
		else
			$data = [];
		return $data;
	}
	//得到用户发表帖子的记录
	public static function note_record($num,$limit,$id){
		$data = DB::table('note')->where([['is_delete',0],['create_user_id',$id]])->leftJoin('user', 'user.id', '=', 'note.create_user_id')
			->select('note.id','note.name as title','user.name as author','content','read_num','comment_num',
				'label','note.update_time','note.create_time')
			->orderBy('note.create_time', 'desc')->offset($num)->limit($limit)->get();
		$time = time();
		foreach ($data as $key => $value) {
			$value->update_time = time_diff($time,$value->update_time);
			$value->create_time = date("Y-m-d",$value->create_time);
			$value->label = self::$label[$value->label];
		}
		return $data;
	}
	//删除帖子
	public static function delete_note($id){
		$res =   DB::table('note')->where('id',$id)->update(['is_delete' => 1]);
		if($res){
			self::delete_msg($id);
			return true;
		}
		return false;
	}
	//删除帖子评论
	public static function delete_msg($id){
		DB::table('comment')->where('note_id',$id)->update(['is_delete' => 1]);
	}
	//增加阅读量和访问量
	public static function add_read($id){
		$res = DB::table('note')->where([['is_delete',0],['note.id',$id]])->increment('read_num');
	}
	public static function add_comment($id){
		$res = DB::table('note')->where([['is_delete',0],['note.id',$id]])->increment('comment_num');
	}
	//减少阅读量
	public static function del_comment($id,$num){
		$res = DB::table('note')->where('note.id',$id)->decrement('comment_num',$num);
		return $res;
	}
}
