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
        html {
            height: 100%;
        }
        body {
            font-family: "Microsoft Yahei";
            background-color: #2f4050;
            padding: 0;
            margin: 0;
            font-size: 14px;
            height: 100%;
        }

        .xheader {
            border-bottom: 1px solid #2f4050;
            background-color: rgba(0, 0, 0, 0.3);
            width: 100%;
            padding: 10px;
            color: #FFF;
            position: fixed;
            font-size: 18px;
        }

        .main_box {
            position: absolute;
            right: 50%;
            left: 50%;
            margin-left: -170px;
            
        }

        #login_account {
            background-color: rgba(0,0,0,0);
            width: 480px;
            height: 320px;
            padding: 20px;
            /*margin: 50px auto;*/
            position: fixed;
            left: 50%;
            top: 45%;
            margin-top: -170px;
            margin-left: -250px;
        }

        #login_account input {
            width: 220px;
            height: 32px;
            margin-bottom: 10px;
            border: 1px solid #999;
            padding-left: 32px;
            color: #666;
            font-size: 14px;
        }

        #login_account #account {
            background: url("/img/icon-user.png") no-repeat;
            background-size: 20px;
            background-position: 2% 50%;
        }

        #login_account #pwd {
            background: url("/img/icon-pwd.png") no-repeat;
            background-size: 20px;
            background-position: 2% 50%;
            margin-top: 5px;
        }

        #login_account .submit {
            display: inline-block;
            text-align: center;
            width: 255px;
            height: 42px;
            border-radius: 6px;
            margin-top: 30px;
            border: 0px;
            cursor: pointer;
            font-size: 16px;
            color: #2f4050;
            border: solid 1px #2f4050;
            opacity : 0.1;
        }

        #login_account .submit:hover {
            background: #E85515;
            color: #fff;
            border: solid 1px #E85515;
        }

        #login_account .btnClose {
            width: 25px;
            height: 25px;
            background: #fff;
            border: solid 1px #999;
            border-top: none;
            color: #666;
            position: absolute;
            top: 0;
            right: 10px;
            font-size: 16px;
            text-align: center;
            cursor: pointer;
        }

        #login_account .btnClose:hover, .login-loading {
            border: solid 1px #E85515;
            border-top: none;
            color: #fff;
            background: #E85515;
        }

        .tips {
            margin-top: 10px;
            display: block;
            color: red;
        }

        .footer {
            width: 100%;
            position: fixed;
            bottom: 10px;
            left: 0;
            right: 0;
            font-size: 12px;
            text-align: center;
            color: #ddd;
        }

        .footer a {
            text-decoration: none;
            color: #ddd;
            padding: 0 5px;
        }

        .footer a:hover {
            color: #FC611F;
        }
    </style>
</head>
<body id="particles-js">
<div class="xheader">
    {{config("app.name")}}
</div>

<div class="main_box">
    <div id="login_account">
        <div style="text-align: center;">
           
            <input id="account" class="input-login" placeholder="用户名" type="text"/>
            <input id="pwd" class="input-login" placeholder="密码" type="password"/>

            <button class="submit">
                登录
            </button>
            <p class="tips">
            </p>
        </div>
    </div> 
</div>
<div class="footer">
    <a target="_blank" href="http://gammainfo.com">@技术支持 特觅科</a>
</div>

</body>
<script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js">
</script>
<script src="js/particles/particles.min.js"></script>
<script src="js/particles/app.js"></script>
<script type="text/javascript">
    $(function () {

        // var guid = '';
        var status = 0;
        var ajaxRequest = null;
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        });
        $(".submit").on("click", function () {
            var _n = $.trim($("#account").val());
            var _p = $.trim($("#pwd").val());
            if (_n == "" || _p == "") {
                $(".tips").html("帐号或密码为空");
                return;
            }
            var self = $(this);
            if (self.hasClass('login-loading')) {
                return;
            }
            self.addClass('login-loading');
            self.text('正在登录...');
            $.post("/login", {name: _n, pass: _p}, function (response) {
                console.log(response)
                if (response.status == 0) {
                    window.location.href = "/";
                } else {
                    $(".tips").html("帐号或密码错误");
                }
            }).always(function () {
                self.text('登录');
                self.removeClass('login-loading');
            });
        })
        $('.input-login').keydown(function () {
            $('.tips').text('');
            if (event.keyCode == "13") {
                $('.submit').trigger('click');
            }
        });

    });
</script>
</html>
