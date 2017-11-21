<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="IE=edge" http-equiv="X-UA-Compatible"/>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="{{csrf_token()}}" id="csrf_token" name="csrf_token"/>
    <title>
        {{config("app.name")}}
    </title>

</head>
<body>
</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    $(function () {
        wx.config({
            debug: false,
            appId: '{{$js["appId"]}}',
            timestamp: '{{$js["timestamp"]}}',
            nonceStr: '{{$js["nonceStr"]}}',
            signature: '{{$js["signature"]}}',
            jsApiList: ["chooseWXPay"]
        });
        wx.ready(function () {
            wx.chooseWXPay({
                appId: '<?= $config['appId'] ?>',
                timestamp: '<?= $config['timestamp'] ?>',
                nonceStr: '<?= $config['nonceStr'] ?>',
                package: '<?= $config['package'] ?>',
                signType: '<?= $config['signType'] ?>',
                paySign: '<?= $config['paySign'] ?>',
                success: function (res) {
                    alert(132);
                    /*$.get('/student/query_order',function(res){
                        if(res.code == 0){
                            $(".pay_status").html("已支付");
                        }else{
                            alert(res.msg);
                        }
                    });*/
                }
            });
        })

    });
</script>
</html>
