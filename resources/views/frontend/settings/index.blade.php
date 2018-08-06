@extends('frontend.layouts._layout')

@section('link_style')
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/settings.css') }}">
@stop

@section('body')
<div class="_fwfl">
    <div class="settings-wrapper">
        <div class="_fwfl settings-inside" data-settings-page-view>
            <div class="settings-left">
                <div class="_fwfl settings-page profile" id="profile">
                    @include('frontend.settings.sections.setting-header')
                    @include('frontend.settings.sections.setting-publish')
                    @include('frontend.settings.sections.setting-email')
                    @include('frontend.settings.sections.setting-slug')
                    @include('frontend.settings.sections.setting-password')
                    @include('frontend.settings.sections.setting-expertise')
                    @include('frontend.settings.sections.setting-personal')
                    @include('frontend.settings.sections.setting-contact')
                </div>
                <div class="_fwfl settings-page employment" id="employment">
                    @include('frontend.settings.sections.setting-employment')
                </div>

                <div class="_fwfl settings-page skills" id="skills">
                    @include('frontend.settings.sections.setting-skills')
                </div>

                <div class="_fwfl settings-page education" id="education">
                    @include('frontend.settings.sections.setting-education')
                </div>
            </div>
            <div class="settings-right">
                @include('frontend.settings.navigation')
            </div>
        </div>
    </div>
</div>
@stop