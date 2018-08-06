<section>
    <div class="_fwfl cover" style="background-image: url('{{ asset($coverMedium) }}');">
        {!! Form::open(['route' => 'front_settings_upload_cover','files' => true, 'method' => 'POST', 'class' => '_fwfl _dn', 'id' => 'upload_cover_form', 'data-upload-cover']) !!}
        {!! Form::file('__file', ['class' => '_dn', 'id' => 'cover_file_input', 'accept' => 'image/*', 'data-event-trigger' => '#upload_cover_form', 'data-event' => 'change|submit']) !!}
        {!! Form::close() !!}
        <button class="_r2 edit-btn" data-event-trigger='#cover_file_input'  data-event='click|click'>
            <img src="{{ asset('assets/frontend/images/loading_white_16x16.gif') }}" class="_dn"/>
            <i class="fa fa-pencil"></i>
        </button>
    </div>
    <div class="_fwfl appearance">
        <div class="_fr col-md-offset-3 col-md-9 col-xs-12">
            <div class="_fl avatar">
                {!! Form::open(['route' => 'front_settings_upload_avatar','files' => true, 'method' => 'POST', 'class' => '_fwfl _dn', 'id' => 'upload_avatar_form', 'data-upload-avatar']) !!}
                {!! Form::file('__file', ['class' => '_dn', 'id' => 'avatar_file_input', 'accept' => 'image/*', 'data-event-trigger' => '#upload_avatar_form', 'data-event' => 'change|submit']) !!}
                {!! Form::close() !!}
                <button class="_r2 edit-btn" data-event-trigger='#avatar_file_input'  data-event='click|click'>
                    <img src="{{ asset('assets/frontend/images/loading_white_16x16.gif') }}" class="_dn"/>
                    <i class="fa fa-pencil"></i>
                </button>
                <img class="_r50 _fwfl _fh avatar-img" src="{{ asset($avatarMedium) }}" />

            </div>
            <div class="_fwfl _mt15">
                <h3 class="_p0 _m0 _tg6">{{ $userProfile->first_name }} {{ $userProfile->last_name }}</h3>
                <p class="_m0">
                    <strong class="_fs12 _tg8">Visit:</strong> 
                    <a href="{{ $userProfile->cvUrl() }}" class="_tb _fs12 current-slug">{{ preg_replace('/^http:\/\//', '', $userProfile->cvUrl()) }}</a>
                </p>
            </div>
        </div>
    </div>
</section>