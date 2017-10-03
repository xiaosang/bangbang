<?php

namespace App\Models\Wx;
use DB;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
	/*
		1、新建帖子的功能
		2、插入的数据
		3、帖子ID
	*/
	public static function set_note($obj){
		$id = DB::table('note')->insert(
			['name' => $obj['title'], 'content' => $obj['content'],'describe'=>$obj['describe'],
			'create_time'=>time(),'create_user_id'=>$obj['user_id']]);
		return $id;
	}
	/*
		1、首页得到数据
		2、跳过的数据条数，得到几条
		3、返回文章数据
	*/
	public static function get_note($num,$limit){
		$data = DB::table('note')->where('is_delete',0)->leftJoin('user', 'user.id', '=', 'note.create_user_id')
			->select('note.id', 'note.name as title','describe','note.create_time as time','user.name as name')->offset($num)->limit($limit)->get();
		foreach ($data as $key => $value) {
			$value->time = date("Y-m-d",$value->time);
		}
		return $data;
	}
}