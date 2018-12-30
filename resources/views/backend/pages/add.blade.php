@extends('backend.layouts._layout')

@section('body')
    <div class="_fwfl title-wrap">
        <h3 class="page-title">Pages</h3>
    </div>
    
    <div class="_fwfl _mt20">
        {!! Form::open(['route' => 'back_page_save', 'method' => 'post', 'files' => true]) !!}

            @if($errors->any())
                <div class="form-group">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <ul class="_ls">
                            @foreach ($errors->all() as $error)
                                <li>+ {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="form-group">
                <label>Banner</label>
                <label class="_fw custom-file">
                    <input name="banner" type="file" id="file" class="custom-file-input">
                    <span class="custom-file-control"></span>
                </label>
            </div>
            <hr>
            <div class="form-group">
                {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Page title']) !!}
            </div>
            <div class="form-group">
                {!! Form::text('slug', null, ['class' => 'form-control', 'placeholder' => 'Page slug']) !!}
            </div>
            <div class="form-group">
                {!! Form::textarea('content', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-secondary">Save</button>
                <a class="btn btn-link" href="{{ route('back_pages') }}"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
        {!! Form::close() !!}
    </div>
@stop

@section('script')
    <script type="text/javascript" src="{{ asset('assets/backend/tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            selector: 'textarea',
            height: 150,
            plugins: 'print preview code searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern help',
            toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | removeformat | code',
            image_advtab: true,
            content_css: [
                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                '//www.tinymce.com/css/codepen.min.css'
            ]
        });//powerpaste advcode mediaembed tinymcespellchecker a11ychecker linkchecker
    </script>
@stop
