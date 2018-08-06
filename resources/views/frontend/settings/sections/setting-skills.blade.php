<section>
    {!! Form::open(['route' => 'front_settings_save_info', 'method' => 'POST', 'class' => '_fwfl skills-form', 'data-add-skill' => '', 'data-required' => 'skill']) !!}
        
        <div class="settings-field-wrapper">
            {!! Form::text('skill', null, ['class' => 'settings-field skills-field', 'placeholder' => _t('setting.skill.add'), 'autocomplete' => 'off', 'data-autocomplete-skill', 'data-autocomplete-skill-url' => route('front_settings_searchskill')]) !!}
            <input type="hidden" name="type" value="_SKILL">
            <div class="skill-suggestion"></div>
        </div>
        <div class="_fwfl _mt12 skill-tags" data-rating="5" data-kill-tag data-rating-url="{{ route('front_settings_save_info') }}" data-killtag-url="{{ route('front_settings_killtag') }}">
            @if(user()->skills->count())
                @foreach(user()->skills as $user_skill)
                <div class="tag" id="{{ $user_skill->id }}">
                    <div class="tag-container">
                        <div class="rating">
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <input type="hidden" value="{{ (is_null($user_skill->votes)) ? 0 : $user_skill->votes }}" class="current-rating"/>
                        </div>
                        <div class="tag-name">{{ $user_skill->skill->name }}</div>
                        <i class="fa fa-close"></i>
                    </div>
                </div>
                @endforeach
            @else
                <span class="_fs13 _fwb _tg7 no-skills">{{ _t('setting.skill.empty') }}</span>
            @endif
        </div>
    {!! Form::close() !!}
</section>