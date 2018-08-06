<div class="selecter" data-selecter>
    {!! Form::select($name, $value, $default, $attributes) !!}
    <label for="{{ $attributes['id'] }}" class="{{ (null === $default) ? '_tga' : '_tg7' }}">{{ (null === $default) ? array_first($value) : $value[$default] }}</label>
    <i class="fa fa-angle-down"></i>
    <img src="{{ asset('assets/frontend/images/loading_gray_24x24.gif') }}" />
</div>