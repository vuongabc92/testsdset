<section>
    <div class="_fwfl timeline-container">
        <div class="timeline employment-timeline" data-update-employment data-update-employment-url="{{ route('front_settings_employmentbyid') }}" data-remove-employment data-remove-employment-url="{{ route('front_settings_employmentremovebyid') }}">
            <div class="_fwfl timeline-section">
                <div class="timeline-point"></div>
                <div class="timeline-content">
                    <h4>{{ _t('setting.employment.urexpjob') }}</h4>
                    {!! Form::open(['route' => 'front_settings_save_info', 'method' => 'POST', 'class' => '_fwfl settings-expected-job-form', 'data-save-form' => '', 'data-requires' => 'expected_job']) !!}
                    <div class="settings-field-wrapper _pr">
                        {!! Form::text('expected_job', $userProfile->expected_job, ['class' => 'settings-field', 'placeholder' => _t('setting.employment.expjobwhat')]) !!}
                    </div>
                    <button type="submit" class="btn _btn _btn-sm _btn-blue-navy _mr8">{{ _t('save') }}</button>
                    <input type="hidden" name="type" value="_EMPLOYMENT"/>
                    <input type="hidden" name="employment_expected" value="1"/>
                    {!! Form::close() !!}
                </div>
            </div>
            @if (count($employmentHistories))
                @foreach($employmentHistories as $employment)
                    <div class="_fwfl timeline-section" id="timeline-section-{{ $employment->id }}">
                        <div class="timeline-point"></div>
                        <div class="timeline-content">
                            <h4>{{ $employment->company_name }}</h4>
                            <b class="position">{{ $employment->position }}</b>
                            <a href="{{ $employment->company_website }}" target="_blank">{{ (str_contains($employment->company_website, 'https')) ? str_replace('https://', '', $employment->company_website) : str_replace('http://', '', $employment->company_website) }}</a>
                            <span class="achieve">{{ $employment->achievement }}</span>
                            <div class="time"><b><i class="fa fa-calendar"></i></b><span>{{ Carbon\Carbon::parse($employment->start_date)->format('m/Y') }} - {{ ($employment->is_current) ? _t('setting.employment.current') : Carbon\Carbon::parse($employment->end_date)->format('m/Y') }}</span></div>
                            <button class="btn _btn timeline-btn timeline-edit" data-update-employment-id="{{ $employment->id }}"><i class="fa fa-pencil"></i></button>
                            <button class="btn _btn timeline-btn timeline-remove" data-remove-employment-id="{{ $employment->id }}" data-confirm-msg="{{ _t('sure_remove') }}"><i class="fa fa-remove"></i></button>
                        </div>
                    </div>
                @endforeach
            @endif
            <div class="_fwfl timeline-section default-timeline">
                <div class="timeline-point"></div>
                <div class="timeline-content">
                    <div class="settings-show">
                        <h4>{{ _t('setting.employment.hi') }}</h4>
                        @if ( ! count($employmentHistories))
                            <p>{{ _t('setting.employment.empty') }}</p>
                        @endif
                        <button class="btn _btn _btn-sm _btn-blue" data-show-form><i class="fa fa-plus"></i> {{ _t('setting.employment.add') }}</button>
                    </div>
                    {!! Form::open(['route' => 'front_settings_save_info', 'method' => 'POST', 'class' => 'settings-form', 'id' => 'settings-add-new-employment', 'data-save-form' => '', 'data-requires' => 'company_name|position|start_month|start_year|end_month|end_year']) !!}
                        <div class="settings-field-wrapper">
                            {!! Form::text('company_name', '', ['class' => 'settings-field', 'placeholder' => _t('setting.employment.companyname')]) !!}
                        </div>
                        <div class="settings-field-wrapper">
                            {!! Form::text('position', '', ['class' => 'settings-field', 'placeholder' => _t('setting.employment.position')]) !!}
                        </div>
                        @php
                            $start_date = selector_date();
                            $end_date   = selector_date('end');
                        @endphp
                        <div class="settings-field-wrapper">
                            <div class="_fl _w50 _pr3">{!! Form::kingSelect('start_month', $start_date['m'], null, ['id' => 'start-month', 'class' => 'settings-field']) !!}</div>
                            <div class="_fl _w50 _pl3">{!! Form::kingSelect('start_year', $start_date['y'], null, ['id' => 'start-year', 'class' => 'settings-field']) !!}</div>
                        </div>
                        <div class="settings-field-wrapper">
                            <div class="_fl _w50 _pr3">{!! Form::kingSelect('end_month', $end_date['m'], null, ['id' => 'end-month', 'class' => 'settings-field']) !!}</div>
                            <div class="_fl _w50 _pl3">{!! Form::kingSelect('end_year', $end_date['y'], null, ['id' => 'end-year', 'class' => 'settings-field']) !!}</div>
                        </div>
                        <div class="settings-field-wrapper">
                            {!! Form::text('website', '', ['class' => 'settings-field', 'placeholder' => _t('setting.employment.companyurl')]) !!}
                        </div>
                        <div class="settings-field-wrapper">
                            {!! Form::textarea('achievement', '', ['class' => 'settings-textarea', 'placeholder' => _t('setting.employment.achievement'), 'maxlength' => 500, 'rows' => 5]) !!}
                        </div>
                        <div class="settings-field-wrapper">
                            <label class="custom-control custom-checkbox current-co-chbox">
                                {!! Form::checkbox('current_company', 1, 0, ['class' => 'custom-control-input', 'id' => 'current-company']) !!} 
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">{{ _t('setting.employment.curcompany') }}</span>
                            </label>
                        </div>
                        
                        <button type="submit" class="btn _btn _btn-sm _btn-blue-navy _mr8">{{ _t('save') }}</button>
                        <button type="reset" class="btn _btn _btn-sm _btn-gray" data-hide-form>{{ _t('cancel') }}</button>
                        <input type="hidden" name="type" value="_EMPLOYMENT"/>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</section>