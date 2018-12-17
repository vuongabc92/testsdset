<!DOCTYPE html>
<html lang="en">
    <head>
        <title>:)</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="msapplication-tap-highlight" content="no"/>
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="ajax-error" content="{{ _t('oops') }}" />
        <!--[if lt IE 9]>
            <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/master.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/layout.css') }}">
        @yield('link_style')
        <style>
            @yield('inline_style')
        </style>
    </head>
    <body>
        <header class="_fwfl header">
            <div class="header-inside">
                <nav class="header-nav">
                    <a href="{{ url('/') }}" class="logo"><img src="{{ asset('assets/frontend/images/logo.png') }}" /> </a>
                    @if ( ! auth()->check())
                        <a href="{{ route('front_register') }}" class="_fr btn _btn _btn-blue header-register-btn">{{ _t('register') }}</a>
                    @endif
                    <ul class="_fr _lsn _p0 _m0 navlist">
                        <li class="headnav-contact"><a href="{{ route('front_contact') }}"><span>{{ _t('contact') }}</span></a></li>
                        <li class="headnav-dev"><a href="{{ route('front_developer') }}"><span><i class="fa fa-cog"></i> {{ _t('developer') }}</span></a></li>
                        
                        @if ( ! auth()->check())
                            <li><a href="{{ route('front_login') }}"><span>{{ _t('login') }}</span></a></li>
                        @else
                            <li><a href="{{ route('front_logout') }}"><span>{{ _t('logout') }}</span></a></li>
                            <li><a href="{{ route('front_settings') }}"><img src="/{{ user()->userProfile->avatar() }}" class="avatar" /></a></li>
                        @endif
                    </ul>
                </nav>

            </div>
        </header>
        @yield('body')
        
        <div class="alert alert-bar fade _dn" role="alert" id="alertBar">
            <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <div class="container">
                <span class="_fs13 _fwb" id="alertText"></span>
            </div>
        </div>
        
        <script type="text/javascript" src="{{ asset('assets/frontend/js/jquery_v1.11.1.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/frontend/js/jquery-ui-1.11.4.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/frontend/js/popper.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/frontend/js/bootstrap4.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/frontend/js/bootstrap-switch.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/frontend/js/script.js') }}"></script>
        <script>
            @yield('script')
        </script>
    </body>
</html>