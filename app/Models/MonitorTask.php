<?php

namespace App\Models;
use Log;
use App\Jobs\EndTask;
use DB;

/**
 * 任务监听
 * @package App\Models
 */
class MonitorTask
{
    protected $id;//任务id
    protected $time;//任务时间

    public function __construct($id,$time)
    {
        $this->id = $id;
        $this->time = $time;
    }

    public function end($sync = false) {
        if (!$sync) {
            //入队
            Log::info('[queue task]: &id = ' . $this->id . ' &time: ' . $this->time);
            dispatch((new EndTask($this))->onQueue('task')->delay($this->time));
            return;
        }
        $res = DB::table('task')->where(['id'=>$this->id,'status'=>0,'is_delete'=>0])->update(['status'=>5]);
        if($res){
            Log::info('[task_id: ' . $this->id . ']' . ' no people accept.');
        } else {
            if(DB::table('task')->where(['id'=>$this->id,'status'=>0])->first())
                Log::info('[task_id: ' . $this->id . ']' . ' is delete.');
            else
                Log::info('[task_id: ' . $this->id . ']' . ' is already changed to other status.');
        }
    }

}