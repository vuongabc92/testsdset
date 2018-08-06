<ul class="_fwfl _ls setting-vertical-nav">
    <li>
        <a href="{{ route('front_settings') }}#profile" data-nav-settings="profile">
            <i class="fa fa-user-o"></i>
            <strong>{{ _t('setting.nav.profile') }}</strong>
            <span class="nav-link-help">{{ _t('setting.nav.profile_help') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('front_settings') }}#skills" data-nav-settings="skills">
            <i class="fa fa-star-o"></i>
            <strong>{{ _t('setting.nav.skills') }}</strong>
            <span class="nav-link-help">{{ _t('setting.nav.skills_help') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('front_settings') }}#education" data-nav-settings="education">
            <i class="fa fa-graduation-cap"></i>
            <strong>{{ _t('setting.nav.education') }}</strong>
            <span class="nav-link-help">{{ _t('setting.nav.education_help') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('front_settings') }}#employment" data-nav-settings="employment">
            <i class="fa fa-briefcase"></i>
            <strong>{{ _t('setting.nav.employment') }}</strong>
            <span class="nav-link-help">{{ _t('setting.nav.employment_help') }}</span>
        </a>
    </li>
    <li class="nav-link-last">
        <a href="{{ route('front_settings_theme') }}" {{ check_route_name('front_settings_theme') ? 'class=active' : '' }}>
            <i class="fa fa-th"></i>
            <strong>{{ _t('setting.nav.theme') }}</strong>
            <span class="nav-link-help">{{ _t('setting.nav.theme_help') }}</span>
        </a>
    </li>
</ul>