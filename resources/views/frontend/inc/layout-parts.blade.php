@section('header')
    <div class="header">
        <div class="container">
            <nav class="navbar navbar-default navbar-master">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="{{ route('front_settings') }}">
                            <span class="logo logo-header"></span>
                        </a>
                    </div>

                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-right">
                            @if (auth()->check())
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <span class="_fl header-login"><img src="{{ user()->userProfile->avatar() }}" /></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('front_settings') }}">Settings</a></li>
                                    <li><a href="#">Help</a></li>
                                    <li><a href="#">Developer</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href={{ route('front_logout') }}>Logout</a></li>
                                </ul>
                            </li>
                            @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="_mt11 fa fa-bars"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    @if( ! auth()->check())
                                        <li><a href="{{ route('front_login') }}">Login</a></li>
                                        <li><a href="{{ route('front_register') }}">Register</a></li>
                                    @endif
                                    <li><a href="{{ route('front_about') }}">About us</a></li>
                                    <li><a href="#">Help</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="#"><i class="fa fa-gear"></i> Developer</a></li>
                                </ul>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>
@endsection()