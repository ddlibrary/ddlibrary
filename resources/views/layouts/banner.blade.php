@push('styles')
<link rel="stylesheet" href="{{ asset('css/fontawesome-all.min.css') }}">
@endpush
<header class="header">
    <div class="ddl-logo">
        <a href="{{ URL::to('/') }}"><img class="header-img" src="{{ asset('storage/files/logo-dd.png') }}"></a>
    </div>
    <i class="fas fa-align-justify fa-3x icons" id="toggle" onclick="openNav()"></i>

    <div id="myNav" class="overlay">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <div class="overlay-content">
            <div class="language-container">
            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                <a rel="alternate" href="{{ URL::to('/'.$localeCode) }}" hreflang="{{ $localeCode }}">
                {{ $properties['native'] }}
                </a>
            @endforeach
            </div>
            <a href="{{ URL::to('/') }}">Home</a>
            <a href="{{ URL::to('resources') }}">Browse</a>
            <a href="{{ URL::to('resources/add/step1') }}">Upload a Resource</a>
            @if (Auth::check())
            <a href="{{ URL::to('logout') }}">Log Out</a>
            @if (isAdmin())
            <a href="{{ URL::to('/admin') }}">Admin Panel</a>
            @endif
            @else
            <a href="{{ URL::to('/login') }}">Sign In</a>
            <a href="{{ URL::to('/register') }}">Register</a>
            @endif
        </div>
    </div>
    <nav class="header-right">
        <ul class="language-content">
            @if (Auth::check())
            <li>
                @lang('Welcome'): <a class="username" href="{{ URL::to('users/view/'.Auth::id()) }}"> {{ Auth::user()->username }}</a>
            </li>
            @endif
            <?php
            $supportedLocals = array();
            $newId = array();
                foreach($app['config']->get('laravellocalization.localesOrder') as $localeCode)
                {
                    $supportedLocals[] = $localeCode;
                }
                
                if(isset($translations)){
                    foreach($translations AS $tr){
                        if(in_array($tr->language, $supportedLocals)){
                            $newId[$tr->language] = $tr->id;
                        }
                    }
                }
            ?>

            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            @if(request()->segment(2) == "" || (request()->segment(2) != "resource" && request()->segment(2) != "page" && request()->segment(2) != "news" && request()->segment(2) != "node"))
                <li>
                    <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                    {{ $properties['native'] }}
                    </a>
                </li>

            @elseif(isset($newId[$localeCode]) && count($newId) > 0)
                <?php 
                    $currentUrl = explode('/',url()->current());
                    $index = count($currentUrl) - 1;
                    $value = $currentUrl[$index];
                    $currentUrl[$index] = $newId[$localeCode];
                    $newUrl = implode($currentUrl, '/');
                ?>
                <li>
                    <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, $newUrl, [], true) }}">
                    {{ $properties['native'] }}
                    </a>
                </li>
            @else
                <li>
                    <a rel="alternate" style="text-decoration: line-through;" hreflang="{{ $localeCode }}">
                    {{ $properties['native'] }}
                    </a>
                </li>
            @endif
            @endforeach
        </ul>
        <ul class="main-navigation">
            <?php
            $classNames = array(
                125 => 'fa-home',
                566 => 'fa-align-justify',
                131 => 'fa-upload'
            );
            ?>
            @foreach ($menu->where('location', 'top-menu')->where('language', app()->getLocale()) as $tmenu)
            <li>
                <a href="{{ URL::to($tmenu->path) }}"><i class="fas {{ $classNames[$tmenu->tnid]}} fa-lg icons"></i>{{ $tmenu->title }}</a>
            </li>
            @endforeach
            @if (Auth::check())
            <li>
                <a href="{{ URL::to('logout') }}" ><i class="fas fa-sign-in-alt fa-lg icons"></i>@lang('Log Out')</a>     
            </li>
            @if (isAdmin())
            <li>
                <a href="{{ URL::to('/admin') }}"><i class="fas fa-user fa-lg icons"></i>@lang('Admin Panel')</a>
            </li>
            @endif
            @else
            <li>
                <a href="{{ URL::to('/login') }}"><i class="fas fa-sign-in-alt fa-lg icons"></i>@lang('Sign In')</a>
            </li>
            <li>
                <a href="{{ URL::to('/register') }}"><i class="fas fa-save fa-lg icons"></i>@lang('Register')</a>
            </li>
            @endif
        </ul>
    </nav>
</header>