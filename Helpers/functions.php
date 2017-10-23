<?php
/**
 * 页面json 输出
 *
 * @param int $code
 * @param $msg
 * @param $paras
 */
function responseToJson($code = 0, $msg = '', $paras = null)
{
    $res["code"] = $code;
    $res["msg"] = $msg;
    // if (!empty($paras)) {
    $res["result"] = $paras;
    // }
    return response()->json($res);
}

function responseToPage($results)
{
    return response()->json($results);
}
/**
 * 上传文件名 生成
 *
 * @param int $code
 * @param $msg
 * @param $paras
 */
function getFilename($ext)
{
    $filename = time() . '-' . uniqid() . '.' . $ext;
    return $filename;
}

function get_session_user()
{
    /*$std               = new \stdClass();
    $std->id           = 5;
    $std->type         = 1;
    $std->real_name    = "chjw";
    $std->org_id       = 1;
    $std->org_name     = "XX大学";
    $std->openid       = "";
    $std->login_openid = "";
    $std->user_code    = "20051015112";
    $std->sex          = "男";
    $std->is_v         = 0;
    $std->relation_id  = 1;
    $std->role_id      = 2;

    session(['user' => $std]);*/
    return session("user");
}

function get_session_user_id()
{
    $user = session("user");
    return $user ? $user->id : 0;
}

function get_wx_user_openid()
{
    $user = session('wechat.oauth_user');
    return $user ? $user['openid'] : '';
}

//获取微信端登录用户的id
function get_wx_user_id()
{
    $user = session("user");
    return $user ? $user->id : 0;
}

function get_wx_user()
{
    return session("user");
}


function startWith($str, $needle)
{
    return strpos($str, $needle) === 0;

}

//第一个是原串,第二个是 部份串
function endWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }
    return (substr($haystack, -$length) === $needle);
}

function wx_nickname_filter($name)
{
    $name = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '', $name);
    $name = preg_replace('/xE0[x80-x9F][x80-xBF]‘.‘|xED[xA0-xBF][x80-xBF]/S', '?', $name);
    $name = preg_replace('/xE0[x80-x9F][x80-xBF]' . '|xED[xA0-xBF][x80-xBF]/S', '?', $name);
    $name = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '', $name);
    return $name;
}

function create_uuid($prefix = "")
{    //可以指定前缀
    $str = md5(uniqid(mt_rand(), true));
    $uuid = substr($str, 0, 8) . '-';
    $uuid .= substr($str, 8, 4) . '-';
    $uuid .= substr($str, 12, 4) . '-';
    $uuid .= substr($str, 16, 4) . '-';
    $uuid .= substr($str, 20, 12);
    return $prefix . $uuid;
}

/**
 * 获取用户密码加密字符串
 * @param $password
 * @param $salt
 * @return string
 */
function encrypt_password($password)
{
    return md5(md5($password));
}

function get_pinyin_simple($str)
{
    return \Overtrue\LaravelPinyin\Facades\Pinyin::abbr($str);
}

function get_pinyin_all($str)
{
    $arr = \Overtrue\LaravelPinyin\Facades\Pinyin::convert($str);
    return implode("", $arr);
}

function millisecond()
{
     return floor(microtime(true) * 1000);
}

/**
 * 二维数组排序
 * @param $array 二维数组
 * @param $key   排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
 * @param $flag
 * @return 二维数组
 * @internal param 排序字段 $k
 */
function arraySort($array,$key,$flag)
{
    $sort = array(
        'direction' => $flag,
        'field'     => $key,
    );
    $arrSort = array();
    foreach($array AS $uniqid => $row){
        foreach($row AS $k=>$value){
            $arrSort[$k][$uniqid] = $value;
        }
    }
    if($sort['direction']){
        array_multisort($arrSort[$sort['field']], constant($sort['direction']), $array);
    }
    return $array;
}

/**
 * 判断$student_id 是否在数组$stat中
 * @param $student_id
 * @param $stat
 * @return bool
 */
function is_stat($student_id,$stat){
    for ($i=0;$i<count($stat);$i++){
        if($stat[$i]['student_id'] == $student_id){
            return true;
        }
    }
    return false;
}
/**
 *文件转为pdf
 * @param $filepath 文件路径
 * @param $source   来源
 * @param $id       文件id
 * @param $ext      文件后缀
 */
function doc2pdf($filepath,$source,$id,$ext) {
    //doc,docx,txt,sql,java,css,js,
    switch($ext){
        case 'doc':
        case 'docx':
        case 'txt':
        case 'sql':
        case 'java':
        case 'css':
        case 'js':
        case 'php':
        case 'jsp':
        case 'vue':
        case 'xls':
        case 'xlsx':
        case 'ppt':
        case 'pptx':
            (new App\Models\Document2PdfInfo($filepath,$source,$id))->request();
            break;
        case 'pdf':
            (new App\Models\Pdf2HtmlInfo($filepath,$source,$id))->save();
            break;
    }

}

/**
 * 生成缩略图函数（支持图片格式：gif、jpeg、png）
 * @author ruxing.li
 * @param  string $src      源图片路径
 * @param  string $filename 保存名字
 * @param  string $filename 保存路径
 * @param  int    $width    缩略图宽度（只指定高度时进行等比缩放）
 * @param  int    $height    缩略图高度（只指定宽度时进行等比缩放）
 * @return bool
 */
function thumbnail($src, $filename, $filepath, $width = 150, $height = null) {
    $path = $filepath;
    if($filename!='')
        $path = $path . '/' . $filename;
    // $path = str_replace('\\','/',$path);
    // dd($path);
    $size = getimagesize($src);
    if (!$size)
        return false;
    list($src_w, $src_h, $src_type) = $size;
    if (!isset($width))
        $width = $src_w * ($height / $src_h);
    if (!isset($height))
        $height = $src_h * ($width / $src_w);

    $src_img = imagecreatefromstring(file_get_contents($src));
    if($src_type==3)
        $dest_img = imagecreate($width, $height);
    else $dest_img = imagecreatetruecolor($width, $height);
    imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $width, $height, $src_w, $src_h);
    if(!is_dir($filepath)){
        mkdir($filepath);
    }
    switch($src_type) {
        case 1 :
            $img_type = 'gif';
            imagegif($dest_img,$path);
            break;
        case 2 :
            $img_type = 'jpeg';
            imagejpeg($dest_img,$path);
            break;
        case 3 :
            $img_type = 'png';
            imagepng($dest_img,$path);
            break;
        default :
            return false;
    }
    imagedestroy($src_img);
    imagedestroy($dest_img);
    return true;
}
/**
 * 得到记录的时间距离当前时间的长度
 * @param  string $big      现在时间
 * @param  string $little   记录更新时间
 */
function time_diff($big,$little){
    $diff = $big-$little;
    if(0<=$diff&&$diff<60){
        return $diff."秒前";
    }elseif(60<=$diff&&$diff<3600)
        return (floor($diff/60))."分钟前";
    elseif(3600<=$diff&&$diff<86400)
        return (floor($diff/3600))."小时前";
    elseif(86400<=$diff&&$diff<2592000)
        return (floor($diff/86400))."天前";
    elseif(2592000<=$diff&&$diff<62208000)
        return (floor($diff/43200*2592000))."月前";
    elseif(62208000<=$diff)
        return (floor($diff/518400*60))."年前";
}

/*
 * 生成n位随机字符串
 * str 字符串
 * num 位数
 * */
function str_rand($num,$str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'){
    $key = '';
    for ( $i = 0; $i < $num; $i++ )
    {
        $key .= $str[ mt_rand(0, strlen($str) - 1) ];
    }
    return $key;
}
/*
 * 把秒转换成天数，小时数，分钟
 * $secs 秒
 * */
function secsToStr($secs) {
    $r = '';
    if($secs>=86400){
        $days=floor($secs/86400);
        $secs=$secs%86400;
        $r=$days.' 天 ';
    }
    if($secs>=3600){
        $hours=floor($secs/3600);
        $secs=$secs%3600;

        $r.=$hours.' 小时 ';
    }
    if($secs>=60){
        $minutes=floor($secs/60);
        $secs=$secs%60;
        $r.=$minutes.' 分钟 ';
    }
//    $r.=$secs.' second';
//    if($secs<>1){$r.='s';
//    }
    return $r;
}