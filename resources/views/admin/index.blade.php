<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>{{config("app.name")}}</title>
    <link href="{{ url('css/ionicons.min.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ url('css/app.css') }}"/>
    <link rel="stylesheet" href="{{ url('css/main.css') }}"/>
    <script type="text/javascript" src="{{ url('js/jquery.min.js') }}"></script>
    <script>
        window.GammaApp = {
            appName: "{{ config('app.name') }}"
        };
    </script>
</head>
<body>
<div id="app"></div>
<script src="{{ mix('js/manifest.js') }}"></script>
<script src="{{ mix('js/vendor.js') }}"></script>
<script src="{{ mix('js/admin.js') }}"></script>
</body>
</html>
