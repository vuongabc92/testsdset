@if($themes->count())
    @foreach($themes as $theme)
        <li>
            <a href="{{ route('front_theme_details', ['slug' => $theme->slug]) }}" data-theme-details>
                <div class="theme-leaf">
                    <img src="/{{ $theme->getThumbnail() }}" class="screenshot"/>
                    <div class="quick-info">
                        <h5>{{ $theme->name }}</h5>
                        <div>{{ str_limit($theme->description, 100) }}</div>
                    </div>
                </div>
            </a>
        </li>
    @endforeach
@endif