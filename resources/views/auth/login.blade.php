@extends('layouts.main')
@section('title')
@lang('Login with your DDL account') - @lang('Darakht-e Danish Online Library')
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
        <h1>@lang('Login with your DDL account')</h1>
    </header>
    <div class="content-body">
        <form method="POST" action="{{ route('login') }}">
        @if ($errors->any())
            <ul class="form-required">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </ul>
        @endif
        @csrf
        <div class="form-item">
            <label for="user-field"> 
                <strong>@lang('Email or Username')</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <input class="form-control{{ $errors->has('user-field') ? ' is-invalid' : '' }}" id="user-field" name="user-field" size="40" type="text" value="{{ old('user-field') }}" required autofocus>
            @if ($errors->has('user-field'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('user-field') }}</strong>
                </span><br>
            @endif
        </div>

        <div class="form-item">
            <label for="password"> 
                <strong>@lang('Password')</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" name="password" size="40" type="password" required>
            @if ($errors->has('password'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-item">
            <label>
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }}
            </label>
        </div>

        <div class="form-item">
                <div class="left-side">
                    <input class="form-control normalButton" type="submit" value="@lang('Submit')">
                </div>

                <div class="btn-div">
                    <a href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                </div>
                <div class="btn-div">
                    <a href="{{ route('register') }}">
                        @lang('Register an Account with DDL')
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
