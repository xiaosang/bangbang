<?php
namespace App\Models;

//use Illuminate\Notifications\Notifiable;
//use App\Jobs\SendTemplate;
use DB;
use Log;

class Template
{
//    use Notifiable;

    public static function test1()
    {
//        $result =  self::upload_img("G:\phpStudy\WWW\bangbang\public\img\\2.jpg");
//        var_dump($result); //_SzJhCIV9NSVzy_Td5pDXC2JNuR-Pj3DrHXiwg3bp-k
        $result = self::get_mediaId();
        return self::send_img_all($result->item[0]['media_id']);

    }

    public static function get_mediaId(){
        $app = app("wechat");
        $material = $app->material;
        $list = $material->lists('image');
        return $list;
    }

    public static function send_img_all($mediaId){
        $app = app('wechat');
        $broadcast = $app->broadcast;
        $result = $broadcast->sendText($mediaId);
        return $result;
    }

    public static function upload_img($path){
        //    G:\phpStudy\WWW\bangbang\public\img.jpg
        $app = app("wechat");
        $material = $app->material;
        $result = $material->uploadImage($path);
        return $result;
    }

    public static function send_model_msg($title,$data){
        $app = app("wechat");
        $notice = $app->notice;
        $result = $notice->send($data);
    }


    /**
     * 撕名牌提醒
     */
    public static function test()
    {
        //todo $template_key = "OPENTM410711248";
        /*if (config('app.env') == 'production') {
            $template_id = "rYwJzvDtJ-PSEmhYzj-Mr_or7NJAIQRiNwuDWfKDk4M"; //正式
        } else {
            $template_id = "uGaa8aBr_JR1R-kabAbltr8ot1GakOzjaQmZ8HkXOr4";   //测试号
        }*/
        $template_id = "MZoWjamCMl0x057E8tk2yKpzynjtjFGUh0hCrPonExE";

        /*$stu = DB::table('student')
            ->where('status', '<>', 2)
            ->where('openid', "o3Io60tsnEoZo930WjPH-EM5RKo4")
            ->first();*/

        //$url = config("app.url") . "/message?code=OPENTM410711248";
        $url = "https://mp.weixin.qq.com/s/CbrOFS6iGn0f5L5FZEg00A";
        $data = [
            "touser" => "ordUR0llJqpXNTh4yP-C0ofKX8gw",
            "template_id" => $template_id,
            "url" => $url,
            "data" => [
                "first" => ["value" => "撕名牌提醒", "color" => ""],
                "name" => ["value" => "周旭", "color" => ""],
                "zu" => ["value" => "开发一组", "color" => ""],
                "time" => ["value" => "今天", "color" => ""],
                "remain" => ["value" => "7", "color" => ""],
                "remark" => ["value" => "加油", "color" => ""]
            ]
        ];
//        {{first.DATA}} 被撕的人：{{name.DATA}} 被撕人的组别：{{zu.DATA}} 被撕时间：{{time.DATA}} 本组剩余的人：{{remain.DATA}} {{remark.DATA}}
//        Template::insert_message($openid, 0, "报名成功提醒", "您好！" . $name . "学员，您已报名成功，接下来需要准备驾照申请资料，请点击查看驾照申请所需材料", $url);
        return Template::send_model_msg("报名成功提醒", $data);
    }
}