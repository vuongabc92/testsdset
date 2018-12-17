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
        {!! Form::open(['route' => 'front_forgotpass_post', 'method' => 'POST', 'class' => '_fwfl auth-form', 'data-required' => 'email']) !!}
            <h1 class="_fwfl _m0 _p0 auth-form-title">{{ _t('password.form.forgot_title') }}</h1>
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
                    {!! Form::text('email', '', ['class' => '_fwfl  _ff0 _r2 auth-field', 'id' => 'email', 'maxlength' => '128', 'autocomplete' => 'off']) !!}
                </div>
            </div>
            <div class="_fwfl auth-field-group">
                <button class="_fwfl btn _btn _btn-blue auth-btn"><i class="fa fa-arrow-right"></i></button>
            </div>
        {!! Form::close() !!}
    </div>
</div>
@stop