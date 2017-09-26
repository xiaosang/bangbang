<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>{{config("app.name")}}</title>
    {{--  <link type="text/css" href="{{ url('dist/css/element-theme/index.css') }}" rel="stylesheet"/>  --}}
    {{--<link href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">--}}
    <link href="{{ url('dist/css/ionicons.min.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ url('dist/css/app.css') }}"/>
    <script type="text/javascript" src="{{ url('dist/js/jquery.min.js') }}"></script>
</head>
<body>
<div id="app"></div>
<script src="{{ mix('js/manifest.js') }}"></script>
<script src="{{ mix('js/vendor.js') }}"></script>
<script src="{{ mix('js/admin.js') }}"></script>
</body>
</html>
