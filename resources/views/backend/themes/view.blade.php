@extends('backend.layouts._layout')
@section('body')
    <div class="_fwfl title-wrap">
        <h3 class="page-title">Theme details</h3>
    </div>
    
    <div class="_fwfl _mt20">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td width="150px">Thumbnail:</td>
                    <td><img src="/{{ $theme->getThumbnail() }}" alt="{{ $theme->name }}" class="rounded border border-dark"></td>
                </tr>
                <tr>
                    <td>Theme name</td>
                    <td>{{ $theme->name }}</td>
                </tr>
                <tr>
                    <td>Theme slug</td>
                    <td><a href="{{ route('front_theme_preview', ['slug' => $theme->slug]) }}" target="_blank" class="badge badge-primary"><i class="fa fa-link"></i> {{ $theme->slug }}</a></td>
                </tr>
                <tr>
                    <td>Theme version</td>
                    <td><span class="badge badge-info">{{ $theme->version }}</span></td>
                </tr>
                <tr>
                    <td>Device</td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Basic example">
                            @if(count($theme->devices()))
                                @foreach($theme->devices() as $device)
                                    <button type="button" class="btn btn-secondary btn-sm">{{ $device }}</button>
                                @endforeach
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Expertise</td>
                    <td>
                        @if(count($theme->expertises()))
                        <span class="text-info">{{ implode(', ', $theme->expertiseNames()) }}</span>
                        @else
                            <span class="text-success">For all expertise</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Theme status</td>
                    <td>
                        @if($theme->activated)
                            <span class="badge badge-success">Activated</span>
                        @else
                            <span class="badge badge-danger">Deactivated</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td>{{ $theme->description }}</td>
                </tr>
               
            </tbody>
        </table>
        <div class="_fwfl">
            <a href="{{ route('back_themes') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
            <form action="{{ route('back_theme_status') }}" method="post" class="d-inline" onsubmit="return confirm('Are you sure to process???')">
                {{ csrf_field() }}
                @if($theme->activated)
                    <button type="submit" class="btn btn-danger">Deactivate theme</button>
                @else
                    <button type="submit" class="btn btn-success">Active theme</button>
                @endif
                <input type="hidden" name="theme_id" value="{{ $theme->id }}" />
            </form>
        </div>
    </div>
@stop