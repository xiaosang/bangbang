<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.0, user-scalable=no">
    <title>playSound</title>
    <style type="text/css">
        *{margin: 0;}
        body{height: 1000px;}
        #a{
            height: 200px;
            background: #f0f0f0;
            position: relative;
            overflow: hidden;
        }
        #b{
            background: #ddd;
            line-height: 200px;
            height: 200px;
            position: absolute;
            width: 100%;
            text-align: center;
            left: 0;
            top: 0;
        }
        #b.ease{
            -webkit-transition: all 0.3s ease 0s;
            -moz-transition: all 0.3s ease 0s;
            -ms-transition: all 0.3s ease 0s;
            -o-transition: all 0.3s ease 0s;
            transition: all 0.3s ease 0s;
        }
        #c{
            position: relative;
            height: 40px;
            border-top: 1px solid #999;
            border-bottom: 1px solid #999;
            line-height: 40px;
            width: 100%;
            overflow: hidden;
        }
        #d{
            width: 20000px;
            height: 40px;
            position: relative;
            overflow: hidden;
        }
        #e{
            float: left;
        }
    </style>
    <script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/2.1.1/jquery.min.js"></script>
</head>
<body>
<div id="a">
    <div id="b">移动我吧</div>
</div>
<div id="c">
    <div id="d"><div id="e">jkasjdf jadfas aos as asio asi as a asd asdjasdj asjfas oasjpf aasfjf aosfpao poafpoasf oasjkpf s ioasdfias aio faio aio ioa ioa ioa ioa oa ioa oas ioadioasdasjdioafg  a jdfjia oaa aioasdio asio ioasdiodasioddasiof ioasfio asioaiodfh nyarf hu yasf 8a ffha fau fasuifhasuifaf a fadifg</div></div>
</div>
<script type="text/javascript">
    /*兼容pc & mobile*/
    var hastouch = "ontouchstart" in window?true:false,
        tapstart = hastouch?"touchstart":"mousedown",
        tapmove  = hastouch?"touchmove":"mousemove",
        tapend   = hastouch?"touchend":"mouseup";
    var a = document.getElementById('a');
    var body = document.getElementsByTagName('body')[0];
    var xSlide = 0;
    var yBody = 0;
    /*滑块*/
    a.addEventListener(tapstart,tapdownHandler); //绑定按下去的事件
    function tapdownHandler(event){
        event.preventDefault(); //阻止默认事件
        body.removeEventListener(tapstart,tapdownBody);
        $('#b').removeClass('ease');
        xSlide = hastouch?event.targetTouches[0].pageX-$('#a').offset().top:event.pageX-$('#a').offset().top;
        a.addEventListener(tapmove,tapmoveHandler);
        a.addEventListener(tapend,tapendHandler);
    }
    function tapmoveHandler(event){
        event.preventDefault(); //阻止默认事件
        var x = hastouch?event.targetTouches[0].pageX-$('#a').offset().left:event.pageX-$('#a').offset().left;
        var left = (x - xSlide) + 'px';
        $('#b').css('left', left);
    }
    function tapendHandler(event){
        body.addEventListener(tapstart,tapdownBody);
        a.removeEventListener(tapmove,tapmoveHandler);
        $('#b').addClass('ease').css({'left': 0,'top': 0});
    }

    /*body*/
    body.addEventListener(tapstart,tapdownBody);
    function tapdownBody(event){
        event.preventDefault(); //阻止默认事件
        yBody = hastouch?event.targetTouches[0].pageY:event.pageY;
        body.addEventListener(tapmove,tapmoveBody);
        body.addEventListener(tapend,tapendBody);
    }
    function tapmoveBody(event){
        event.preventDefault(); //阻止默认事件
        var y = hastouch?event.targetTouches[0].pageY:event.pageY;
        var _y = yBody - y;
        var _scroll = $(window).scrollTop();
        $('html,body').scrollTop(_scroll + _y);
    }
    function tapendBody(event){
        body.removeEventListener(tapmove,tapmoveBody);
    }

    /*滚动条*/
    var ew = $('#e').width();
    $('#d').width(ew*2);
    $('#d').append($('#e').clone());
    function scroll_left(){
        var left = $('#c').scrollLeft();
        if(left < ew){
            left ++;
        }else{
            left = 0;
        }
        $('#c').scrollLeft(left);
    }
    var timer = setInterval(scroll_left,20);
</script>
</body>
</html>
