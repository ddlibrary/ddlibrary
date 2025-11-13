@extends('layouts.main')
@section('title')
    @lang('Register an account') - @lang('Darakht-e Danesh Library')
@endsection
@section('description')
    @lang('The Darakht-e Danesh Online Library for Educators is a repository of open educational resources for teachers, teacher trainers, school administrators, literacy workers and others involved in furthering education in Afghanistan.')
@endsection
@section('page_image')
{{ asset('storage/files/logo-dd.png') }}
@endsection
@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><h4>@lang('Sign up')</h4></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-1"></div>
                            <a href="{{ config('app.google_sso_enabled') ? route('login.google') : 'javascript:void(0)' }}" class="btn btn-primary btn-md col-lg-4 {{ config('app.google_sso_enabled') ? '' : 'disabled' }}" type="submit">
                                <i class="fab fa-google"></i>
                                <span class="oauth-icon-separator"></span>
                                @lang('Sign up with Google')
                            </a>
                            <div class="col-md-2"></div><div class="d-lg-none"><br></div>
                            <a href="{{ config('app.facebook_sso_enabled') ? route('login.facebook') : 'javascript:void(0)' }}" class="btn btn-primary btn-md float-md-right col-lg-4 {{ config('app.facebook_sso_enabled') ? '' : 'disabled' }}" type="submit">
                                <i class="fab fa-facebook-f"></i>
                                <span class="oauth-icon-separator"></span>
                                @lang('Sign up with Facebook')
                            </a>
                            <div class="col-md-1"></div>
                        </div>
                        <hr>
                        <form method="POST" action="{{ route('register') }}" id="register-form">
                            @honeypot
                            @csrf
                            <div class="mb-3">@lang('Or, sign up using your email')</div>
                            <div class="form-group row mb-1">
                                <label for="email" class="col-md-4 col-form-label text-md-right">
                                    @lang('Email')
                                </label>
                                <div class="col-md-6">
                                    <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                           id="email"
                                           name="email"
                                           type="email"
                                           value="{{ old('email') }}"
                                           aria-describedby="emailHelp"
                                           required
                                           autofocus
                                    >
                                    <small id="emailHelp" class="form-text text-muted">
                                        @lang('Your email will be treated as confidential information and will be used to
                                             reset your password and communicate to you. If you do not own an email address,
                                             <a href=":gmail_signup_url" target="_blank">click here</a> to create one.', ['gmail_signup_url' => $gmail_signup_url])
                                    </small>
                                    @if ($errors->has('email'))
                                        @foreach ($errors->get('email') as $message)
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label for="password" class="col-md-4 col-form-label text-md-right">
                                    @lang('Password')
                                </label>
                                <div class="col-md-6">
                                    <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                           id="password"
                                           name="password"
                                           type="password"
                                           aria-describedby="passwordHelp"
                                           required
                                    >
                                    <small id="passwordHelp" class="form-text text-muted">
                                        @lang('Must be 8 characters, with at least 1 special character and 1 digit.')
                                    </small>
                                    @if ($errors->has('password'))
                                        @foreach ($errors->get('password') as $message)
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">
                                    @lang('Password (confirm)')
                                </label>
                                <div class="col-md-6">
                                    <input class="form-control"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           type="password"
                                           required
                                    >
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                    <label for="first_name" class="col-md-4 col-form-label text-md-right">
                                        @lang('First name')
                                    </label>
                                <div class="col-md-6">
                                    <input class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}"
                                           id="first_name"
                                           name="first_name"
                                           value="{{ old('first_name') }}"
                                           type="text"
                                           required
                                    >
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label for="last_name" class="col-md-4 col-form-label text-md-right">
                                    @lang('Last name')
                                </label>
                                <div class="col-md-6">
                                    <input class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}"
                                           id="Last_name"
                                           name="last_name"
                                           value="{{ old('Last_name') }}"
                                           aria-describedby="lastNameHelp"
                                           type="text"
                                    >
                                    <small id="lastNameHelp" class="form-text text-muted">
                                        @lang('Optional')
                                    </small>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="gender" class="col-md-4 col-form-label text-md-right">
                                    @lang('Gender')
                                </label>
                                <div class="col-md-6">
                                    <select class="form-control{{ $errors->has('gender') ? ' is-invalid' : '' }}"
                                            name="gender"
                                            id="gender"
                                            required
                                    >
                                        <option value="">@lang('Select an option')</option>
                                        <option value="Male" {{ old('gender') == "Male" ? "selected" : "" }}>@lang('Male')</option>
                                        <option value="Female" {{ old('gender') == "Female" ? "selected" : "" }}>@lang('Female')</option>
                                        <option value="None" {{ old('gender') == "None" ? "selected" : "" }}>@lang('Prefer not to say')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="country" class="col-md-4 col-form-label text-md-right">
                                    @lang('Country')
                                </label>
                                <div class="col-md-6">
                                    <select class="form-control{{ $errors->has('country') ? ' is-invalid' : '' }}"
                                            name="country"
                                            id="country"
                                            onchange="populate(this,'city', {{ json_encode($provinces) }})"
                                            required
                                    >
                                        <option value="">@lang('Select an option')</option>
                                        @foreach($countries AS $cn)
                                        <option value="{{ $cn->tnid }}" {{ old('country') == $cn->tnid ? "selected" : "" }}>{{ $cn->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="city" class="col-md-4 col-form-label text-md-right">
                                    @lang('City')
                                </label>
                                <div class="col-md-6">
                                    <select class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}"
                                            name="city"
                                            id="city"
                                            aria-describedby="cityHelp"
                                    >
                                        <option value="">@lang('Select an option')</option>
                                    </select>
                                    <small id="cityHelp" class="form-text text-muted">
                                        @lang('Optional')
                                    </small>
                                    <input type="text"
                                           class="form-control"
                                           name="city_other"
                                           id="js-text-city"
                                           style="display:none;"
                                    >
                                </div>
                            </div>
                            @if(config('app.captcha')== 'yes')
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="g-recaptcha btn btn-primary"
                                            data-sitekey="{{ config('services.recaptcha_v3.site_key') }}"
                                            data-callback='onSubmit'
                                            data-action='register'>
                                        @lang('Sign up')
                                    </button>
                                </div>
                            @else
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        @lang('Sign up')
                                    </button>
                                </div>
                            @endif
                        </form>
                    </div>
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
                document.getElementById("register-form").submit();
            }
        </script>
    @endpush
@endsection
