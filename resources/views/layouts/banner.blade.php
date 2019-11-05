<header class="header">
    <div class="ddl-logo">
        <a href="{{ URL::to('/') }}" title="Website Logo"><img class="header-img" src="{{ asset('storage/files/logo-dd.png') }}" alt="Website Logo"></a>
    </div>
    <i class="fas fa-align-justify fa-3x icons" id="toggle" onclick="openNav()"></i>

    <div id="myNav" class="overlay">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()" title="Close Navigation">&times;</a>
        <div class="overlay-content">
            <div class="language-container">
            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                <a rel="alternate" href="{{ URL::to('/'.$localeCode) }}" hreflang="{{ $localeCode }}" title="Language">
                {{ $properties['native'] }}
                </a>
            @endforeach
            </div>
            <a href="{{ URL::to('/') }}" title="@lang('Home')">@lang('Home')</a>
            <a href="{{ URL::to('resources') }}" title="@lang('Browse')">@lang('Browse')</a>
            <a href="{{ URL::to('resources/add/step1') }}" title="@lang('Upload a Resource')">@lang('Upload a Resource')</a>
            @if (Auth::check())
            <a href="{{ URL::to('logout') }}" title="@lang('Log Out')">@lang('Log Out')</a>
            @if (isAdmin())
            <a href="{{ URL::to('/admin') }}" title="@lang('Admin Panel')">@lang('Admin Panel')</a>
            @endif
            @else
            <a href="{{ URL::to('/login') }}" title="@lang('Sign In')">@lang('Sign In')</a>
            <a href="{{ URL::to('/register') }}" title="@lang('Register')">@lang('Register')</a>
            @endif
        </div>
    </div>
    <nav class="header-right">
        <ul class="language-content">
            @if (Auth::check())
            <li>
                @lang('Welcome'): <a class="username" href="{{ URL::to('user/profile') }}"> {{ ucwords(Auth::user()->username) }}</a>
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
                    <a rel="alternate" title="Language" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
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
                    <a rel="alternate" title="Language" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, $newUrl, [], true) }}">
                    {{ $properties['native'] }}
                    </a>
                </li>
            @else
                <li>
                    <a rel="alternate" title="Language" style="text-decoration: line-through;" hreflang="{{ $localeCode }}">
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
                <a href="{{ URL::to($tmenu->path) }}" title="{{ $tmenu->title }}"><i class="fas {{ $classNames[$tmenu->tnid]}} fa-lg icons"></i>{{ $tmenu->title }}</a>
            </li>
            @endforeach
            @if (Auth::check())
            <li>
                <a href="{{ URL::to('logout') }}" title="@lang('Log Out')"><i class="fas fa-sign-in-alt fa-lg icons"></i>@lang('Log Out')</a>     
            </li>
            @if (isAdmin())
            <li>
                <a href="{{ URL::to('/admin') }}" title="@lang('Admin Panel')"><i class="fas fa-user fa-lg icons"></i>@lang('Admin Panel')</a>
            </li>
            @endif
            @else
            <li>
                <a href="{{ URL::to('/login') }}" title="@lang('Sign In')"><i class="fas fa-sign-in-alt fa-lg icons"></i>@lang('Sign In')</a>
            </li>
            <li>
                <a href="{{ URL::to('/register') }}" title="@lang('Register')"><i class="fas fa-save fa-lg icons"></i>@lang('Register')</a>
            </li>
            @endif
        </ul>
    </nav>
</header>