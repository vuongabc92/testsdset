@extends('backend.layouts._layout')
@section('body')
    <div class="_fwfl title-wrap">
        <h3 class="page-title">Dashboard</h3>
    </div>
    <div class="_fwfl">
        <div class="_mt20 row">
            <div class="col">
                <div class="jumbotron">
                    <h4 class="display-4">Users <i class="fa fa-heart-o"></i></h4>
                    <p class="lead">You are my everything. I love you all.</p>
                    <hr class="my-4">
                    <p>Total: {{ $totalUser }}</p>
                    <p class="lead">
                        <a class="btn btn-primary" href="{{ route('back_users') }}" role="button">Let's go</a>
                    </p>
                </div>
            </div>
            <div class="col">
                <div class="jumbotron">
                    <h4 class="display-4">Themes</h4>
                    <p class="lead">Good things to delivery.</p>
                    <hr class="my-4">
                    <p>Total: {{ $totalTheme }}</p>
                    <p class="lead">
                        <a class="btn btn-primary" href="{{ route('back_themes') }}" role="button">Go go move it</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@stop