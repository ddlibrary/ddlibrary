<header class="header">
    <div class="ddlLogo">
        <a href="{{ URL::to('/') }}"><img class="headerImg" src="{{ asset('storage/files/logo-dd.png') }}"></a>
    </div>
    <nav class="headerRight">
        <ul class="languageContent">

            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            <?php
            if(isset($translations)){
                $id=0;
                foreach($translations AS $tr){
                    if($tr->language == $localeCode){
                        $id = $tr->id;
                    }
                }
            }
            ?>
            @if(isset($id) && !empty($id))
            <?php 
                $currentUrl = explode('/',url()->current());
                $index = count($currentUrl) - 1;
                $value = $currentUrl[$index];
                $currentUrl[$index] = $id;
                $newUrl = implode($currentUrl, '/');
            ?>
                <li>
                    <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, $newUrl, [], true) }}">
                    {{ $properties['native'] }}
                    </a>
                </li>
            @else
                <li>
                    <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                    {{ $properties['native'] }}
                    </a>
                </li>
            @endif
            @endforeach
        </ul>
        <ul class="mainNavigation">
            <li>
                <a href="{{ URL::to('/') }}"><i class="fas fa-home fa-lg icons"></i>Home</a>
            </li>
            <li>
                <a href="{{ URL::to('resources') }}"><i class="fas fa-align-justify fa-lg icons"></i>Browse</a>
            </li>
            <li class="dropDown">
                <a href="#"><i class="fas fa-upload fa-lg icons"></i>Upload A Resource</a>
            </li>
            @if (Auth::check())
            <li>
                <a href="{{ URL::to('logout') }}" ><i class="fas fa-sign-in-alt fa-lg icons"></i>Log Out</a>     
            </li>
            <li>
                <a href="#"> {{ Auth::user()->username }}</a>
            </li>
            @if (isAdmin())
            <li>
                <a href="{{ URL::to('/admin') }}"><i class="fas fa-user fa-lg icons"></i>Admin Panel</a>
            </li>
            @endif
            @else
            <li>
                <a href="{{ URL::to('/login') }}"><i class="fas fa-sign-in-alt fa-lg icons"></i>Sign In</a>
            </li>
            <li>
                <a href="{{ URL::to('/register') }}"><i class="fas fa-save fa-lg icons"></i>Register</a>
            </li>
            @endif
        </ul>
    </nav>
</header>