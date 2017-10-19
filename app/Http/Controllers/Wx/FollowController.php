<?php

namespace App\Http\Controllers\Wx;

use App\Models\wx\Follow;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB;

class FollowController extends Controller
{

    public function get_school()
    {
        $res = Follow::get_school();
        $data = [];
        // dd($res);
        foreach ($res as $key => $value) {
            
            $data[$key]['name'] = $value->name;
            $data[$key]['value'] = $value->id;
        }
       
        // dd($data);
        return responseToJson(0,'success',$data);
    }


    public function test(Request $request)
    {
        $client = new Client([
            'base_uri' => 'http://jwgl.hist.edu.cn',
            'timeout' => 2
        ]);
        
        $jar = new CookieJar();

        $r = $client->request('get', '/jwweb/sys/ValidateCode.aspx', [
            'headers' => [
                'Host' => 'jwgl.hist.edu.cn',
                'Referer' => 'http://jwgl.hist.edu.cn/jwweb/_data/login.aspx',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.86 Safari/537.36',
            ],
            'cookies' => $jar,
        ]);

        return $r->getBody();
    }
}