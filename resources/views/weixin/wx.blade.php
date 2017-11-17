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
    <style type="text/css">
        html,body {
            margin: 0;
            padding: 0;
            height: 100%;
            background: rgb(241,241,241);
        }
        input {
            outline: none;
            font-size: 16px;
            border: none;
        }
        .but {
            background-color: #1AAD19;
            outline: 0;
            border: none;
            color: #fff;
            border-radius: 5px;
            padding: 5px 15px;
            font-size: 13px;
        }
        .but-send {
            position: absolute;
            right: 15px;
        }
        .but-bottom {
            width: 100%;
            font-size: 18px;
            text-align: center;
            color: #FFFFFF;
            border-radius: 5px;
        }
        .but:active {
            color: rgba(255, 255, 255, 0.6);
            background-color: #179B16;
        }
        .title {
            margin-top: 0.77em;
            margin-bottom: 0.3em;
            padding-left: 15px;
            padding-right: 15px;
            color: #999999;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="title">新用户认证</div>
    <div style="background: rgb(255,255,255);">
        <div style="padding: 10px 15px;">

            <label for="vux-x-input-bqzi3" class="weui-label" style="width: 4em;">手机号</label>
            <input type="text" placeholder="请输入手机号码" class="phone">
            <input type="button" value="发送验证码" class="but but-send">
        </div>
        <div style="padding-left: 15px;">
            <div style="border-top: 1px solid #D9D9D9;"></div>
        </div>
        <div style="padding: 10px 15px;">
            <label for="vux-x-input-bqzi3" class="weui-label" style="width: 4em;">验证码</label>
            <input type="text" placeholder="请输入验证码" class="code">
        </div>
    </div>
    <div style="padding: 10px 15px;">
        <input type="button" value="认证" class="but but-bottom">
    </div>
</body>
<script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js">
</script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    var miao = 60;
    $(function () {
        wx.ready(function () {
//            wx.chooseWXPay({
//                    timestamp: $config.timestamp,
//                    nonceStr: $config.nonceStr,
//                    package: $config.package,
//                    signType: $config.signType,
//                    paySign: $config.paySign, // 支付签名
//                    success: function (res) {
//                        // 支付成功后的回调函数
//                        window.location.href='/success';
//                    }
//                });
            wx.chooseWXPay({
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
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        });
        $(".but-send").click(function(){
            if(isPoneAvailable($(".phone").val())){
                jishi();
                $.post("/wx/phone/send", {phone: $(".phone").val()}, function (response) {
                        console.log(response)
                        if (response.code == 0) {
                            alert(response.msg);
                        } else {
                            alert(response.msg);
                        }
                    })
            }else{
                alert("请输入正确的手机号");
            }
        });
        $(".but-bottom").click(function(){
//            if(isPoneAvailable($(".phone").val())){
//                $.post("/wx/phone/check", {phone: $(".phone").val(),code: $(".code").val()}, function (response) {
//                    console.log(response)
//                    if (response.code == 0) {
//                        window.location.href='/wx#'
//                        location.reload()
//                    } else {
//                        alert(response.msg);
//                    }
//                })
//            }else{
//                alert("请输入正确的手机号");
//            }
            alert("请输入正确的手机号");
            $.post("/wx/pay", {}, function (response) {
                alert(response);
                var $config = response.data;
                alert($config);
//                wx.chooseWXPay({
//                    timestamp: $config.timestamp,
//                    nonceStr: $config.nonceStr,
//                    package: $config.package,
//                    signType: $config.signType,
//                    paySign: $config.paySign, // 支付签名
//                    success: function (res) {
//                        // 支付成功后的回调函数
//                        window.location.href='/success';
//                    }
//                });
            })
        });

    });
    function isPoneAvailable(str) {
        var myreg=/^[1][3,4,5,7,8][0-9]{9}$/;
        if (!myreg.test(str)) {
            return false;
        } else {
            return true;
        }
    }

    function jishi() {
        if(miao==0){
            miao=60;
            $(".but-send").val("发送验证码");
            $(".but-send").attr("disabled", false);
        } else{
            miao--;
            $(".but-send").val(miao+"s后重新发送");
            $(".but-send").attr("disabled", true);
            $(".but-send").css('color','#333');
            setTimeout(jishi,1000);
        }
    }
</script>
</html>
