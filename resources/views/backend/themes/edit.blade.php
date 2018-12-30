@extends('backend.layouts._layout')
@section('body')
    <div class="_fwfl title-wrap">
        <h3 class="page-title">Theme edit</h3>
    </div>
    
    <div class="_fwfl _mt20">
        {!! Form::open(['route' => 'back_theme_save', 'method' => 'post', 'files' => true]) !!}
            @if (count($errors) > 0)
            <div class="form-group">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="alert-heading">Whoops!</h4>
                    <hr>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
            <div class="form-group">
                <img src="/{{ $theme->getThumbnail() }}" alt="{{ $theme->name }}" class="img-thumbnail">
                <hr>
            </div>
            <div class="form-group">
                <label>Theme thumbnail (w:200, h:150)</label>
                <label class="_fw custom-file">
                    <input name="thumbnail" type="file" id="file" class="custom-file-input">
                    <span class="custom-file-control"></span>
                </label>
            </div>
            <div class="form-group">
                <label>Theme screenshot (w:800, h:600)</label>
                <label class="_fw custom-file">
                    <input name="screenshot" type="file" id="file" class="custom-file-input">
                    <span class="custom-file-control"></span>
                </label>
            </div>
            <div class="form-group">
                <label for="themeName">Theme name</label>
                {!! Form::text('theme_name', $theme->name, ['class' => 'form-control', 'id' => 'themeName', 'placeholder' => 'Theme name']) !!}
            </div>
            <div class="form-group">
                <label for="themeSlug">Theme slug</label>
                {!! Form::text('theme_slug', $theme->slug, ['class' => 'form-control', 'id' => 'themeSlug', 'placeholder' => 'Theme slug', 'readonly' => '']) !!}
            </div>
            <div class="form-group">
                <label for="themeVersion">Theme version</label>
                {!! Form::text('theme_version', $theme->version, ['class' => 'form-control', 'id' => 'themeVersion', 'placeholder' => 'Theme version']) !!}
            </div>
            <div class="form-group">
                <label for="themeExpetise">Theme expertise</label>
                <div class="_fw">
                    {!! Form::select('expertise_id[]', $expertise, $themeExpt, ['class' => '_fw form-control', 'id' => 'themeExpetise', 'multiple' => 'multiple']) !!}
                </div>
            </div>
            <div class="form-group">
                <label for="themeDesc">Theme description</label>
                {!! Form::textarea('theme_desc', $theme->description, ['class' => 'form-control', 'id' => 'themeDesc', 'rows' => 5]) !!}
            </div>
            <div class="form-group">
                <label>Devices</label>
                <div class="_fw">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input name="devides[]" class="form-check-input" type="checkbox" value="desktop" {{ in_array('desktop', $devices) ? 'checked' : '' }}> Desktop
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input name="devides[]" class="form-check-input" type="checkbox" value="tablet" {{ in_array('tablet', $devices) ? 'checked' : '' }}> Tablet
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input name="devides[]" class="form-check-input" type="checkbox" value="mobile" {{ in_array('mobile', $devices) ? 'checked' : '' }}> Mobile
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="themeTag">Theme tags</label>
                {!! Form::text('theme_tags', $theme->tags, ['class' => 'form-control', 'id' => 'themeTag', 'placeholder' => 'Theme tags']) !!}
            </div>
            {!! Form::hidden('theme_id', $theme->id) !!}
            <a href="{{ route('back_themes') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
            <button type="submit" class="btn btn-primary">Save</button>
        {!! Form::close() !!}
        
    </div>
@stop