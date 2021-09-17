@extends('layouts.main')
{!! NoCaptcha::renderJs() !!}
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
                            <a href="#" class="btn btn-primary btn-md col-lg-4" type="submit">
                                <i class="fab fa-google"></i>
                                <span class="oauth-icon-separator"></span>
                                @lang('Sign up with Google')
                            </a>
                            <div class="col-md-2"></div><div class="d-lg-none"><br></div>
                            <a href="#" class="btn btn-primary btn-md float-md-right col-lg-4" type="submit">
                                <i class="fab fa-facebook-f"></i>
                                <span class="oauth-icon-separator"></span>
                                @lang('Sign up with Facebook')
                            </a>
                            <div class="col-md-1"></div>
                        </div>
                        <hr>
                        <form method="POST" action="{{ route('register') }}">
                            @honeypot
                            @csrf
                            <div class="mb-3">@lang('Or sign up using your email or phone.')</div>
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">
                                    @lang('Email')
                                    <span id="email-preferred" style="display:none;">(preferred)</span>
                                </label>
                                <div class="col-md-6">
                                    <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                           id="email"
                                           name="email"
                                           type="email"
                                           value="{{ old('email') }}"
                                           aria-describedby="emailHelp"
                                           {{ $errors->has('phone')? '' : 'required' }}
                                           autofocus
                                    >
                                    <small id="emailHelp" class="form-text text-muted">
                                        @lang('Your email will be treated as confidential information and will be used to
                                             reset your password and communicate to you. If you do not own an email address,
                                             <a href=":gmail_signup_url" target="_blank">click here</a> to create one.', ['gmail_signup_url' => $gmail_signup_url])
                                        <span id="phone-text" {{ $errors->has('phone')? 'style=display:none;' : '' }}>
                                        @lang(
                                            'Or, <span class="open-phone" id="phone-field" onclick="showDiv()">
                                             click here</span> to use your phone number to register.'
                                        )
                                        </span>
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
                            <div class="form-group row" id="phone-block" {{ $errors->has('phone')? '' : 'style=display:none;' }}>
                                <label for="phone" class="col-md-4 col-form-label text-md-right">
                                    @lang('Phone')
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">+93</div>
                                        </div>
                                        <input class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                               id="phone"
                                               name="phone"
                                               value="{{ old('phone') }}"
                                               placeholder="@lang('For Afghan users only')"
                                               aria-describedby="phoneHelp"
                                               type="text"
                                        >
                                    </div>
                                    <small id="phoneHelp" class="form-text text-muted">
                                        @lang(
                                            "Your telephone number will also be treated as confidential information.
                                             But please note that you won't be able to reset your password and
                                             we won't be able to communicate to you. If you forget your password,
                                             you will have to contact us."
                                        )
                                    </small>
                                    @if ($errors->has('phone'))
                                        @foreach ($errors->get('phone') as $message)
                                            <span class="text-danger">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="username" class="col-md-4 col-form-label text-md-right">
                                    @lang('Username')
                                </label>
                                <div class="col-md-6">
                                    <input class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}"
                                           id="username"
                                           name="username"
                                           value="{{ old('username') }}"
                                           aria-describedby="usernameHelp"
                                           type="text"
                                           required
                                    >
                                    <small id="usernameHelp" class="form-text text-muted">
                                        @lang('Spaces are allowed; punctuation is not allowed except for periods, hyphens, apostrophes, and underscores.')
                                    </small>
                                    @if ($errors->has('username'))
                                        @foreach ($errors->get('username') as $message)
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
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
                            <div class="form-group row">
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
                            <div class="form-group row">
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
                            <div class="form-group row">
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
                            <div class="form-group row">
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
                            <div class="form-group row">
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
                            <div class="form-group row">
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
                            @if(Config::get('captcha.captcha') == 'yes')
                                <div class="col-md-6 offset-md-4 mb-3">
                                    {!! NoCaptcha::display() !!}
                                </div>
                            @endif
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    @lang('Sign up')
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
    <script src="{{ asset('js/ddl.js') }}"></script>
    <script>
        function showDiv() {
            document.getElementById('phone').required = true;
            document.getElementById('email').required = false;
            document.getElementById('phone-text').style.display = "none";
            document.getElementById('phone-block').style.display = "flex";
            document.getElementById('email-preferred').style.display = "inline";
        }
    </script>
@endpush
@endsection
