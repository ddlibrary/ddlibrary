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
            <a href="{{ URL::to('/') }}" title="{{ __('Home') }}">{{ __('Home') }}</a>
            <a href="{{ URL::to('resources') }}" title="{{ __('Browse') }}">{{ __('Browse') }}</a>
            {{-- The route() landing_page parameter is a key from config/constants.php, and as such, must match with a key to work --}}
            <a href="{{ route ('storyweaver-confirm', ['landing_page' => 'storyweaver_default']) }}" title="{{ __('StoryWeaver') }}">{{ __('StoryWeaver') }}</a>
            <a href="{{ URL::to('resources/add/step1') }}" title="{{ __('Upload a Resource') }}">{{ __('Upload a Resource') }}</a>
            @auth
            <a href="{{ URL::to('logout') }}" title="{{ __('Log Out') }}">{{ __('Log Out') }}</a>
            @if (isAdmin())
            <a href="{{ URL::to('/admin') }}" title="{{ __('Admin Panel') }}">{{ __('Admin Panel') }}</a>
            @endif
            @else
            <a href="{{ URL::to('/login') }}" title="{{ __('Sign In') }}">{{ __('Sign In') }}</a>
            <a href="{{ URL::to('/register') }}" title="{{ __('Register') }}">{{ __('Register') }}</a>
            @endif
        </div>
    </div>
    <nav class="header-right">
        <ul class="language-content">
            @auth
            <li>
                {{ __('Welcome') }}: <a class="username" href="{{ URL::to('user/profile') }}"> {{ ucwords(Auth::user()->username) }}</a>
            </li>
            @endauth
            <?php
            $supportedLocals = [];
            $newId = [];
                foreach(config('laravellocalization.localesOrder') as $localeCode)
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

            @elseif(isset($newId[$localeCode]) && $newId)
                <?php 
                    $currentUrl = explode('/',url()->current());
                    $index = count($currentUrl) - 1;
                    $value = $currentUrl[$index];
                    $currentUrl[$index] = $newId[$localeCode];
                    $newUrl = implode('/', $currentUrl);
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
            $classNames = [
                125  => 'fa-home',
                566  => 'fa-align-justify',
                131  => 'fa-upload'
            ];
            ?>
            @if($menu)
                @foreach ($menu->where('location', 'top-menu')->where('language', app()->getLocale()) as $tmenu)
                    <li>
                        <a href="{{ URL::to($tmenu->path) }}" title="{{ $tmenu->title }}"><i class="fas {{ $classNames[$tmenu->tnid]}} fa-lg icons"></i>{{ $tmenu->title }}</a>
                    </li>
                    @if ($loop->index == 1) {{-- where 0 is Home, 1 is DDL Library. We want it next to DDL Library. --}}
                        <li>
                            <a href="{{ route('storyweaver-confirm', ['landing_page' => 'storyweaver_default']) }}" title="StoryWeaver"><img src="{{ URL::to(config('constants.ddlmain_s3_file_storage_url').'/public/img/storyweaver-logo.svg') }}" class="storyweaver-logo" alt="StoryWeaver logo"> {{ __('StoryWeaver Library') }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
            
            @auth
            <li>
                <a href="{{ URL::to('logout') }}" title="{{ __('Log Out') }}"><i class="fas fa-sign-in-alt fa-lg icons"></i>{{ __('Log Out') }}</a>     
            </li>
            @if (isAdmin())
            <li>
                <a href="{{ URL::to('/admin') }}" title="{{ __('Admin Panel') }}"><i class="fas fa-user fa-lg icons"></i>{{ __('Admin Panel') }}</a>
            </li>
            @endif
            @else
            <li>
                <a href="{{ URL::to('/login') }}" title="{{ __('Sign In') }}"><i class="fas fa-sign-in-alt fa-lg icons"></i>{{ __('Sign In') }}</a>
            </li>
            <li>
                <a href="{{ URL::to('/register') }}" title="{{ __('Register') }}"><i class="fas fa-save fa-lg icons"></i>{{ __('Register') }}</a>
            </li>
            @endif
        </ul>
    </nav>
</header>
