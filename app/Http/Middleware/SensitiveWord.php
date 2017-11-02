<?php

namespace App\Http\Middleware;

use Closure;

class SensitiveWord
{
    /**
     * 敏感词中间键
     * 发送的数据必须是'content'
     *  根据敏感词选择不同的路由
     */
    public function handle($request, Closure $next)
    {
        $data = config('sensitive');
        $str = $request->content;
        if(isset($request->title))
            $str = $str+$request->title;
        // 提取中文部分和英文部分，防止其中夹杂英语等
        preg_match_all("/[\x{4e00}-\x{9fa5}]+/u", $str, $match);
        $chinsesArray = $match[0];
        $chineseStr = implode('', $match[0]);
        $englishStr = strtolower(preg_replace("/[^A-Za-z0-9\.\-]/", " ", $str));
        // 全匹配过滤,去除特殊字符后过滤中文及提取中文部分
        foreach ($data['china'] as $word){
            if (strpos($chineseStr, $word)!==false){
                return redirect()->route('sensitive',['word'=>$word]);
            }
        }
        foreach ($data['english'] as $word){
            if (strpos($englishStr, $word)!==false){
                return redirect()->route('sensitive',['word'=>$word]);
            }
        }
        return $next($request);
    }
}
