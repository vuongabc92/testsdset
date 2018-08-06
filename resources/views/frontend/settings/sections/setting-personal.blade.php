<section>
    <div class="_fwfl settings-row">
        <div class="_fl col-md-3 col-xs-12">
            <b class="settings-row-title">{{ _t('setting.profile.personal') }}</b>
        </div>
        <div class="_fl col-md-9 col-xs-12">
            <div class="settings-show">
                <div class="_fl col-no-padding col-md-11 col-xs-11">
                    <b class="_fl _tg9 _fs13 personal-intro">
                        @if ('' !== $userProfile->first_name && null !== $birthdate['birthday']['y'])
                            {{ $userProfile->first_name . ' ' . $userProfile->last_name }}, {{ ((int) date('Y') - (int) $birthdate['birthday']['y']) . ' ' . _t('common.yearsold') }}, ...
                        @else
                            {{ _t('setting.profile.personalintro') }}
                        @endif
                    </b>
                </div>
                <div class="_fr col-no-padding col-md-1 col-xs-1">
                    <button type="button" class="settings-expand-btn" data-show-form><i class="fa fa-pencil"></i></button>
                </div>
            </div>
            {!! Form::open(['route' => 'front_settings_save_info', 'method' => 'POST', 'class' => 'settings-form', 'data-save-form' => '', 'data-requires' => '', 'data-personalintro-text' => _t('setting.profile.personalintro'), 'data-persioninfo-year' => _t('common.yearsold')]) !!}
            <div class="settings-field-wrapper">
                <span class="_fl _w50 _pr3">
                    {!! Form::text('first_name', $userProfile->first_name, ['class' => 'settings-field', 'placeholder' => _t('setting.profile.fname')]) !!}
                </span>
                <span class="_fl _w50  _pl3">
                    {!! Form::text('last_name', $userProfile->last_name, ['class' => 'settings-field', 'placeholder' => _t('setting.profile.lname')]) !!}
                </span>
            </div>
            <div class="settings-field-wrapper settings-birthday">
                {!! Form::kingSelect('date', $birthdate['date'], $birthdate['birthday']['d'], ['id' => 'settings-birthdate', 'class' => 'settings-field']) !!}                          
                {!! Form::kingSelect('month', $birthdate['month'], $birthdate['birthday']['m'], ['id' => 'settings-birthmonth', 'class' => 'settings-field']) !!}
                {!! Form::kingSelect('year', $birthdate['year'], $birthdate['birthday']['y'], ['id' => 'settings-birthyear', 'class' => 'settings-field']) !!}
            </div>
            <div class="settings-field-wrapper">
                {!! Form::kingSelect('gender', $genders, $userProfile->gender_id, ['id' => 'settings-gender', 'class' => 'settings-field']) !!}
            </div>
            <div class="settings-field-wrapper">
                {!! Form::kingSelect('marital_status', $maritalStatuses, $userProfile->marital_status_id, ['id' => 'settings-gender', 'class' => 'settings-field']) !!}
            </div>
            <div class="settings-field-wrapper">
                {!! Form::text('hobbies', $userProfile->hobbies, ['class' => 'settings-field', 'placeholder' => _t('setting.profile.hobby_help')]) !!}
            </div>
            <div class="settings-field-wrapper">
                {!! Form::textarea('about_me', $userProfile->about_me, ['class' => 'settings-textarea', 'placeholder' => _t('setting.profile.aboutme'), 'maxlength' => 500, 'rows' => 5]) !!}
            </div>
            <button type="submit" class="btn _btn _btn-sm _btn-blue-navy _mr8">{{ _t('save') }}</button>
            <button type="reset" class="btn _btn _btn-sm _btn-gray" data-hide-form>{{ _t('cancel') }}</button>
            <input type="hidden" name="type" value="_PERSONAL"/>
            {!! Form::close() !!}
        </div>
    </div>
</section>