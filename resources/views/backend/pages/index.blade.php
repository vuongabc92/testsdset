@extends('backend.layouts._layout')

@section('body')
    <div class="_fwfl title-wrap">
        <h3 class="page-title">Pages <a href="{{ route('back_page_add') }}" class="btn btn-secondary btn-sm"><i class="fa fa-plus"></i> Add</a></h3>
    </div>
    
    <div class="_fwfl _mt20">
        <table class="table table-bordered table-striped table-hover ">
            <thead>
            <tr>
                <td>ID</td>
                <td>Name</td>
                <td>slug</td>
                <td width="200px">Actions</td>
            </tr>
            </thead>
            <tbody>
                @if($pages->count())
                    @foreach($pages as $page)
                        <tr>
                            <td>{{ $page->id }}</td>
                            <td>{{ $page->title }}</td>
                            <td><a href="{{ url($page->slug) }}">{{ $page->slug }}</a></td>
                            <td>
                                <a href="{{ route('back_page_edit', ['id' => $page->id]) }}" class="btn btn-secondary btn-sm">Edit</a>
                                <form class="_dlb" method="post" action="{{ route('back_page_block') }}" onsubmit="return confirm('Are you sure to process???')">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="pageId" value="{{ $page->id }}">
                                    @if($page->block)
                                        <button class="btn btn-danger btn-sm">Click To Unblock</button>
                                    @else
                                        <button class="btn btn-warning btn-sm">Click to Block</button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <a href="{{ route('back_page_add') }}" class="btn btn-secondary"><i class="fa fa-plus"></i> Add</a>
    </div>
@stop
