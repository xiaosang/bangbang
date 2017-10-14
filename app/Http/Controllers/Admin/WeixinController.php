<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Admin\Weixin;

class WeixinController extends Controller
{
    /**
     * 获取微信配置
     * @return json
     */
    public function get_config()
    {
        $config = Weixin::getConfig();
        $obj = new \stdClass();
        if ($config) {
            $obj->app_id = $config->appid;
            $obj->app_secret = $config->appsecret;
            $obj->follow_url = $config->subscribe_url;
            $obj->app_url = $config->dev_url == "" ? "http://" . $_SERVER['SERVER_NAME'] . "/wechat" : $config->dev_url;
            $obj->app_token = $config->dev_token == "" ? $this->randStr() : $config->dev_token;
            $obj->app_ask = $config->dev_aeskey;
            $obj->app_type = strval($config->dev_encrypt_type);
            $obj->open_id = $config->open_appid;
            $obj->open_sk = $config->open_appsecret;
        } else {
            $obj->app_id = "";
            $obj->app_secret = "";
            $obj->follow_url = "";
            $obj->app_url = "";
            $obj->app_token = "";
            $obj->app_ask = "";
            $obj->app_type = "0";
            $obj->open_id = "";
            $obj->open_sk = "";
        }
        return Response::json($obj);
    }

    function randStr($len = 6)
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        mt_srand((double)microtime() * 1000000 * getmypid());
        $password = "";
        while (strlen($password) < $len)
            $password .= substr($chars, (mt_rand() % strlen($chars)), 1);
        return $password;
    }

    /**
     * 设置微信配置
     * @param Request $request
     */
    public function set_config(Request $request)
    {
        $config = array(
            "appid" => Input::get("app_id"),
            "appsecret" => Input::get("app_secret"),
            "subscribe_url" => Input::get("follow_url"),
            "dev_url" => Input::get("app_url"),
            "dev_token" => Input::get("app_token"),
            "dev_aeskey" => Input::get("app_ask"),
            "dev_encrypt_type" => Input::get("app_type"),
            "open_appid" => Input::get("open_id"),
            "open_appsecret" => Input::get("open_sk"),
        );

        $rows = Weixin::setConfig($config);

        if ($rows === false) {
            return responseToJson(1, "微信配置保存失败！");
        } else {
            return responseToJson(0, "微信配置保存成功~~~");
        }
    }

    /**
     * 获取所有通知模板
     * @return json
     */
    public function get_wx_template()
    {
        $rows = Weixin::get_wx_template();
        for ($i = 0; $i < count($rows); $i++) {
            $rows[$i]->btnUpdate = false;
            $rows[$i]->btnSave = false;
        }
        return Response::json($rows);
    }

    /**
     * 设置行业
     */
    public function set_industry()
    {
        $rlt = Weixin::set_industry();
        if ($rlt) {
            return responseToJson(0, "微信账号所属行业设置成功！");
        } else {
            return responseToJson(1, "更改行业太频繁！");
        }
    }

    /**
     * 自动更新所有微信通知模板
     * @return [type] [description]
     */
    public function get_private_templates()
    {
        $rlt = Weixin::get_private_templates();
        if (count($rlt) > 0) {
            return responseToJson(0, "微信模板自动更新成功！", $rlt);
        } else {
            return responseToJson(1, "微信模板自动更新失败~~~");
        }
    }

    /**
     * 手动更新单条微信通知模板
     */
    public function add_template()
    {
        $rlt = Weixin::add_template(Input::get("tpl_id"), Input::get("tpl_key"));
        if ($rlt != "false") {
            return responseToJson(0, "微信模板手动更新成功~~~", $rlt);
        } else {
            return responseToJson(1, "微信模板手动更新失败~~~");
        }
    }

    /**
     * 手动保存微信通知模板
     */
    public function set_wx_template()
    {
        $template = array(
            "template_key" => Input::get("tpl_key"),
            "template_id" => Input::get("tpl_id"),
        );
        $rows = Weixin::set_wx_template($template);
        if ($rows === false) {
            return responseToJson(1, "微信模板消息配置保存失败！");
        } else {
            return responseToJson(0, "微信模板消息配置保存成功~~~");
        }
    }

    /**
     * 获取关注回复、消息、关键字回复内容
     * @return [type] [description]
     */
    public function get_reply()
    {
        $data = Weixin::get_reply();
        $keyword = [];
        $reply = new \stdClass();
        $reply->follow = array("id" => 0, "content" => "");
        $reply->news = array("id" => 0, "content" => "");
        if (!empty($data)) {
            foreach ($data as $key => $item) {
                if ($item->category == 0 && $item->type == 1) {
                    $reply->follow = array("id" => $item->id, "content" => $item->content);
                }
                if ($item->category == 1 && $item->type == 1) {
                    $reply->news = array("id" => $item->id, "content" => $item->content);
                }
                if ($item->category == 2) {
                    array_push($keyword, array("id" => $item->id, "header" => $item->title, "title" => $item->title, "content" => $item->content, "words" => Weixin::get_words($item->id), "btn" => false, "del" => false, "validate" => false, "result" => new \stdClass()));
                }
            }
        }
        $reply->keyword = $keyword;
        return Response::json($reply);
    }

    /**
     * 设置关注回复内容
     */
    public function set_follow()
    {
        $id = Input::get("id");
        $rows = Weixin::set_follow(array("id" => $id, "content" => Input::get("content")));
        if ($rows === false) {
            return responseToJson(1, "关注自动回复设置失败！");
        } else {
            return responseToJson(0, "关注自动回复设置成功！");
        }
    }

    /**
     * 设置消息回复内容
     */
    public function set_news()
    {
        $rows = Weixin::set_news(array("id" => Input::get("id"), "content" => Input::get("content")));
        if ($rows === false) {
            return responseToJson(1, "消息自动回复设置失败！");
        } else {
            return responseToJson(0, "消息自动回复设置成功！");
        }
    }

    /**
     * 更新关键字回复规则
     */
    public function set_reply()
    {
        $data["id"] = Input::get("id");
        $data["title"] = Input::get("title");
        $data["words"] = Input::get("words");
        $data["content"] = Input::get("content");
        $rows = Weixin::set_reply($data);
        if ($rows == 0) {
            return responseToJson(0, "关键字自动回复设置成功！");
        } else if ($rows == 1) {
            return responseToJson(1, "关键字自动回复设置失败！");
        } else if ($rows == 2) {
            return responseToJson(2, "规则名有重复！");
        } else if ($rows == 3) {
            return responseToJson(3, "关键词有重复！");
        }
    }

    /**
     * 新建关键字回复规则
     */
    public function create_reply()
    {
        $data["title"] = Input::get("title");
        $data["words"] = Input::get("words");
        $data["content"] = Input::get("content");
        $rows = Weixin::create_reply($data);
        if ($rows > 0) {
            $item = Weixin::get_reply_byid($rows);
            $arr = array("id" => $item->id, "header" => $item->title, "title" => $item->title, "content" => $item->content, "words" => Weixin::get_words($item->id), "btn" => false, "del" => false, "validate" => false, "result" => new \stdClass());
            return responseToJson(0, "关键字自动回复设置成功！", $arr);
        } else if ($rows == -1) {
            return responseToJson(1, "关键字自动回复设置失败！");
        } else if ($rows == -2) {
            return responseToJson(2, "规则名有重复！");
        } else if ($rows == -3) {
            return responseToJson(3, "关键词有重复！");
        }
    }

    /**
     * 删除关键字回复规则
     * @param  Number $id 规则ID
     * @return json
     */
    public function delete_reply($id)
    {
        $rows = Weixin::delete_reply($id);
        if ($rows) {
            return responseToJson(0, "规则删除成功！");
        } else {
            return responseToJson(1, "规则删除失败！");
        }
    }

    /**
     * 获取微信菜单
     * @return  json
     */
    public function get_menu()
    {
        $index = 0;
        $new_menu_list = array();
        $menus = Weixin::get_menu();
        if($menus){
            foreach ($menus as $key => $item) {
                if (isset($item['sub_button']['list'])) {
                    $item['sub_button'] = $item['sub_button']['list'];
                    unset($item['sub_button']['list']);
                    for ($i = 0; $i < count($item['sub_button']); $i++) {
                        $item['sub_button'][$i]["sub_button"] = array();
                        $item['sub_button'][$i]["validate"] = true;
                        $item['sub_button'][$i]["result"] = new \stdClass();
                        $item['sub_button'][$i]["max"] = 8;
                        $item['sub_button'][$i]["id"] = $index;
                        $index++;
                    }
                } else {
                    $item['sub_button'] = array();
                }
                $item["add_sub"] = count($item['sub_button']) >= 5 ? false : true;
                $item["validate"] = true;
                $item["result"] = new \stdClass();
                $item["id"] = $index;
                $item["max"] = 4;
                $item["show"] = false;
                $new_menu_list[] = $item;
                $index++;
            }
        }else{
            $new_menu_list[] = [];
        }
        return Response::json($new_menu_list);
    }

    /**
     * 设置微信菜单
     */
    public function set_menu()
    {
        $data = Input::get("data");
        $buttons = array();
        foreach ($data as $key => $item) {
//            if($key == 0){
//                continue;
//            }
            $arr = array();
//            var_dump($item);
            if (count($item["sub_button"]) > 1) {
                foreach ($item["sub_button"] as $vv) {
                    $url = str_replace("&amp;", "&", $vv["url"]);
                    array_push($arr, array("type" => "view", "name" => $vv["name"], "url" => $url));
                }
            }
            if (count($arr) == 0) {
                $url = str_replace("&amp;", "&", $item["url"]);
                array_push($buttons, array("type" => "view", "name" => $item["name"], "url" => $url));
            } else {
                array_push($buttons, array("type" => "view", "name" => $item["name"], "sub_button" => $arr));
            }

        }
//        var_dump($buttons);
        $rlt = Weixin::set_menu($buttons);
        if ($rlt) {
            return responseToJson(0, "保存并发布成功！");
        } else {
            return responseToJson(1, "保存并发布失败！");
        }
    }

    /**
     * 同步微信用户
     */
    public function sync_wx_user()
    {
        $rows = Weixin::get_wx_user();
        if ($rows) {
            return responseToJson(0, $rows);
        } else {
            return responseToJson(1, "fail");
        }
    }
}
