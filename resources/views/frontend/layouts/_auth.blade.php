<!DOCTYPE html>
<html>
    <head>
        <title>@yield('title')</title>
        <meta charset="UTF-8">
        <meta id="viewport" name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <meta name="msapplication-tap-highlight" content="no"/>
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/auth.css') }}">
    </head>
    <body class="auth-page">
        <header class="_fwfl">
            <div class="_mw970 _ma header-inside">
                <a href="/" class="logo"></a>
                @yield('button')
            </div>
        </header>

        <div class="_fwfl auth-wrap">
            @yield('body')
        </div>
        
        <script type="text/javascript" src="{{ asset('assets/frontend/js/jquery_v1.11.1.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/frontend/js/jquery-ui-1.11.4.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/frontend/js/bootstrap.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/frontend/js/bootstrap-switch.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/frontend/js/script.js') }}"></script>
    </body>
</html>
