@extends('backend.layouts._layout')
@section('body')
    <div class="_fwfl title-wrap">
        <h3 class="page-title">Users</h3>
    </div>
    
    <div class="_fwfl _mt20">
        <form class="_fwfl _mb20 form-inline" action="{{ route('back_users') }}" method="get">
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
                    <td>Username</td>
                    <td>Email</td>
                    <td>Activated</td>
                    <td width="150px">Actions</td>
                </tr>
            </thead>
            <tbody>
                @if($users->count())
                    @php $i = 0; @endphp
                    @foreach($users as $user)
                        @php $i++; @endphp
                        <tr>
                            <td>{{ $i + (($users->currentPage() - 1)  * $maxPerPage) }} </td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->activated)
                                    <span class="badge badge-success">Activated</span>
                                @else
                                    <span class="badge badge-danger">Deactivated</span>
                                @endif
                            </td>
                            
                            <td class="text-center">
                                <a href="{{ route('back_user_view', ['id' => $user->id]) }}" class="btn btn-secondary btn-sm" target="_blank">view</a>
                                <a href="{{ route('front_cv', ['slug' => $user->userProfile->slug]) }}" class="btn btn-info btn-sm" target="_blank">Resume</a>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <div class="_fwfl">
            <nav aria-label="User pagination">
                {{ $users->links('backend.layouts._pagination') }}
            </nav>
        </div>
    </div>
@stop