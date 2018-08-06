@extends('frontend.layouts._layout')

@section('title')
    {{ _t('signup.title') }}
@stop

@section('link_style')
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/auth.css') }}">
@stop

@section('body')
<div class="_fwfl">
    <div class="auth-box register-box">
        @include('frontend.inc.register-form')
    </div>
</div>
@stop