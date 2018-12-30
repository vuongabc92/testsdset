@extends('backend.layouts._layout')
@section('body')
    <div class="_fwfl title-wrap">
        <h3 class="page-title">Themes</h3>
    </div>
    
    <div class="_fwfl _mt20">
        <form class="_fwfl _mb20 form-inline" action="{{ route('back_themes') }}" method="get">
            <div class="form-group">
                {!! Form::text('q', $filterQ, ['class' => 'form-control', 'placeholder' => 'Search...']) !!}
            </div>
            <div class="form-group mx-sm-3">
                {!! Form::select('status', ['' => 'Status', '0' => 'Deactivated', '1' => 'Activated'], $filterStat, ['class' => 'custom-select']) !!}
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-secondary">Filter</button>
            </div>
        </form>
        <table class="table table-bordered table-striped table-hover table-responsive">
            <thead>
                <tr>
                    <td>#</td>
                    <td>Name</td>
                    <td>Slug</td>
                    <td>Activated</td>
                    <td width="220px">Actions</td>
                </tr>
            </thead>
            <tbody>
                @if($themes->count())
                    @php $i = 0; @endphp
                    @foreach($themes as $theme)
                        @php $i++; @endphp
                        <tr>
                            <td>{{ $i + (($themes->currentPage() - 1)  * $maxPerPage) }} </td>
                            <td>{{ $theme->name }}</td>
                            <td>{{ $theme->slug }}</td>
                            <td >
                                @if($theme->activated)
                                    <span class="badge badge-success">Activated</span>
                                @else
                                    <span class="badge badge-danger">Deactivated</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('back_theme_view', ['id' => $theme->id]) }}" class="btn btn-secondary btn-sm">View</a>
                                <a href="{{ route('back_theme_edit', ['id' => $theme->id]) }}" class="btn btn-primary btn-sm">Edit</a>
                                <a href="{{ route('front_theme_preview', ['slug' => $theme->slug]) }}" target="_blank" class="btn btn-info btn-sm">Template</a>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <div class="_fwfl">
            <nav aria-label="User pagination">
                {{ $themes->links('backend.layouts._pagination') }}
            </nav>
        </div>
    </div>
@stop