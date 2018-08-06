@extends('frontend.layouts._layout')

@section('link_style')
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/settings.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/theme-tree.css') }}">
@stop

@section('body')
<div class="_fwfl">
    <div class="theme-details-wrap">
        <div class="_fwfl theme-details-inside">
            @include('frontend.inc.theme-details')
        </div>
    </div>
</div>
@stop