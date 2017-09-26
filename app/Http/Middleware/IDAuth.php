<?php

namespace App\Http\Middleware;

use \Illuminate\Http\Request;
use Closure;
use DB;
use Log;

/**
 *  用户身份验证
 */
class IDAuth
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
        $session = $request->session();
        $user = $session->get('user');

        $isAuth = false;

        if (empty($user)) {
            $authUser = session('wechat.oauth_user');
            $openid = $authUser ? $authUser['openid'] : false;
            if ($openid) {
                $user = DB::table('user')
                    ->where('openid', $openid)
                    ->where('status', 0)
                    ->first();
                if ($user&&!empty($user->openid)) {
                    $isAuth = true;
                    $session->put('user', $user);
                    //更新登录session
//                    DB::table('user')->where('id', $user->id)->update([
//                        'login_session_wx' => $session->getId()
//                    ]);
                    Log::debug('login_session_wx:' . $user->id . '-' . $openid . '-' . $session->getId());
                }
            }
        } else {
            $isAuth = true;
        }

        if (!$isAuth) {
            session(['go.url' => $request->fullUrl()]);
            Log::info(['AUTH' => [
                'redirect_url' => session('go.url'),
                'auth'=>'auth'
            ]]);
//            return redirect()->to('/wx/auth#!/');
            //如果获取不到则可以跳转到出错页面
        }
        return $next($request);
    }
}
