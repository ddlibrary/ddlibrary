<nav class="navbar navbar-expand-lg px-2" style="border-radius: 0; background-color: #000000;">
    <a href="{{ URL::to('/') }}" class="navbar-brand" title="Website Logo">
        <img src="{{ getFile('files/logo-dd.png') }}" alt="DDL Logo">
    </a>
    <button class="navbar-toggler navbar-dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav {{ (Lang::locale() != 'en') ? 'ms-auto' : 'me-auto' }}">
            <li class="nav-item active">
                <a class="nav-link" href="{{ URL::to('/') }}"><i class="fas fa-home"></i> @lang('Home') <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ URL::to('/resources') }}"><i class="fas fa-book"></i> @lang('Darakht-e Danesh Library')</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('storyweaver-confirm', ['landing_page' => 'storyweaver_default']) }}" class="nav-link" title="StoryWeaver">
                    <img src="{{ getFile('files/storyweaver-logo.svg') }}"
                         class="storyweaver-logo"
                         alt="StoryWeaver logo"
                    >
                    @lang('StoryWeaver Library')
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ URL::to('add/resourcefile') }}"><i class="fas fa-upload"></i> @lang('Submit a resource')</a>
            </li>
        </ul>
        <ul class="navbar-nav align-items-center">
            @php
                $currentLocale = LaravelLocalization::getCurrentLocale();
                $currentPath = request()->path();
                $redirectPath = null;
                if ($pos = str_contains($currentPath, $currentLocale . '/')) {
                    $redirectPath = substr($currentPath, $pos + 1);
                }
            @endphp
            <li class="nav-item @if(LaravelLocalization::getCurrentLocaleDirection() == 'ltr') border-end @else border-start @endif border-white lh-1" id="no-border-nav-item">
                <a rel="alternate"
                   href="{{ URL::to('/en'.$redirectPath) }}"
                   hreflang="en"
                   title="Language"
                   class="nav-link"
                >
                    English
                </a>
            </li>
            <li class="nav-item @if(LaravelLocalization::getCurrentLocaleDirection() == 'ltr') border-end @else border-start @endif border-white lh-1" id="no-border-nav-item">
                <a rel="alternate"
                   href="{{ URL::to('/fa'.$redirectPath) }}"
                   hreflang="fa"
                   title="Language"
                   class="nav-link"
                >
                    فارسی
                </a>
            </li>
            <li class="nav-item">
                <a rel="alternate"
                   href="{{ URL::to('/ps'.$redirectPath) }}"
                   hreflang="ps"
                   title="Language"
                   class="nav-link"
                >
                    پښتو
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa-solid fa-language"></i> @lang('Other languages')
                </a>
                <div class="dropdown-menu @if(LaravelLocalization::getCurrentLocaleDirection() == 'ltr') dropdown-menu-right @else dropdown-menu-left @endif" aria-labelledby="navbarDropdown">
                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        @unless($localeCode == LaravelLocalization::getCurrentLocale())
                            @if ($localeCode != 'en' and $localeCode != 'fa' and $localeCode != 'ps')
                                <a rel="alternate"
                                   href="{{ URL::to('/'.$localeCode.$redirectPath) }}"
                                   hreflang="{{ $localeCode }}"
                                   title="Language"
                                   class="dropdown-item"
                                >
                                    {{ $properties['native'] }}
                                </a>
                            @endif
                        @endunless
                    @endforeach
                </div>
            </li>
            @if (Auth::check())
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa-solid fa-circle-user fa-2xl"></i>
                    </a>
                    <div class="dropdown-menu @if(LaravelLocalization::getCurrentLocaleDirection() == 'ltr') dropdown-menu-end @else dropdown-menu-start @endif" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ URL::to('/user/profile') }}" title="@lang('Account')">@lang('Account')</a>
                        @if (isAdmin())
                            <a class="dropdown-item" href="{{ URL::to('/admin') }}" title="@lang('Admin panel')">@lang('Admin panel')</a>
                        @endif
                        <hr class="dropdown-divider">
                        <a href="{{ URL::to('logout') }}" class="dropdown-item" title="@lang('Log out')">@lang('Log out')</a>
                    </div>
                </li>
            @else
                <li class="nav-item">
                    <a href="{{ URL::to('/register') }}" class="nav-link" title="@lang('Sign up')"><i class="fas fa-user-plus"></i> @lang('Sign up')</a>
                </li>
                <li class="nav-item">
                    <a href="{{ URL::to('/login') }}" class="nav-link" title="@lang('Log in')"><i class="fas fa-sign-in-alt"></i> @lang('Log in')</a>
                </li>
            @endif
        </ul>
    </div>
</nav>
