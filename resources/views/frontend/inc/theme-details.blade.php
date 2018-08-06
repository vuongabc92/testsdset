<div class="_fwfl theme-details-header" id="themeDetailsHeader">
    <a href="#" id="themeAuthorAvatar">
        <img class="_r50" src="{{ asset($userProfile->avatar()) }}" />
    </a>
    <h1><span id="themeName">{{ $theme->name }}</span> <span class="theme-details-version badge badge-secondary" id="themeVersion">{{ $theme->version }}</span></h1>
    <h2>
        <span class="theme-by">{{ _t('theme.details_by') }} <a href="#">{{ (empty($userProfile->first_name)) ? $theme->user->username : $userProfile->first_name . ' ' . $userProfile->last_name }}</a></span>
        <span class="theme-date">{{ _t('theme.details_on') }} <span class="_tgb">{{ $theme->createdAtFormat('M d, Y') }}</span></span>
    </h2>
    @if ( ! check_route_name('front_theme_details'))
    <button type="button" class="close theme-popup-close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    @endif
</div>
<div class="_fwfl theme-details-content">
    <div class="theme-details-screenshot" id="themeScreenshot">
        <img src="{{ asset($theme->getScreenshot()) }}" />
        <ul class="_pa _lsn _p0 theme-devices">
            @if ($theme->devices())
                @foreach ($theme->devices() as $device)
                    <li><i class="fa fa-{{ $device }}"></i></li>
                @endforeach
            @endif
        </ul>
    </div>
    
    <div class="theme-details-meta">
        <div class="_fwfl theme-details-actions" id="themeAction">
            @if(auth()->check())
            <form action="{{ route('front_theme_install') }}" method="post" id="installThemeForm">
                {{ csrf_field() }}
                <input type="hidden" name="theme_id" value="{{ $theme->id }}"/>
                <input type="hidden" name="cv_url" value="{{ user()->userProfile->cvUrl() }}"/>
                <button type="submit" class="btn _btn _btn-blue-navy" data-finished-text="{{ _t('theme.installed') }}">
                    {{ _t('theme.install') }}
                </button>
            </form>
            <a href="{{ route('front_theme_preview', $theme->slug) }}" class="btn _btn _btn-gray" target="_blank" id="themePreviewBtn">{{ _t('theme.preview') }}</a>
            @else 
                <a href="{{ route('front_login') }}" class="btn _btn _btn-blue-navy _mr10">{{ _t('theme.install') }}</a>
                <a href="{{ route('front_login') }}" class="btn _btn _btn-gray">{{ _t('theme.preview') }}</a>
            @endif
        </div>
    </div>
    <div class="theme-details-desc" id="themeDesc">{{ $theme->description }}</div>
</div>
<script>
    $('#installThemeForm').installTheme();
</script>