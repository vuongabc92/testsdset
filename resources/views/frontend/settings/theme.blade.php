@extends('frontend.layouts._layout')

@section('link_style')
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/settings.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/theme-tree.css') }}">
@stop

@section('body')
    <div class="current-theme-wrap">
        <div class="_fwfl settings-theme-inside">
            @if(user()->isAdmin())
            <div class="_fwfl _mt20">
                <button class="add-new-theme-btn" data-toggle="modal" data-target="#addThemeModal"><i class="fa fa-plus"></i> {{ _t('setting.theme.addnewtheme') }}</button>
            </div>
            @endif

            <div class="_fwfl _mt20 current-activated-theme">
                <div class="settings-left">
                    <div class="screenshot">
                        <span class="badge badge-info badge-current-theme"><i class="fa fa-check"></i> {{ _t('theme.curActivated') }}</span>
                        <img src="/{{ $currentTheme->getScreenshot() }}">
                    </div>
                    <div class="_fwfl _mt20 activated-info">
                        <a href="{{ route('front_theme_details', ['slug' => $currentTheme->slug]) }}" data-theme-details class="_fl _btn btn _btn-blue-navy">{{ _t('theme.details') }}</a>
                        <a href="/" class="_fl _btn btn _btn-gray">{{ _t('theme.change') }}</a>
                    </div>
                </div>
                <div class="settings-right">
                    @include('frontend.settings.navigation')
                </div>
            </div>
        </div>
    </div>
    @if($themes->count())
    <div class="_fwfl uploaded-theme-wrap">
        <div class="uploaded-theme-title">
            <div class="_fwfl uploaded-theme-title-inside">
                <h3>{{ _t('theme.yourUploaded') }}</h3>
            </div>
        </div>
        <div class="_fwfl themes-inside">
            <ol class="_lsn _p0 _m0 theme-tree" @if (auth()->check()) data-go-lazy data-current="0" data-url="{{ route('front_themes_lazy') }}" @endif data-theme-details>
                @include('frontend.settings.theme-item')
            </ol>

            <div class="_fwfl text-center loadmore-wrap">
                <div class="_fwfl">
                    <span class="loading-more">Loading more...</span>
                </div>
                <div class="_fwfl">
                    @if (auth()->check())
                        <a href="#" class="load-more-btn" data-load-more data-target=".theme-tree">{{ _t('load_more') }}</a>
                    @else
                        <a href="{{ route('front_login') }}" class="load-more-btn">{{ _t('load_more') }}</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
    
    
    @include('frontend.inc.popup-theme-details')
    
    @if(user()->isAdmin())
        @include('frontend.inc.popup-add-theme')
    @endif
@stop