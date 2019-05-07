<?php
namespace App\Models;


use DB;
use Log;

class Template
{
    public static function send_model_msg($data){
        $app = app("wechat");
        $notice = $app->notice;
        $result = $notice->send($data);
    }

    /**
     * 撕名牌提醒
     */
    public static function test($openid,$first,$name,$zu,$time,$remain,$remark)
        //ordUR0llJqpXNTh4yP-C0ofKX8gw    撕名牌提醒  周旭   开发一组   今天   7   加油加油
    {
        //todo $template_key = "OPENTM410711248";
        /*if (config('app.env') == 'production') {
            $template_id = "rYwJzvDtJ-PSEmhYzj-Mr_or7NJAIQRiNwuDWfKDk4M"; //正式
        } else {
            $template_id = "uGaa8aBr_JR1R-kabAbltr8ot1GakOzjaQmZ8HkXOr4";   //测试号
        }*/
        $template_id = "MZoWjamCMl0x057E8tk2yKpzynjtjFGUh0hCrPonExE";

        $url = "";
        $data = [
            "touser" => $openid,//openid    ordUR0llJqpXNTh4yP-C0ofKX8gw
            "template_id" => $template_id,//模板消息openid
            "url" => $url,
            "data" => [
                "first" => ["value" => $first, "color" => ""],
                "name" => ["value" => $name, "color" => ""],
                "zu" => ["value" => $zu, "color" => ""],
                "time" => ["value" => $time, "color" => ""],
                "remain" => ["value" => $remain, "color" => ""],
                "remark" => ["value" => $remark, "color" => ""]
            ]
        ];
        return Template::send_model_msg( $data);
    }

    /*
     * 任务截至提醒
     * */
    public static function task_stop($openid,$first,$title,$type,$create_time,$stop_time,$remark){
        $template_id = "lzmkcDbJ1oiOH6x_IgLv8OyeoPkQvN2e2N7NPAgC7ew";

        $url = "";
        $data = [
            "touser" => $openid,//openid    ordUR0llJqpXNTh4yP-C0ofKX8gw
            "template_id" => $template_id,//模板消息openid
            "url" => $url,
            "data" => [
                "first" => ["value" => $first, "color" => ""],
                "title" => ["value" => $title, "color" => ""],
                "type" => ["value" => $type, "color" => ""],
                "create_time" => ["value" => $create_time, "color" => ""],
                "stop_time" => ["value" => $stop_time, "color" => ""],
                "remark" => ["value" => $remark, "color" => ""]
            ]
        ];
        return Template::send_model_msg( $data);
    }

    /*
     * 任务被接提醒
     * */
    public static function task_accept($openid,$first,$title,$type,$create_time,$nick_name,$phone,$remark){
        $template_id = "e-Ve7b2ILOin2473c57IznqwjHQ0wZ2dVTGbiMzUVwI";

        $url = "";
        $data = [
            "touser" => $openid,//openid    ordUR0llJqpXNTh4yP-C0ofKX8gw
            "template_id" => $template_id,//模板消息openid
            "url" => $url,
            "data" => [
                "first" => ["value" => $first, "color" => ""],
                "title" => ["value" => $title, "color" => ""],
                "type" => ["value" => $type, "color" => ""],
                "create_time" => ["value" => $create_time, "color" => ""],
                "nick_name" => ["value" => $nick_name, "color" => ""],
                "phone" => ["value" => $phone, "color" => ""],
                "remark" => ["value" => $remark, "color" => ""]
            ]
        ];
        return Template::send_model_msg( $data);
    }

    public static function lost_accept($openid,$content,$lost_time,$place,$user_phone,$type){
        $template_id = "wXyUTJ0GfzQ2you3xUqnD_ZfCsuoGPhx5eeYwJ9L28o";

        if ($type == 2) {
            $template_id = "84JzwqYehJ3vsc44mG8nQmOM16igQ474XksR8qtG8fE";
        }
        $url = "";
        $data = [
            "touser" => $openid,//openid    ordUR0llJqpXNTh4yP-C0ofKX8gw
            "template_id" => $template_id,//模板消息openid
            "url" => $url,
            "data" => [
                "first" => ["value" => $content, "color" => ""],
                "address" => ["value" => $place, "color" => ""],
                "date" => ["value" => $lost_time, "color" => ""],
                "phone" => ["value" => $user_phone, "color" => ""],
            ]
        ];
        return Template::send_model_msg( $data);
    }


}