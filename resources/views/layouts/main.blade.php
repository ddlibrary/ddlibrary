<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
<head>
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description')">
    <!-- Twitter Card data -->
    <meta name="twitter:card" value="@yield('description')">

    <!-- Open Graph data -->
    <meta property="og:title" content="@yield('title')" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="{{ URL::current() }}" />
    <meta property="og:image" content="@yield('page_image')" />
    <meta property="og:description" content="@yield('description')" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('storage/files/favicon.ico') }}">

    @googlefonts

    @if (LaravelLocalization::getCurrentLocaleDirection() == 'ltr')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    @else
        <link rel="stylesheet" href="https://cdn.rtlcss.com/bootstrap/v4.5.3/css/bootstrap.min.css" integrity="sha384-JvExCACAZcHNJEc7156QaHXTnQL3hQBixvj5RV5buE7vgnNEzzskDtx9NQ4p6BJe" crossorigin="anonymous">
    @endif

    <link rel="stylesheet" href="{{ asset('css/all.css') }}">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    @if(Lang::locale() != 'en')
    <link rel="stylesheet" href="{{ asset('css/local.css') }}">
    @endif

    @stack('styles')
    @if(App::environment('production'))
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-6207513-43"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-6207513-43');
    </script>
    @endif

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>

</head>
<body>
    <div id="page-container">
        <div id="content-wrap">
            <!-- Facebook Chat Integration - Start -->
            <!-- Load Facebook SDK for JavaScript -->
            <div id="fb-root"></div>
            <script>
                window.fbAsyncInit = function() {
                    FB.init({
                    xfbml            : true,
                    version          : 'v3.2'
                    });
                };

                (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
                fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));
            </script>

            <!-- Your customer chat code -->
            <div class="fb-customerchat"
                attribution=setup_tool
                page_id="1540661852875335"
                theme_color="#ffa800"
                logged_in_greeting="به کتاب‌خانه درخت دانش خوش آمدید. چگونه می‌توانیم به شما کمک کنیم؟"
                logged_out_greeting="به کتاب‌خانه درخت دانش خوش آمدید. چگونه می‌توانیم به شما کمک کنیم؟">
            </div>
            <!-- Facebook Chat Integration - End -->
            @include('layouts.banner')
            @yield('search')
            <main>

                <?php
                $lang = Config::get('app.locale');
                $questions_count = \App\SurveyQuestion::getPublishedQuestions($lang)->count();
                ?>
                @if ($questions_count != 0)
                    @if (Request::is(Lang::locale().'/home'))
                        @include('../survey/survey_view')
                    @elseif (Request::is(Lang::locale()))
                        @include('../survey/survey_view')
                    @elseif (Request::is(Lang::locale().'/resource/*'))
                        @include('../survey/survey_view')
                    @elseif (Request::is(Lang::locale().'/resources/*'))
                        @include('../survey/survey_view')
                    @endif
                @endif

                @yield('content')

            </main>
        </div>
        @include('layouts.footer')
        <!-- Optional JavaScript -->
        <script async src="{{ asset('js/all.js') }}"></script>
        @if (LaravelLocalization::getCurrentLocaleDirection() == 'ltr')
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
        @else
            <script src="https://cdn.rtlcss.com/bootstrap/v4.5.3/js/bootstrap.bundle.min.js" integrity="sha384-40ix5a3dj6/qaC7tfz0Yr+p9fqWLzzAXiwxVLt9dw7UjQzGYw6rWRhFAnRapuQyK" crossorigin="anonymous"></script>
        @endif
        @stack('scripts')
    </div>
</body>
</html>
