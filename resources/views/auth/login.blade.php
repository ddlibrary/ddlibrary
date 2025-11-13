@extends('layouts.main')
@section('title')
    @lang('Log in to Darakht-e Danesh Library') - @lang('Darakht-e Danesh Library')
@endsection
@section('description')
    @lang('The Darakht-e Danesh Online Library for Educators is a repository of open educational resources for teachers, teacher trainers, school administrators, literacy workers and others involved in furthering education in Afghanistan.')
@endsection
@section('page_image')
    {{ asset('storage/files/logo-dd.png') }}
@endsection
@section('content')
    <div class="container">
        <div id="login-row" class="row justify-content-center align-items-center">
            <div id="login-column" class="col-md-6">
                <div id="login-box" class="col-md-12">
                    <form method="POST" id="login-form" action="{{ route('login') }}">
                        @honeypot
                        @csrf
                        <h3 class="text-center">@lang('Log in')</h3>
                        <div class="form-group mb-2">
                            <input id="email"
                                   class="form-control"
                                   name="email"
                                   autocomplete="username"
                                   spellcheck="false"
                                   placeholder="@lang('Email')"
                                   size="40"
                                   type="email"
                                   value="{{ old('email') }}"
                                   required
                                   autofocus
                            >
                        </div>
                        <div class="form-group mb-3">
                            <input id="password"
                                   class="form-control"
                                   name="password"
                                   autocomplete="current-password"
                                   spellcheck="false"
                                   placeholder="@lang('Password')"
                                   size="40"
                                   type="password"
                                   required
                            >
                        </div>
                        @if ($errors->has('email'))
                            <span class="text-danger bg-danger-subtle p-2 rounded">
                                    <span>{{ $errors->first('email') }}</span>
                            </span><br>
                        @endif
                        <div class="form-group mb-4 mt-2">
                            <label id="remember-me">
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember me') }}
                            </label>
                            @if (config('app.captcha') == 'yes')
                                <input class="g-recaptcha btn btn-primary btn-md btn-block col-12 mt-2"
                                       type="submit"
                                       data-sitekey="{{ config('services.recaptcha_v3.site_key') }}"
                                       data-callback='onSubmit'
                                       data-action='register'
                                       value="@lang('Log in')"
                                >
                            @else
                                <input class="btn btn-primary btn-md col-12 mt-2"
                                       type="submit"
                                       value="@lang('Log in')"
                                >
                            @endif
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <a href="{{ config('app.google_sso_enabled') ? route('login.google') : 'javascript:void(0)' }}"
                               class="btn btn-outline-secondary btn-md {{ config('app.google_sso_enabled') ? '' : 'disabled' }}"
                            >
                                <i class="fab fa-google"></i>
                                <span class="oauth-icon-separator"></span>
                                @lang('Log in with Google')
                            </a>
                            <div class="d-xl-none"><br></div>
                            <a href="{{ config('app.facebook_sso_enabled') ? route('login.facebook') : 'javascript:void(0)' }}"
                               class="btn btn-outline-secondary btn-md {{ config('app.facebook_sso_enabled') ? '' : 'disabled' }}"
                            >
                                <i class="fab fa-facebook-f"></i>
                                <span class="oauth-icon-separator"></span>
                                @lang('Log in with Facebook')
                            </a>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
                            <a href="{{ route('register') }}">@lang('Sign up')</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        @if (config('app.captcha') == 'yes')
            <script src="https://www.google.com/recaptcha/api.js"></script>
        @endif
        <script>
            function onSubmit(token) {
                document.getElementById("login-form").submit();
            }
        </script>
    @endpush
@endsection
