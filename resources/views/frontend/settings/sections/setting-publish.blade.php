<section class="_mt20">
    <div class="_fwfl settings-row">
        <div class="_fl col-md-3 col-xs-12">
            <b class="settings-row-title">{{ _t('setting.profile.publish') }}</b>
        </div>
        <div class="_fr col-md-9 col-xs-12">
            <form action="{{ route('front_setting_publish_profile') }}" method="POST" class="_fwfl">
                <span class="_fl"><input type="checkbox" name="publish_profile" id="publish-cv-swicher" {{ ($userProfile->publish) ? 'checked' : '' }}></span>
                <label class="_fl _m0 _ml10 _mt3 _fs13 _tg7" for="publish-cv-swicher">{{ _t('setting.profile.publishcs_msg') }}</label>
                <span class="inline-notification success">{{ _t('saved') }}</span>
                <span class="inline-notification error">{{ _t('oops') }}</span>
            </form>
            <span class="_mt5 settings-help-text">{{ _t('setting.profile.publish_note') }}</span>
        </div>
    </div>
</section>