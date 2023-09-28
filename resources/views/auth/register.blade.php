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
<header>
    <br>
    <h1 class="text-center">@lang('Register an account')</h1>
</header>
    <section class="ddl-forms register-form">
        <div class="content-body">
            @include('layouts.messages')
            <form method="POST" action="{{ route('register') }}">
                @honeypot
                @csrf
                {{-- Login via socialate --}}
                <div class="socialite-container">
                    <div class="socialite justify-content-center">
    
                        {{-- Gmail --}}
                        <a href="{{ env('LOGIN_WITH_GOOGLE') == 'no' ? 'javascript:void(0)' : route('login.google') }}"
                            class="btn btn-outline-secondary btn-md  @if (env('LOGIN_WITH_GOOGLE') == 'no') disabled-link display-none @endif @if (env('LOGIN_WITH_FACEBOOK') == 'no') flex-grow-1 @endif"
                            type="submit">
                            <i class="fab fa-google"></i>
                            <span class="oauth-icon-separator"></span>
                            @lang('Sign Up with Google')
                        </a>
    
                        {{-- Facebook --}}
                        <a href="{{ env('LOGIN_WITH_FACEBOOK') == 'no' ? 'javascript:void(0)' : route('login.facebook') }}"
                            class=" btn btn-outline-secondary btn-md float-xl-right @if (env('LOGIN_WITH_FACEBOOK') == 'no') disabled-link display-none @endif @if (env('LOGIN_WITH_GOOGLE') == 'no') flex-grow-1 @endif"
                            type="submit">
                            <i class="fab fa-facebook-f"></i>
                            <span class="oauth-icon-separator"></span>
                            @lang('Sign Up with Facebook')
                        </a>
                    </div>
                    <div class="form-item">
                        <div class="divider">
                            <span class="divider-inner-text">{{ __('or') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Create account --}}
                <div class="personal-information">

                    {{-- First Name --}}
                    <div class="register-form-item">
                        <div class="form-item">
                            <label for="first_name">
                                <strong>@lang('First name')</strong>
                                <span class="form-required" title="This field is required.">*</span>
                            </label>
                            <input class="form-control  w-100" placeholder="@lang('First name')" id="first_name" name="first_name" value="{{ old('first_name') }}"
                                type="text" required>
                        </div>
                    </div>

                    {{-- Last Name --}}
                    <div class="register-form-item">
                        <div class="form-item">
                            <label for="last_name">
                                <strong>@lang('Last name')</strong>
                                <span class="form-required" title="This field is required.">*</span>
                            </label>
                            <input class="form-control  w-100{{ $errors->has('last_name') ? ' is-invalid' : '' }}" id="last_name"
                                name="last_name" value="{{ old('last_name') }}" type="text" placeholder="@lang('Last name')" required>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="register-form-item">
                        <div class="form-item">
                            <label for="first_name">
                                <strong>@lang('Email')</strong>
                                <span class="form-required" title="This field is required.">*</span>
                            </label>
                            <input class="form-control  w-100{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                placeholder="youemail@email.com" id="email" name="email" type="email"
                                value="{{ old('email') }}" {{ $errors->has('phone') ? '' : 'required' }} autofocus>
                        </div>
                    </div>

                    {{-- Gender --}}
                    <div class="register-form-item">
                        <div class="form-item">
                            <label for="gender">
                                <strong>@lang('Gender')</strong>
                                <span class="form-required" title="This field is required.">*</span>
                            </label>
                            <select class="form-control  w-100{{ $errors->has('gender') ? ' is-invalid' : '' }}" name="gender"
                                id="gender" required>
                                <option value="">- @lang('None') -</option>
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>@lang('Male')
                                </option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>@lang('Female')
                                </option>
                                <option value="None" {{ old('gender') == 'None' ? 'selected' : '' }}>@lang('Prefer not to say')
                                </option>
                            </select>
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="register-form-item">
                        <div class="form-item">
                            <label for="password">
                                <strong>@lang('Password')</strong>
                                <span class="form-required" title="This field is required.">*</span>
                            </label>
                            <div class="position-relative">
                                <input
                                    class="form-control  w-100{{ $errors->has('password') ? ' is-invalid' : '' }}  user-password"
                                    id="password" name="password" type="password" required placeholder="********"
                                    title="@lang('Choose a strong password with a minimum of eight characters, <br>combining at least one special character (!@#$%^&.) and a digit (0-9).')">
                                <span class="fa fa-eye-slash password-toggle-icon text-gray" aria-hidden="true"
                                    onclick="togglePassword()"></span>
                            </div>
                            @if ($errors->has('password'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Confirm Password --}}
                    <div class="register-form-item">
                        <div class="form-item">
                            <label for="password_confirmation">
                                <strong>@lang('Confirm password')</strong>
                                <span class="form-required" title="This field is required.">*</span>
                            </label>
                            <div class="position-relative">
                                <input class="form-control  w-100 confirm-user-password" placeholder="********"
                                    id="password_confirmation" name="password_confirmation" type="password" required>
                                <span class="fa fa-eye-slash confirm-password-toggle-icon text-gray" aria-hidden="true"
                                    onclick="togglePassword('confirm-password-toggle-icon', 'confirm-user-password')"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Country --}}
                    <div class="register-form-item">
                        <div class="form-item">
                            <label for="country">
                                <strong>@lang('Country')</strong>
                                <span class="form-required" title="This field is required.">*</span>
                            </label>
                            <select class="form-control  w-100{{ $errors->has('country') ? ' is-invalid' : '' }}" name="country"
                                id="country" onchange="populate(this,'city', {{ json_encode($provinces) }})" required>
                                <option value="">- @lang('None') -</option>
                                @foreach ($countries as $cn)
                                    <option value="{{ $cn->tnid }}"
                                        {{ old('country') == $cn->tnid ? 'selected' : '' }}>{{ $cn->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- City --}}
                    <div class="register-form-item">
                        <div class="form-item">
                            <label for="city">
                                <strong>@lang('City')</strong>
                            </label>
                            <select class="form-control  w-100{{ $errors->has('city') ? ' is-invalid' : '' }}" name="city"
                                id="city">
                                <option value="">- @lang('None') -</option>
                            </select>
                            <input type="text" class="form-control" name="city_other" id="js-text-city"
                                style="display:none;">
                        </div>
                    </div>
                </div>

                {{-- Google Captcha --}}
                @if (Config::get('captcha.captcha') == 'yes')
                    <div class="register-form-item">
                        <div class="form-item overflow-x">
                            {!! NoCaptcha::display() !!}
                        </div>
                    </div>
                @endif

                {{-- Submit --}}
                <div class="register-form-item register-form-submit-btn">
                    <input class="form-control submit-button btn btn-primary" type="submit" value="@lang('Submit')">
                    <a href="{{ route('login') }}">@lang('Sign in')</a>
                </div>
            </form>
        </div>
    </section>
    @push('scripts')
        <script src="{{ asset('js/ddl.js') }}"></script>
        <script>
            function showDiv() {
                document.getElementById('phone').required = true;
                document.getElementById('email').required = false;
                document.getElementById('email-asterisk').style.display = "none";
                document.getElementById('phone-text').style.display = "none";
                document.getElementById('phone-block').style.display = "block";
                document.getElementById('email-preferred').style.display = "inline";
            }
        </script>
    @endpush
@endsection
