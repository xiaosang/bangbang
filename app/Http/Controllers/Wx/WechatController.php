<?php
namespace App\Http\Controllers\Wx;

use App\Http\Controllers\Controller;
use App\Models\Wx\WxAutoreply;
use Log;
use DB;

class WechatController extends Controller
{

    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        Log::info('request arrived.');
        $wechat = app('wechat');
        $wechat->server->setMessageHandler(function ($message) use ($wechat) {
            $wxServer = new WxServer($message, $wechat);
            //Message detail: {"ToUserName":"gh_d96276df473d","FromUserName":"oMMATt0KjFrtIcjpfVuRPsVWyqGk","CreateTime":"1458799194","MsgType":"event","Event":"subscribe","EventKey":[]}
            if ($message->MsgType == 'event') {
                if ($message->Event == 'subscribe') {
                    //TODO 关注
                    return $wxServer->subscribe();
                } else if ($message->Event == 'unsubscribe') {
                    //TODO 取消关注
                    return $wxServer->unsubscribe();
                }

                // {"ToUserName":"gh_9f675e9c1f81","FromUserName":"ofeo0szCbJLREJi4KrMP1BeQbtMo","CreateTime":"1459931042","MsgType":"event","Event":"CLICK","EventKey":"event_msg_1"}
                if ($message->Event == 'CLICK') {
                    return $wxServer->click();
                }

            } else if ($message->MsgType == 'text') {
                return $wxServer->keyword();
            }

            return $wxServer->help();

        });

        Log::info('return response.');
        return $wechat->server->serve();
    }

    /**
     * 查看微信公众号当前的按钮
     */
    public function menu_current()
    {
        $app = app('wechat');
        $menu = $app->menu;
        $menus = $menu->all();
        var_dump($menus);
    }

    /**
     * 添加菜单
     */
    public function menu_add()
    {
        $app = app('wechat');
        $menu = $app->menu;
        $menu->destroy();
        $buttons = [
            [
                "type" => "view",
                "name" => "首页",
                "url" => env('APP_URL') . '/wx#/main'
            ],

//            [
//            "name"=>"进入课堂",
//                "sub_button"=>[
//                   [
//                        "type"=>"view",
//                        "name"=>"我是学生",
//        //                "url"=>"http://hnnu.gammainfo.com/wx_student"
//                        "url"=>"http://teach_learn.zhuchenglin.cn/wx_student"
//                    ],
//                    [
//                        "type"=>"view",
//                        "name"=>"我是教师",
//                        "url"=>"http://teach_learn.zhuchenglin.cn/wx_teacher"
//                    ],
//                ],
//            ],
        ];
        $menu->add($buttons);
        echo "OK";
    }

    /**
     * 删除菜单
     */
//    public  function  menu_destroy(){
//        $app = app('wechat');
//        $menu = $app->menu;
//        $menu->destroy();
//    }


    /**
     * @param $notice   微信的notice服务
     * @param $template_id     模板id
     * @param $url  模板链接地址
     * @param $data    模板消息的数据
     * @param $open_id  要发送的用户的openid
     * 推送微信模板公告消息
     */
//    public static function push_message($notice, $template_id, $url, $data, $open_id)
//    {
//        $notice->uses($template_id)->withUrl($url)->andData($data)->andReceiver($open_id)->send();
//    }
}

class WxServer
{
    private $message = null;
    private $wechat = null;

    public function __construct($message, $wechat)
    {
        $this->message = $message;
        $this->wechat = $wechat;
    }

    //关注
    public function subscribe()
    {
        $openid = $this->message->FromUserName;
        //获取用户信息
        $userService = $this->wechat->user;
        $userInfo = $userService->get($openid);
        $query = DB::table('wx_user')
            ->where('openid', $openid)
            ->first();

        if ($query) {
            DB::table('wx_user')->where('openid', $openid)->update([
                'nickname' => wx_nickname_filter(strval($userInfo->nickname)),
                'avatar' => strval($userInfo->headimgurl),
                'sex' => intval($userInfo->sex),
                'province' => strval($userInfo->province),
                'city' => strval($userInfo->city),
                'country' => strval($userInfo->country),
                'is_subscribe' => intval($userInfo->subscribe),
                'subscribe_time' => strval(intval($userInfo->subscribe_time)),
                'create_time' => time(),
                'unionid' => strval($userInfo->unionid)
            ]);
        } else {
            DB::table('wx_user')->insert([
                'openid' => $openid,
                'nickname' => wx_nickname_filter(strval($userInfo->nickname)),
                'avatar' => strval($userInfo->headimgurl),
                'sex' => intval($userInfo->sex),
                'province' => strval($userInfo->province),
                'city' => strval($userInfo->city),
                'country' => strval($userInfo->country),
                'is_subscribe' => intval($userInfo->subscribe),
                'subscribe_time' => strval(intval($userInfo->subscribe_time)),
                'create_time' => time(),
                'unionid' => strval($userInfo->unionid)
            ]);
        }
        //TODO 返回关注公众号时配置的信息
        //类型，0：被添加自动回复(关注时回复)，1：消息自动回复（关键字匹配不到回复），2：关键词自动回复
        $autoReply = DB::table('wx_autoreply')
            ->where('category', 0)
            ->where('status', 0)
            ->first();
        if ($autoReply) {
            $subContent = $autoReply->content;
        } else {
            $subContent = '欢迎关注~~~';
        }
        return $subContent;
    }

    //取消关注
    public function unsubscribe()
    {
        $openid = $this->message->FromUserName;

        $query = DB::table('wx_user')
            ->where('openid', $openid)
            ->update([
                'is_subscribe' => 0,
                'subscribe_time' => 0
            ]);

        return $query !== false ? true : false;
    }

    /**
     * 单击事件
     * @return string
     */
    public function click()
    {
        //TODO 暂时不处理点击事件
        return $this->help();
//        return 'click';
    }

    //关键字匹配
    public function keyword()
    {
        //TODO
        $content = $this->message->Content;
        $keywordRlt = DB::table('wx_keyword')
            ->where('name', $content)
            ->first();
        if ($keywordRlt) {
            //查找到
            $autoReply = DB::table('wx_autoreply')
                ->where('id', $keywordRlt->reply_id)
                ->first();
            if ($autoReply) {
                return $autoReply->content;
            }
        }
        //没有查找到,查默认配置
        $autoReply = DB::table('wx_autoreply')
            ->where('category', 1)
            ->where('status', 0)
            ->first();
        if ($autoReply) {
            return $autoReply->content;
        }

        return '无法识别此内容~~';
    }

    public function help()
    {
        //TODO
        return 'help';
    }
}
