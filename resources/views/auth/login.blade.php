@extends('layouts.main')
@if (config('settings.captcha') == 'yes')
    <script src="https://www.google.com/recaptcha/api.js"></script>
@endif
@section('title')
    {{ __('Log in to Darakht-e Danesh Library') }} - {{ __('Darakht-e Danesh Library') }}
@endsection
@section('description')
    {{ __('The Darakht-e Danesh Online Library for Educators is a repository of open educational resources for teachers, teacher trainers, school administrators, literacy workers and others involved in furthering education in Afghanistan.') }}
@endsection
@section('page_image')
    {{ asset('storage/files/logo-dd.png') }}
@endsection
@section('content')
    <div class="display-flex justify-content-center">
        <section class="p-8 d-block ddl-forms register-form">
            <div>
                <header>
                    <h3 class="text-center">{{ __('Log in to Darakht-e Danesh Library') }}</h3>
                </header>
                <div>
                    <form method="POST" action="{{ route('login') }}" id="login-form">
                        @honeypot
                        @csrf

                        {{-- Username --}}
                        <div class="form-item">
                            <input type="text" class="form-control w-100 {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                id="email" name="email" autocomplete="username" spellcheck="false"
                                placeholder="{{ __('Email or username or phone') }}" size="40" value="{{ old('email') }}" required
                                autofocus>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback text-start">
                                    <span>{{ $errors->first('email') }}</span>
                                </span>
                            @endif
                        </div>

                        {{-- Password --}}
                        <div class="form-item position-relative">
                            <input type="password"
                                class="form-control w-100 {{ $errors->has('password') ? ' is-invalid' : '' }} user-password"
                                id="password" name="password" autocomplete="current-password" spellcheck="false"
                                placeholder="{{ __('Password') }}" size="40" required>
                            <span class="fa fa-eye-slash password-toggle-icon text-gray" aria-hidden="true"
                                onclick="togglePassword()"></span>
                            @if ($errors->has('password'))
                                <span class="invalid-feedback text-start">
                                    <span>{{ $errors->first('password') }}</span>
                                </span>
                            @endif
                        </div>

                        {{-- Remember Me --}}
                        <div class="form-item text-start">
                            <label id="remember-me">
                                <input type="checkbox" name="remember" class="display-inline-block submit-button"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <span class="">{{ __('Remember Me') }}</span>
                            </label>
                        </div>

                        {{-- Submit Button --}}
                        <div class="form-item">

                            <div>
                                @if (config('settings.captcha') == 'yes')
                                    <button class="g-recaptcha form-control login-submit btn btn-primary w-100"
                                        data-sitekey="{{ config('services.recaptcha_v3.site_key') }}"
                                        data-callback='onSubmit' data-action='register'>{{ __('Log in') }}</button>
                                @else
                                    <button
                                        class="form-control login-submit btn btn-primary w-100">{{ __('Log in') }}</button>
                                @endif
                                @if ($errors->has('g-recaptcha-response'))
                                    <div>
                                        <span class="invalid-feedback text-start">
                                            <span>{{ $errors->first('g-recaptcha-response') }}</span>
                                        </span>
                                    </div>
                                @endif

                            </div>
                        </div>

                        <div class="form-item">
                            <div class="divider">
                                <span class="divider-inner-text">{{ __('or') }}</span>
                            </div>
                        </div>

                        {{-- Login via socialate --}}
                        <div class="socialite">

                            {{-- Gmail --}}
                            <a href="{{ config('settings.login_with_google') == 'no' ? 'javascript:void(0)' : route('login.google') }}"
                                class="btn btn-outline-secondary btn-md  @if (config('settings.login_with_google') == 'no') disabled-link display-none @endif @if (config('settings.login_with_facebook') == 'no') flex-grow-1 @endif"
                                type="submit">
                                <i class="fab fa-google"></i>
                                <span class="oauth-icon-separator"></span>
                                {{ __('Log in with Google') }}
                            </a>

                            {{-- Facebook --}}
                            <a href="{{ config('settings.login_with_facebook') == 'no' ? 'javascript:void(0)' : route('login.facebook') }}"
                                class=" btn btn-outline-secondary btn-md float-xl-right @if (config('settings.login_with_facebook') == 'no') disabled-link display-none @endif @if (config('settings.login_with_google') == 'no') flex-grow-1 @endif"
                                type="submit">
                                <i class="fab fa-facebook-f"></i>
                                <span class="oauth-icon-separator"></span>
                                {{ __('Log in with Facebook') }}
                            </a>
                        </div>

                        <div class="form-group text-start" style="margin-top: 20px;">

                            {{-- Sign up link --}}
                            <a href="{{ route('register') }}" style="margin-inline-end: 25px;">{{ __('Sign up') }}</a>

                            {{-- Forgot password --}}
                            <a href="{{ route('password.request') }}"
                                @@disabled(true)>{{ __('Forgot your password?') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
    @push('scripts')
        <script>
            function onSubmit(token) {
                let form = document.getElementById("login-form");
                
                if (form.checkValidity()) {
                    form.submit();
                } else {
                    grecaptcha.reset();
                    form.reportValidity();
                }
            }
        </script>
    @endpush
@endsection
