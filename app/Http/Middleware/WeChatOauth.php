<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use DB;
use Log;

class WechatOauth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        //TODO 指定用户测试
        $session = $request->session();
        $dev_user_id = env('DEV_USER_ID', 0);
        if ($dev_user_id) {
            $oauthUser = $session->get("wechat.oauth_user");
            if (empty($oauthUser) || !isset($oauthUser['user_id']) || intval($oauthUser['user_id']) != $dev_user_id) {
                $user = DB::table('user')->where('id', $dev_user_id)->first();
                if (!empty($user)) {
                    $session->put('wechat.oauth_user', ['openid' => $user->openid]);
                    $session->put('user', $user);
                }
            }
        }

        $wechat = app('wechat');//
        if (empty($session->get('wechat.oauth_user'))) {

            if ($request->has('state') && $request->has('code')) {
                $user = $wechat->oauth->user();

                $openid = $user->getId();
                $session->put('wechat.oauth_user', ['openid' => $openid]);
                //获取用户信息
                $userService = $wechat->user;
                $userInfo = $userService->get($openid);
                Log::debug(['WXOAUTH_2' => ['session_id' => $session->getId(), 'wechat.oauth_user' => $session->get('wechat.oauth_user')]]);
                $this->toDo($userInfo);

                return redirect()->to($this->getTargetUrl($request));
            }

            return $wechat->oauth->redirect($request->fullUrl());
        }

        return $next($request);
    }

    /**
     * Build the target business url.
     *
     * @param Request $request
     *
     * @return string
     */
    public function getTargetUrl($request)
    {
        $queries = array_except($request->query(), ['code', 'state']);
        return $request->url() . (empty($queries) ? '' : '?' . http_build_query($queries));
    }

    private function toDo($userInfo)
    {
        $query = DB::table('wx_user')
            ->where('openid', $userInfo["openid"])
            ->first();
        $data = ['nickname' => isset($userInfo['nickname']) ? wx_nickname_filter($userInfo["nickname"]) : '',
            'avatar' => isset($userInfo["headimgurl"]) ? substr($userInfo["headimgurl"],0,-1).'96' : '',
            'sex' => isset($userInfo['sex']) ? intval($userInfo["sex"]) : 0,
            'province' => isset($userInfo["province"]) ? $userInfo["province"] : '',
            'city' => isset($userInfo["city"]) ? $userInfo["city"] : '',
            'country' => isset($userInfo["country"]) ? $userInfo["country"] : '',
            'is_subscribe' => isset($userInfo["subscribe"]) ? intval($userInfo["subscribe"]) : 0,
            'subscribe_time' => isset($userInfo["subscribe_time"]) ? strval(intval($userInfo["subscribe_time"])) : 0,
            'unionid' => isset($userInfo["unionid"]) ? $userInfo["unionid"] : ''];
        if ($query) {
            DB::table('wx_user')->where('openid', $userInfo["openid"])->update($data);
        } else {
            $data['openid'] = $userInfo["openid"];
            $data['create_time'] = time();
            DB::table('wx_user')->insert($data);
        }
    }
}