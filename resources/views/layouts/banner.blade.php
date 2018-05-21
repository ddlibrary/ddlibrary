<header class="header">
    <div class="ddlLogo">
        <a href="{{ URL::to('/') }}"><img class="headerImg" src="{{ asset('storage/files/logo-dd.png') }}"></a>
    </div>
    <nav class="headerRight">
        <a href="{{ URL::to('/') }}"><i class="fas fa-home fa-lg icons"></i>Home</a>
        <a href="{{ URL::to('resources') }}"><i class="fas fa-align-justify fa-lg icons"></i>Browse</a>
        <a href="#"><i class="fas fa-language fa-lg icons"></i>Language</a>
        @if (Auth::check())
        <a href="{{ URL::to('logout') }}" ><i class="fas fa-sign-in-alt fa-lg icons"></i>Log Out</a>     
        <a href="#"> {{ Auth::user()->username }}</a>
        @if (isAdmin())
        <a href="{{ URL::to('/admin') }}"><i class="fas fa-user fa-lg icons"></i>Admin Panel</a>
        @endif
        @else
        <a href="{{ URL::to('/login') }}"><i class="fas fa-sign-in-alt fa-lg icons"></i>Sign In</a>
        <a href="{{ URL::to('/register') }}"><i class="fas fa-save fa-lg icons"></i>Register</a>
        @endif
    </nav>
</header>