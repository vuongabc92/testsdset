<!DOCTYPE html>
<html>
    <head>
        <title>:)</title>
        <meta charset="UTF-8">
        <meta id="viewport" name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <meta name="msapplication-tap-highlight" content="no"/>
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="ajax-error" content="{{ _t('oops') }}" />
        <!--[if lt IE 9]>
            <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/settings.css') }}">
    </head>
    <body>
        <main>
            @include('frontend.inc.layout-parts')
            @yield('header')
            <div class="container">
                <div class="settings">
                    @yield('body')
                </div>
            </div>
            
            <div class="alert-bar alert fade out" role="alert" id="alertBar">
                <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="container">
                    <span class="_fs13 _fwb" id="alertText"></span>
                </div>
            </div>
        </main>
        
        <script type="text/javascript" src="{{ asset('assets/frontend/js/jquery_v1.11.1.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/frontend/js/jquery-ui-1.11.4.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/frontend/js/bootstrap.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/frontend/js/bootstrap-switch.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/frontend/js/script.js') }}"></script>
        
        <script>
            requirejs(['script']);
        </script>
    </body>
</html>
