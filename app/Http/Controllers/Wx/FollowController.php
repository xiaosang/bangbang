<?php

namespace App\Http\Controllers\Wx;

use App\Models\wx\Follow;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;


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


    public function get_check(Request $request)
    {
        // dd(app('wechat'));
        $school = config('school');
        if($request->school_id==1){
            $client = new Client([
                'base_uri' => $school['hist']['base_uri'],
                'timeout' => 2
            ]);
            
            $jar = new CookieJar();

            $r = $client->request('get', $school['hist']['ValidateCode'], [
                'headers' => $school['hist']['headers'],
                'cookies' => $jar,
            ]);
            session(['user_cookie_student' => $jar]);
            return $r->getBody();
        }
    }
    
    public function login(Request $request){
        $school = config('school');
        // dd($school,$request->school_id==1);
        if($request->school_id==1){
            $postData = $school['hist']['postData'];
    
            $postData['dsdsdsdsdxcxdfgfg'] = $request->password;
            $postData['fgfggfdgtyuuyyuuckjg'] = $request->validate;
            $postData['txt_asmcdefsddsd'] = $request->user_id;
    
    
            $client = new Client([
                'base_uri' => $school['hist']['base_uri'],
                'timeout' => 2
            ]);
            $jar = session('user_cookie_student');
            
            $r = $client->request('post', $school['hist']['login'], [
                'headers' => $school['hist']['headers'],
                'cookies' => $jar,
                'form_params' => $postData,
            ]);
            $crawler = new Crawler();
            $crawler->addHtmlContent($r->getBody()->getContents(),'gb2312');
    
            $text = $crawler->filter('#divLogNote>font')->text();
            if($text == '正在加载权限数据...'){
                return responseToJson(0, 'success', '登录成功，正在加载信息');
            }else{
                return responseToJson(1, 'error', $text);
            }
        }
        
    }

    public function get_info(Request $request){
        $school = config('school');
        $res = Follow::is_set(get_wx_user_openid());

        if($res->is_v == 0){
            if($request->school_id==1){
                $client = new Client([
                    'base_uri' => $school['hist']['base_uri'],
                    'timeout' => 2
                ]);
                $jar = session('user_cookie_student');
                
                $info = $client->request('get', $school['hist']['studentInfo'], [
                        'headers' => [
                            'Host' => 'jwgl.hist.edu.cn',
                            'Referer' => $school['hist']['studentInfoUrl'],
                            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.86 Safari/537.36',
                        ],
                        'cookies' => $jar,
                    // 'form_params' => $postData,
                ]);
                // dd($info->getBody()->getContents());
                $crawler = new Crawler();
                $crawler->addHtmlContent($info->getBody()->getContents(), 'gb2312');
                
        
                $datas = $crawler->filter('.T')->eq(0)->nextAll()->each(function(Crawler $node,$i){
                    $tdNodes = $node->filter('td');
                    // $data = [];
                    if($i==0){
                        $data['student_num'] = $tdNodes->eq(1)->text();
                        $data['student_name'] = $tdNodes->eq(3)->text();
                        // var_dump($data['student_num']);
                        return $data;
                    }
                    if($i==2){
                        $data['student_sex'] = $tdNodes->eq(1)->text();
                        $data['student_code'] = $tdNodes->eq(3)->text();
                        return $data;
                    }
                    if($i==22){
                        $data['student_department'] = $tdNodes->eq(1)->text();
                        $data['student_class'] = $tdNodes->eq(3)->text();
                        return $data;
                    }
                    
                    
                });
                $data = [];
        
                $data['student_num'] = $datas[0]['student_num'];
                $data['name'] = $datas[0]['student_name'];
                $data['sex'] = $datas[2]['student_sex'];
                $data['student_code'] = $datas[2]['student_code'];
                $data['student_department'] = $datas[22]['student_department'];
                $data['student_class'] = $datas[22]['student_class'];
                $data['openid'] = get_wx_user_openid();
                return responseToJson(0, 'success', Follow::save_info($data));
            }
        }else{
            return responseToJson(0, 'success', $res);
        }
        
        
    }

}