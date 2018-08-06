<section>
    <div class="_fwfl timeline-container">
        <div class="timeline education-timeline" data-update-education data-update-education-url="{{ route('front_settings_educationbyid') }}" data-remove-education data-remove-education-url="{{ route('front_settings_educationremovebyid') }}">
            @if (count($educations))
                @foreach($educations as $education)
                    <div class="_fwfl timeline-section" id="timeline-section-{{ $education->id }}">
                        <div class="timeline-point"></div>
                        <div class="timeline-content">
                            <h4>{{ $education->college_name }}</h4>
                            <b class="subject">{{ $education->subject }}</b>
                            <span class="qualification">{{ $education->qualification->name }}</span>
                            <div class="time"><b><i class="fa fa-calendar"></i></b><span>{{ Carbon\Carbon::parse($education->start_date)->format('m/Y') }} - {{ Carbon\Carbon::parse($education->end_date)->format('m/Y') }}</span></div>
                            <button class="btn _btn timeline-btn timeline-edit" data-update-education-id="{{ $education->id }}"><i class="fa fa-pencil"></i></button>
                            <button class="btn _btn timeline-btn timeline-remove" data-remove-education-id="{{ $education->id }}" data-confirm-msg="{{ _t('sure_remove') }}"><i class="fa fa-remove"></i></button>
                        </div>
                    </div>
                @endforeach
            @endif
            <div class="_fwfl timeline-section default-timeline">
                <div class="timeline-point"></div>
                <div class="timeline-content">
                    <div class="settings-show">
                        <h4>{{ _t('setting.education.hi') }}</h4>
                        @if ( ! count($educations))
                            <p>{{ _t('setting.education.empty') }}</p>
                        @endif
                        <button class="btn _btn _btn-sm _btn-blue" data-show-form><i class="fa fa-plus"></i> {{ _t('setting.education.add') }}</button>
                    </div>
                    {!! Form::open(['route' => 'front_settings_save_info', 'method' => 'POST', 'class' => 'settings-form', 'id' => 'settings-add-new-employment', 'data-save-form' => '', 'data-requires' => 'college_name|subject|start_month|start_year|end_month|end_year|qualification']) !!}
                        <div class="settings-field-wrapper">
                            {!! Form::text('college_name', '', ['class' => 'settings-field', 'placeholder' => _t('setting.education.collegename')]) !!}
                        </div>
                        <div class="settings-field-wrapper">
                            {!! Form::text('subject', '', ['class' => 'settings-field', 'placeholder' => _t('setting.education.subject')]) !!}
                        </div>
                        @php
                            $start_date = selector_date('start', 50);
                            $end_date   = selector_date('end', 50, 5);
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
                            {!! Form::kingSelect('qualification', qualification(), null, ['id' => 'qualification', 'class' => 'settings-field']) !!}
                        </div>
                        <button type="submit" class="btn _btn _btn-sm _btn-blue-navy _mr8">{{ _t('save') }}</button>
                        <button type="reset" class="btn _btn _btn-sm _btn-gray" data-hide-form>{{ _t('cancel') }}</button>
                        <input type="hidden" name="type" value="_EDUCATION"/>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</section>