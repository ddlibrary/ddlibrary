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
                        <div class="form-group">
                            <input id="user-field"
                                   class="form-control"
                                   name="user-field"
                                   autocomplete="username"
                                   spellcheck="false"
                                   placeholder="@lang('Email or username or phone')"
                                   size="40" type="text"
                                   value="{{ old('user-field') }}"
                                   required
                                   autofocus
                            >
                        </div>
                        <div class="form-group">
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
                        @include('layouts.messages')
                        <div class="form-group">
                            <label id="remember-me">
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember me') }}
                            </label>
                            <input class="btn btn-primary btn-md btn-block" type="submit" value="@lang('Log in')">
                        </div>

                        <a href="#" class="btn btn-outline-secondary btn-md" type="submit">
                            <i class="fab fa-google"></i>
                            <span class="oauth-icon-separator"></span>
                            @lang('Log in with Google')
                        </a>
                        <div class="d-xl-none"><br></div>
                        <a href="#" class="btn btn-outline-secondary btn-md float-xl-right" type="submit">
                            <i class="fab fa-facebook-f"></i>
                            <span class="oauth-icon-separator"></span>
                            @lang('Log in with Facebook')
                        </a>

                        <div class="form-group" style="margin-top: 20px;">
                            <a href="{{ route('register') }}" style="margin-right: 25px;">@lang('Sign up')</a>
                            <a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
