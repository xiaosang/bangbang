<?php

namespace App\Http\Middleware;

use App\Models\Wx\Follow;
use Closure;
use Log;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request)
    {
        $res = Follow::is_set(get_wx_user_openid());
        if($res->is_student == 1){
            // return response("success(已认证)",200 ,['id'=>$res->id])->header("X-CSRF-TOKEN", csrf_token());
            return redirect('/login');
        }else{
            return $next($request);
        }
    }
}