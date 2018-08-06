<section>
    <div class="_fwfl settings-row">
        <div class="_fl col-md-3 col-xs-12">
            <b class="settings-row-title">{{ _t('setting.profile.pass') }}</b>
        </div>
        <div class="_fl col-md-9 col-xs-12">
            <div class="settings-show">
                <span class="settings-help-text">{{ _t('setting.profile.pass_note') }}</span>
                <span class="_fl _cp btn _btn _btn-red _mt10" data-show-form>{{ _t('setting.profile.pass_btn') }}</span>
            </div>
            {!! Form::open(['route' => 'front_settings_save_info', 'method' => 'POST', 'class' => 'settings-form', 'data-save-form' => '', 'data-requires' => (($fromFb) ? '' : 'old_password|') . 'new_password|new_password_confirmation']) !!}
            <div class="settings-field-wrapper{{ ($fromFb ? ' _dn' : '') }}">
                {!! Form::password('old_password', ['class' => 'settings-field', 'placeholder' => _t('setting.profile.oldpass')]) !!}
            </div>
            <div class="settings-field-wrapper">
                {!! Form::password('new_password', ['class' => 'settings-field', 'placeholder' => _t('setting.profile.newpass')]) !!}
            </div>
            <div class="settings-field-wrapper">
                {!! Form::password('new_password_confirmation', ['class' => 'settings-field', 'placeholder' => _t('setting.profile.renewpass')]) !!}
            </div>
            <button type="submit" class="btn _btn _btn-sm _btn-blue-navy _mr8">{{ _t('save') }}</button>
            <button type="reset" class="btn _btn _btn-sm _btn-gray" data-hide-form>{{ _t('cancel') }}</button>
            <input type="hidden" name="type" value="_PASS"/>
            {!! Form::close() !!}
        </div>
    </div>
</section>