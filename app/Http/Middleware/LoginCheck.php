<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Log;

class LoginCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        //TODO 指定用户测试
        $dev_user_id = env('DEV_USER_ID', 0);
        if ($dev_user_id && $dev_user_id != get_session_user_id()) {
            $user = User::get_info($dev_user_id);
            if ($user) {
                session(['user' => $user, 'permission' => null]);
            }
        }

        if (get_session_user_id()) {
            return $next($request);
        } else {
            //TODO 跳转到登录,登录成功后返回到原来访问页面
            Log::debug(['NO_PERMISSION' => get_session_user(), 'user_id' => get_session_user_id()]);
            if ($request->ajax()) {
                return response("Unauthorized.（未登录）", 401)->header("X-CSRF-TOKEN", csrf_token());
            } else {
                return redirect('/login');
            }
        }
    }
}