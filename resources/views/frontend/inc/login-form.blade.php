{!! Form::open(['route' => 'front_login_post', 'method' => 'POST', 'class' => '_fwfl auth-form', 'id' => 'landingLoginForm', 'data-required' => 'email|password']) !!}
<h1 class="_fwfl _m0 _p0 auth-form-title">{{ _t('loginin.form.title') }}</h1>
<div class="_fwfl outworld-auth auth-field-group first-field-group">
    <button type="button" class="btn _btn btn-fb" onclick="document.location.href='{{ $fbLoginUrl }}'"><span>{{ _t('login_with') }} </span>Facebook</button>
    <button type="button" class="btn _btn btn-google" onclick="document.location.href='{{ $googleLoginUrl }}'"><span>{{ _t('login_with') }} </span>Google</button>
    <span class="auth-or">Or</span>
</div>
<div class="_fwfl auth-field-group">
    <label class="_fwfl _fs14 _fwn _tg5" for="email">
        @if ($errors->has('email'))
            <span class="_tr5">{!! $errors->first('email') !!}</span>
        @else
            {{ _t('common.email') }}
        @endif
    </label>
    <div class="_fwfl">
        {!! Form::text('email', '', ['class' => '_fwfl  _ff0 _r2 auth-field', 'id' => 'email', 'maxlength' => '128', 'autocomplete' => 'off']) !!}
    </div>
</div>
<div class="_fwfl auth-field-group">
    <label class="_fwfl _fs14 _fwn _tg5" for="password">
        @if ($errors->has('password'))
            <span class="_tr5">{{ $errors->first('password') }}</span>
        @else
            {{ _t('common.pass') }}
        @endif

    </label>
    <div class="_fwfl">
        {!! Form::password('password', ['class' => '_ff0 _r2 _fwfl auth-field', 'id' => 'password', 'maxlength' => '60', 'autocomplete' => 'off']) !!}
    </div>
</div>
<div class="_fwfl auth-field-group">
    <label class="custom-control custom-checkbox login-remember-chbox">
        {!! Form::checkbox('remember', '1', true, ['class' => 'custom-control-input']) !!}
        <span class="custom-control-indicator"></span>
        <span class="custom-control-description">{{ _t('signin.remember') }}</span>
    </label>
</div>
<div class="_fwfl auth-field-group">
    <button class="_fwfl btn _btn _btn-blue auth-btn"><i class="fa fa-arrow-right"></i></button>
</div>
<div class="_fwfl">
    <a href="{{ route('front_forgotpass') }}" class="_fr _tb">{{ _t('signin.lostpass') }}</a>
</div>
{!! Form::close() !!}