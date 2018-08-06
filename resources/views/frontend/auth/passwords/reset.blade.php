@extends('frontend.layouts._layout')

@section('link_style')
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/auth.css') }}">
@stop

@section('title')
    {{ _t('signin.title') }}
@stop

@section('body')
    <div class="_fwfl">
        <div class="auth-box login-box">
            {!! Form::open(['route' => 'front_resetpass_post', 'method' => 'POST', 'class' => '_fwfl auth-form']) !!}
            <input type="hidden" name="token" value="{{ $token }}">
            <h1 class="_fwfl _m0 _p0 auth-form-title">{{ _t('password.form.reset_title') }}</h1>
            <div class="_fwfl auth-field-group first-field-group">
                <label class="_fwfl _fs14 _fwn _tg5" for="email">
                    @if (session('status'))
                        <span class="_tgs">{{ session('status') }}</span>
                    @elseif ($errors->has('email'))
                        <span class="_tr5">{{ $errors->first('email') }}</span>
                    @else
                        {{ _t('common.email') }}
                    @endif
                </label>
                <div class="_fwfl">
                    {!! Form::text('email', $email, ['class' => '_fwfl  _ff0 _r2 auth-field', 'id' => 'email', 'maxlength' => '128', 'autocomplete' => 'off']) !!}
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
                    {!! Form::password('password', ['class' => '_ff0 _r2 _fwfl auth-field', 'id' => 'password', 'maxlength' => '60']) !!}
                </div>
            </div>
            <div class="_fwfl auth-field-group">
                <label class="_fwfl _fs14 _fwn _tg5" for="password-confirmation">
                    @if ($errors->has('password_confirmation'))
                        <span class="_tr5">{{ $errors->first('password_confirmation') }}</span>
                    @else
                        {{ _t('common.passconfirm') }}
                    @endif
                </label>
                <div class="_fwfl">
                    {!! Form::password('password_confirmation', ['class' => '_ff0 _r2 _fwfl auth-field', 'id' => 'password-confirmation']) !!}
                </div>
            </div>
            <div class="_fwfl auth-field-group">
                <button class="_fwfl btn _btn _btn-blue auth-btn"><i class="fa fa-arrow-right"></i></button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop
