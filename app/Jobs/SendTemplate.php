<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Log;
use DB;

class SendTemplate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data, $token, $title;

    public function __construct($title, $data)
    {
        $this->title = $title;
        $this->data = $data;
    }


    public function handle()
    {
        try {
            $app = app("wechat");
            $notice = $app->notice;
            //Log::info($this->title);
            $result = $notice->send($this->data);
            //Log::info($result);
            if ($result->errcode == 0) {
                return true;
            } else {
                DB::table("template_error")->insert(["create_time" => time(), "title" => $this->title, "openid" => $this->data["touser"], "template_id" => $this->data["template_id"], "content" => json_encode($this->data), "code" => $result->errcode, "message" => $result->errmsg, "status" => 0]);
                return false;
            }
        } catch (\Exception $e) {
            DB::table("template_error")->insert(["create_time" => time(), "title" => $this->title, "openid" => $this->data["touser"], "template_id" => $this->data["template_id"], "content" => json_encode($this->data), "code" => $e->getCode(), "message" => $e->getMessage(), "status" => 0]);
            return false;
        }
    }
}
