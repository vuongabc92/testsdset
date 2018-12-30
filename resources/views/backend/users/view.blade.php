@extends('backend.layouts._layout')
@section('body')
    <div class="_fwfl title-wrap">
        <h3 class="page-title">User details</h3>
    </div>
    
    <div class="_fwfl _mt20">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td width="150px">Avatar:</td>
                    <td><img src="/{{ $user->userProfile->avatar() }}" alt="{{ $user->username }}" class="rounded-circle user-details-avatar"></td>
                </tr>
                <tr>
                    <td>Username</td>
                    <td>{{ $user->username }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                </tr>
                <tr>
                    <td>Resume</td>
                    <td><a href="{{ route('front_cv', ['slug' => $user->userProfile->slug]) }}" target="_blank">{{ route('front_cv', ['slug' => $user->userProfile->slug]) }}</a></td>
                </tr>
                @if($user->userProfile->phone_number)
                <tr>
                    <td>Phone</td>
                    <td><span class="badge badge-info">{{ $user->userProfile->phone_number }}</span></td>
                </tr>
                @endif
                @if($user->userProfile->website)
                <tr>
                    <td>Website</td>
                    <td><a href="{{ $user->userProfile->website }}" target="_blank">{{ $user->userProfile->website }}</a></td>
                </tr>
                @endif
                <tr>
                    <td>Themes</td>
                    <td><a href="{{ route('back_themes', ['user_id' => $user->id]) }}" target="_blank"><i class="fa fa-link"></i></a></td>
                </tr>
            </tbody>
        </table>
        <div class="_fwfl">
            <a href="{{ route('back_users') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back</a>
            <form action="{{ route('back_user_status') }}" method="post" class="d-inline">
                {{ csrf_field() }}
                @if($user->activated)
                    <button type="submit" class="btn btn-danger">Deactivate user</button>
                @else
                    <button type="submit" class="btn btn-success">Active user</button>
                @endif
                <input type="hidden" name="user_id" value="{{ $user->id }}" />
            </form>
        </div>
    </div>
@stop