<!DOCTYPE html>
<html lang="utf-8">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>{{config("app.name")}}</title>
    <link href="{{ url('css/ionicons.min.css') }}" rel="stylesheet"/>
</head>
<style>
    *{
        margin:0;
        padding:0;
    }
    a{
        text-decoration: none;
    }
    html{
        background: rgb(241,241,241);
    }
</style>
<body>
<div id="app"></div>
<script src="{{ mix('js/manifest.js') }}"></script>
<script src="{{ mix('js/vendor.js') }}"></script>
<script src="{{ mix('js/weixin.js') }}"></script>
@include('bugtags')
</body>
</html>
