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
<section class="ddl-forms login">
    <header>
        <h3>@lang('Log in to Darakht-e Danesh Library')</h3>
    </header>
    <div class="content-body">
        @include('layouts.messages')
        <form method="POST" action="{{ route('login') }}">
            @honeypot
            @csrf
            <div class="form-item">
                <input class="form-control{{ $errors->has('user-field') ? ' is-invalid' : '' }}" id="user-field" name="user-field" autocomplete="username" spellcheck="false"  placeholder="@lang('Email or username or phone')" size="40" type="text" value="{{ old('user-field') }}" required autofocus>
                @if ($errors->has('user-field'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('user-field') }}</strong>
                    </span><br>
                @endif
            </div>

            <div class="form-item">
                <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" name="password" autocomplete="current-password" spellcheck="false" placeholder="@lang('Password')" size="40" type="password" required>
                @if ($errors->has('password'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-item">
                <input class="form-control login-submit btn btn-primary" type="submit" value="@lang('Log in')">
            </div>
            <div class="form-item">
                <label id="remember-me">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember me') }}
                </label>
            </div>
            <div class="form-item">
                <div class="btn-div">
                    <a href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                    <span aria-hidden="true">&bull;</span>
                    <a href="{{ route('register') }}">
                        @lang('Register an account')
                    </a>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
