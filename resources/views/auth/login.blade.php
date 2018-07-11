@extends('layouts.main')

@section('content')
<section class="ddl-forms login">
    <header>
        <h1>Login with your DDL account</h1>
    </header>
    <div class="content-body">
        <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-item">
            <label for="user-field"> 
                <strong>Email or Username</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <input class="form-control{{ $errors->has('user-field') ? ' is-invalid' : '' }}" id="user-field" name="user-field" size="40" maxlength="40" type="text" value="{{ old('user-field') }}" required autofocus>
            @if ($errors->has('user-field'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('user-field') }}</strong>
                </span><br>
            @endif
        </div>

        <div class="form-item">
            <label for="password"> 
                <strong>Password</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" name="password" size="40" maxlength="40" type="password" required>
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
                    <input class="form-control normalButton" type="submit" value="Submit">
                </div>

                <div class="btn-div">
                <a href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
