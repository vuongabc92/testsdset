@extends('frontend.layouts._layout')

@section('link_style')
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/page.css') }}">
@stop

@section('body')
<div class="_fwfl">
    <header class="page-header" style="background-image: url({{ asset(($page) ? $page->getBannerImage() : '') }})">
        <div class="constraint">
            <h1>{!! ($page) ? $page->title : 'Developer' !!}</h1>
        </div>
    </header>
    
    <div class="page-container">
        <div class="_fwfl page-inside">

            <div class="_fwfl page-content">
                <div class="page-left">
                    {!! ($page) ? $page->content : '' !!}
                </div>
            </div>
        </div>
    </div>
</div>
@stop