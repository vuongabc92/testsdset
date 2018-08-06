@if(auth()->check())

<div class="modal fade add-theme-modal" id="addThemeModal" tabindex="-1" role="dialog" aria-labelledby="addThemeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addThemeModalLabel">{{ _t('theme.upload.modaltitle') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route' => 'front_settings_save_info', 'method' => 'POST', 'class' => 'add-theme-form', 'data-save-form' => '', 'data-requires' => 'theme_name|theme_version|theme_desc']) !!}
                <div class="settings-field-wrapper">
                    <button type="button" class="btn _btn _btn-sm _btn-blue" id="uploadThemeBtn" data-event-trigger="#theme_file_input" data-event="click|click" data-loading="blue24">{{ _t('theme.upload.addThemebtn') }}</button>
                    <input type="hidden" name="theme_path"/>
                </div>
                <div class="settings-field-wrapper">
                    {!! Form::text('theme_name', '', ['class' => 'settings-field', 'placeholder' => _t('theme.upload.themename'), 'autocomplete' => 'off']) !!}
                </div>
                <div class="settings-field-wrapper">
                    {!! Form::text('theme_version', '', ['class' => 'settings-field', 'placeholder' => _t('theme.upload.themeversion'), 'autocomplete' => 'off']) !!}
                </div>
                <div class="settings-field-wrapper">
                    {!! Form::textarea('theme_desc', '', ['class' => 'settings-textarea', 'placeholder' => _t('theme.upload.themedesc')]) !!}
                </div>
                <div class="settings-field-wrapper devices-field">
                    <span class="_fwfl _tg5 _mb10 devices-title">{{ _t('theme.upload.themedevices') }}</span>
                    <div class="devices-col">
                        <label class="custom-control custom-checkbox current-co-chbox">
                            <input type="checkbox" name="devices[]" value="desktop" class="custom-control-input"/>
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">{{ _t('theme.upload.themedesktop') }}</span>
                        </label>
                    </div>
                    <div class="devices-col">
                        <label class="custom-control custom-checkbox current-co-chbox">
                            <input type="checkbox" name="devices[]" value="tablet" class="custom-control-input"/>
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">{{ _t('theme.upload.themetablet') }}</span>
                        </label>
                    </div>
                    <div class="devices-col">
                        <label class="custom-control custom-checkbox current-co-chbox">
                            <input type="checkbox" name="devices[]" value="mobile" class="custom-control-input"/>
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">{{ _t('theme.upload.thememobile') }}</span>
                        </label>
                    </div>
                </div>
                <div class="settings-field-wrapper">
                    <span class="_fwfl _tg5 _mb10 devices-title">{{ _t('setting.theme.pickexpertise') }}</span>
                    {!! Form::select('expertise_id[]', $expertises, null, ['id' => 'theme-expertise', 'class' => 'settings-field theme-expertise', 'multiple' => '']) !!}
                </div>
                <div class="settings-field-wrapper">
                    {!! Form::text('theme_tags', '', ['class' => 'settings-field', 'placeholder' => _t('theme.upload.themetags'), 'autocomplete' => 'off']) !!}
                </div>

                <button type="submit" class="btn _btn _btn-sm _btn-blue-navy _mr8">{{ _t('save') }}</button>
                <button type="reset" class="btn _btn _btn-sm _btn-gray" data-dismiss="modal">{{ _t('cancel') }}</button>
                <input type="hidden" name="type" value="_THEME"/>
                {!! Form::close() !!}

                {!! Form::open(['route' => 'front_theme_add_new','files' => true, 'method' => 'POST', 'class' => '_fwfl _dn', 'id' => 'upload_theme_form', 'data-upload-theme']) !!}
                {!! Form::file('__file', ['class' => '_dn', 'id' => 'theme_file_input', 'accept' => '.zip,application/octet-stream,application/zip,application/x-zip,application/x-zip-compressed', 'data-event-trigger' => '#upload_theme_form', 'data-event' => 'change|submit']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endif