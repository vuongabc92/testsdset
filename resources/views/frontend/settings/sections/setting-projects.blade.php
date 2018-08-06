<!--<section>
    <h3 class="skills-title">Projects</h3>
    <div class="settings-field-wrapper">
        <ul class="_fl _lsn _p0 _m0 project-list">
            <li><a href="#"><span>Walking dead</span><i class="fa fa-remove"></i></a></li>
            <li><a href="#"><span>Check My Salons</span><i class="fa fa-remove"></i></a></li>
            <li><a href="#"><span>King Of Versailles</span><i class="fa fa-remove"></i></a></li>
            <li><a href="#"><span>Sunbeam Crockpot</span><i class="fa fa-remove"></i></a></li>
            <li><a href="#"><span>Doctor Wicker</span><i class="fa fa-remove"></i></a></li>
        </ul>
        <div class="_fl project-form-wrap">
            {!! Form::open(['route' => 'front_settings_save_info', 'method' => 'POST', 'class' => '_fwfl project-form', 'data-save-form' => '', 'data-requires' => '']) !!}
            <h4 class="_fwfl _m0 add-project-title">Add Project</h4>
            <div class="settings-field-wrapper">
                <button type="button" class="_fwfl project-img-btn"><i class="fa fa-image"></i></button>
            </div>
            <div class="settings-field-wrapper">
                {!! Form::text('project_name', '', ['class' => 'settings-field', 'placeholder' => 'Project name']) !!}
            </div>
            <div class="settings-field-wrapper">
                {!! Form::text('link', '', ['class' => 'settings-field', 'placeholder' => 'Link to project']) !!}
            </div>
            <div class="settings-field-wrapper">
                {!! Form::text('phone_number', '', ['class' => 'settings-field', 'placeholder' => _t('setting.profile.phone')]) !!}
            </div>
            <div class="settings-field-wrapper">
                {!! Form::textarea('description', '', ['class' => 'settings-textarea', 'placeholder' => 'Description']) !!}
            </div>
            <button type="submit" class="btn _btn _btn-sm _btn-blue-navy _mr8">{{ _t('save') }}</button>
            <button type="reset" class="btn _btn _btn-sm _btn-gray" data-hide-form>{{ _t('cancel') }}</button>
            <input type="hidden" name="type" value="_PROJECT"/>
            {!! Form::close() !!}
            
            {!! Form::open(['route' => 'front_settings_upload_avatar', 'files' => true, 'method' => 'POST', 'class' => '_fwfl _dn', 'id' => 'upload_project_img_form', 'data-upload-project-img']) !!}
            {!! Form::file('__file', ['class' => '_dn', 'id' => 'project_img_file_input', 'accept' => 'image/*', 'data-event-trigger' => '#upload_project_img_form', 'data-event' => 'change|submit']) !!}
            {!! Form::close() !!}
        </div>
    </div>
</section>-->