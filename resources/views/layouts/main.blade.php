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

    <link rel="shortcut icon" href="{{ asset('storage/files/favicon.ico') }}">

    <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ddl.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fontawesome-all.min.css') }}">
    @stack('styles')

    <script>
        console.log('{{ App::environment("production") }}');
    </script>
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

</head>
<body>
    @include('layouts.banner')
    @yield('search')
    <main>
        @yield('content')
    </main>
    @include('layouts.footer')
    <!-- Optional JavaScript -->
    @stack('scripts')
    <script async src="{{ asset('js/ddl.js') }}"></script>
</body>
</html>