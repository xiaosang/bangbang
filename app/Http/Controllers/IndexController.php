<?php

namespace App\Http\Controllers;

use App\Models\MonitorTask;
use App\Models\User;
use Illuminate\Http\Request;
use Log;
use DB;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Payment\Order;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        //依据角色判断跳转到哪里
        $user = get_session_user();
        if ($user) {
            return view('admin.index');
        }
        return view("login");

    }
    //测试专用
    public function test () {
//        (new MonitorTask(35,10))->end();
//        (new MonitorTask(36,30))->end();
//        echo '12341';
        //连接本地的 Redis 服务
//        $redis = new \Redis();
//        $redis->connect('127.0.0.1', 6379);
//        echo "Connection to server sucessfully";
//        //查看服务是否运行
//        echo "Server is running: " . $redis->ping();

    }

        /*显示图片*/
    public function show_img(Request $request) {
        
        $path = storage_path() . '/' . $request->name;
        // dd($path);
        if(file_exists($path)) {
            return response()->file($path);
        }
    }

    protected function options(){ //选项设置
        return [
            // 前面的appid什么的也得保留哦
            'app_id' => 'wx2fffc402a50e03a5', //你的APPID
            'secret'  => '956397f1970f6d1b114a8ac835bc0a77',     // AppSecret
            // 'token'   => 'your-token',          // Token
            // 'aes_key' => '',                    // EncodingAESKey，安全模式下请一定要填写！！！
            // ...
            // payment
            'payment' => [
                'merchant_id'        => '1439601702',
                'key'                => 'def56bbd76f33932dbce862cd87d59de',
                // 'cert_path'          => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
                // 'key_path'           => 'path/to/your/key',      // XXX: 绝对路径！！！！
                'notify_url'         => 'http://juankuan.marchsoft.cn/home/pay/wxnotify',       // 你也可以在下单时单独设置来想覆盖它
                // 'device_info'     => '013467007045764',
                // 'sub_app_id'      => '',
                // 'sub_merchant_id' => '',
                // ...
            ],
        ];
    }
    public function dopay () {

    }
//传入订单ID即可，我这里是通过订单，来查询该订单的金额，当然你也可以自定义金额
    public function pay(){
//        $id = ;//传入订单ID
//        $order_find = ; //找到该订单
//        $mch_id = xxxxxxx;//你的MCH_ID
        $options = $this->options();
        $app = new Application($options);
        $payment = $app->payment;
        $out_trade_no = date("YmdHis"); //拼一下订单号
        $attributes = [
            'trade_type'       => 'JSAPI', // JSAPI，NATIVE，APP...
            'body'             => '助力三月',
            'detail'           => "woshitest",//我这里是通过订单找到商品详情，你也可以自定义
            'out_trade_no'     => $out_trade_no,
            'total_fee'        => 1*100, //因为是以分为单位，所以订单里面的金额乘以100
             'notify_url'       => 'http://juankuan.marchsoft.cn/home/pay/wxnotify', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
             'openid'           => get_wx_user_openid(), // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
            // ...
        ];
        $order = new Order($attributes);
        $result = $payment->prepare($order);
        dd($result);
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){
//            $order_find->out_trade_no = $out_trade_no; //在这里更新订单的支付ID
//            $order_find->save();
            // return response()->json(['result'=>$result]);
            $prepayId = $result->prepay_id;
            $config = $payment->configForAppPayment($prepayId);
            dd(response()->json($config));
        }

    }
//下面是回调函数
    public function paySuccess(){
        $options = $this->options();
        $app = new Application($options);
        $response = $app->payment->handleNotify(function($notify, $successful){
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
//            $order = ExampleOrder::where('out_trade_no',$notify->out_trade_no)->first();
//            if (count($order) == 0) { // 如果订单不存在
//                return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
//            }
//            // 如果订单存在
//            // 检查订单是否已经更新过支付状态
//            if ($order->pay_time) { // 假设订单字段“支付时间”不为空代表已经支付
//                return true; // 已经支付成功了就不再更新了
//            }
//            // 用户是否支付成功
//            if ($successful) {
//                // 不是已经支付状态则修改为已经支付状态
//                $order->pay_time = time(); // 更新支付时间为当前时间
//                $order->status = 6; //支付成功,
//            } else { // 用户支付失败
//                $order->status = 2; //待付款
//            }
//            $order->save(); // 保存订单
            return true; // 返回处理完成
        });
    }
}