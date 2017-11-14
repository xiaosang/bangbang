<?php

namespace App\Http\Controllers\Wx;

use App\Models\Wx\Follow;
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
        foreach ($res as $key => $value) {
            $data[$key]['name'] = $value->name;
            $data[$key]['value'] = $value->id;
        }
       
        return responseToJson(0,'success',$data);
    }


    public function check_school($school_id){
        if($school_id == 1){
            return config('hist');
        }else if($school_id == 3){
            return config('xinke');
        }
    }

    public function get_check(Request $request)
    {
        // dd(app('wechat'));
        $school = $this->check_school($request->school_id);
        // if($request->school_id==1){
        $client = new Client([
            'base_uri' => $school['base_uri'],
            'timeout' => 2
        ]);
        
        $jar = new CookieJar();
        
        // $file = storage_path('app/public/temp_html/Cookie.html');
        // $fp = fopen($file,"w");
        // fwrite($fp,json_encode(($jar)));
        // fwrite($fp, '结束');
        // fclose($fp);


        $r = $client->request('get', $school['ValidateCode'], [
            'headers' => $school['headers'],
            'cookies' => $jar,
        ]);
        session(['user_cookie_student' => $jar]);
        return $r->getBody();
        // }
    }
    
    public function login(Request $request){
        // $school = config('school');
        // dd($school,$request->school_id==1);
        $school = $this->check_school($request->school_id);
        $postData = $school['postData'];
        if($request->school_id == 1){
            $postData['dsdsdsdsdxcxdfgfg'] = $request->password;
            $postData['fgfggfdgtyuuyyuuckjg'] = $request->validate;
            $postData['txt_asmcdefsddsd'] = $request->user_id;
        }else if($request->school_id == 3){
            $postData['PassWord'] = $request->password;
            $postData['cCode'] = $request->validate;
            $postData['UserID'] = $request->user_id;
        }
        $client = new Client([
            'base_uri' => $school['base_uri'],
            'timeout' => 2
        ]);
        $jar = session('user_cookie_student');
        // dd($jar);
        $r = $client->request('post', $school['login'], [
            'headers' => $school['headers'],
            'cookies' => $jar,
            'form_params' => $postData,
        ]);
        $crawler = new Crawler();

        // $file = storage_path('app/public/temp_html/test.html');
        // $fp = fopen($file,"w");
        // fwrite($fp,$r->getBody()->getContents());
        // fwrite($fp, '结束');
        // fclose($fp);

        $crawler->addHtmlContent($r->getBody()->getContents(),'gb2312');
        if($request->school_id == 1){
            $text = $crawler->filter('#divLogNote>font')->text();
        }else if($request->school_id == 3){
            $text = $crawler->filter('#divLogNote')->text();
        }
        // dd($text);
        if($text == '正在加载权限数据...'){
            // dd(;
            Follow::save_school(get_session_user_id(),$request->school_id);
            // DB::table('user')
            // ->where('id',intval(get_session_user_id()))
            // ->update([
            //     'school_id'=> intval($request->school_id)
            // ]);
            return responseToJson(0, 'success', '登录成功，正在加载信息');
        }else{
            return responseToJson(1, 'error', $text);
        }
        
    }

    public function get_info(Request $request){
        $res = Follow::is_set(get_wx_user_openid());
        // dd($request->is_student);
        if($res->is_student == 0){
            $school = $this->check_school($res->school_id);
            $client = new Client([
                'base_uri' => $school['base_uri'],
                'timeout' => 2
            ]);
            $jar = session('user_cookie_student');
            
            $info = $client->request('get', $school['studentInfo'], [
                    'headers' =>$school['headers_info'],
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
        }else{
            return responseToJson(0, 'success', $res);
        }
        
        
    }

}