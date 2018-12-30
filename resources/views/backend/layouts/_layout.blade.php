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
        <link rel="stylesheet" href="{{ asset('assets/backend/css/master.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/backend/css/layout.css') }}">
        @yield('link_style')
        <style>
            @yield('inline_style')
        </style>
    </head>
    <body>
        
        <header class="_fwfl">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container">
                    <a class="navbar-brand" href="/">OCTOCV</a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('back_dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('back_users') }}">Users <i class="fa fa-heart-o"></i></a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Themes
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="nav-link"  href="{{ route('back_themes') }}">List all</a>
                                <a class="nav-link" href="{{ route('back_theme_screenshot') }}">Pages</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('back_pages') }}">Pages</a>
                        </li>
                    </ul>
                </div>
                </div>
            </nav>
        </header>
        <div class="container">
            <div class="_fwfl _mb20 container-inside">
                @yield('body')
            </div>
        </div>
        
        <script type="text/javascript" src="{{ asset('assets/backend/js/jquery_v1.11.1.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/backend/js/jquery-ui-1.11.4.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/backend/js/popper.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/backend/js/bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/backend/js/bootstrap-switch.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/backend/js/script.js') }}"></script>
        @yield('script')
    </body>
</html>