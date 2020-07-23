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
<section class="ddl-forms register-form">
    <header>
        <h1>@lang('Register an account')</h1>
    </header>
    <div class="content-body">
        @include('layouts.messages')
        <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-item">
            <label for="email">
                <strong>@lang('Email')</strong>
                <span class="form-required" id="email-asterisk" title="This field is required." {{ $errors->has('phone')? 'style=display:none;' : '' }}>*</span>
                <span id="email-preferred" style="display:none;">(preferred)</span>
            </label>
            <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email" type="email" value="{{ old('email') }}" {{ $errors->has('phone')? '' : 'required' }} autofocus>
            @if ($errors->has('email'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('email') }}</strong>
                </span><br>
            @endif
            <div class="description">
                @lang(
                    'Your email will be treated as confidential information and will be <br>used to
                     reset your password and communicate to you. <br>If you do not own an email address,
                     <a href="' . $gmail_signup_url . '" target="_blank">click here</a> to create one.'
                )
                <br>
                <span id="phone-text" {{ $errors->has('phone')? 'style=display:none;' : '' }}>
                    @lang(
                        'Or, <span class="open-phone" id="phone-field" onclick="showDiv()">
                         click here</span> to use your phone number to register.'
                    )
                </span>
            </div>
        </div>
        <div class="form-item" id="phone-block" {{ $errors->has('phone')? '' : 'style=display:none;' }}>
            <label for="phone">
                <strong>@lang('Telephone number')</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <input class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" id="phone" name="phone" value="{{ old('phone') }}" type="tel" style="width: 327px;">
            @if ($errors->has('phone'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('phone') }}</strong>
                </span>
            @endif
            <div class="description">
                @lang(
                    'Your telephone number will also be treated as confidential information. <br>
                     But please note that you won\'t be able to reset your password and <br>
                     we won\'t be able to communicate to you. If you forget your password, <br>
                     you will have to contact us.'
                )
            </div>
        </div>
        <div class="form-item">
            <label for="username"> 
                <strong>@lang('Username')</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <input class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" id="username" name="username" value="{{ old('username') }}" type="text" required>
            @if ($errors->has('username'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('username') }}</strong>
                </span>
            @endif
            <div class="description">
                @lang('Spaces are allowed; punctuation is not allowed except for periods, <br> hyphens, apostrophes, and underscores.')
            </div>
        </div>
        <div class="personal-information">
            <div class="right-side">
                <div class="form-item">
                    <label for="password">
                        <strong>@lang('Password')</strong>
                        <span class="form-required" title="This field is required.">*</span>
                    </label>
                    <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" name="password" type="password" required>
                    @if ($errors->has('password'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                    <div class="description">
                        @lang('Choose a strong password with a minimum of eight characters, <br>combining at least one special character (!@#$%^&.) and a digit (0-9).')
                    </div>
                </div>
            </div>
            <div class="left-side">
                <div class="form-item">
                    <label for="password_confirmation">
                        <strong>@lang('Confirm password')</strong>
                        <span class="form-required" title="This field is required.">*</span>
                    </label>
                    <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" required>
                </div>
            </div>
            <div class="right-side">
                <div class="form-item">
                    <label for="first_name"> 
                        <strong>@lang('First name')</strong>
                        <span class="form-required" title="This field is required.">*</span>
                    </label>
                    <input class="form-control" id="first_name" name="first_name"  value="{{ old('first_name') }}" type="text" required>
                </div>
            </div>
            <div class="left-side">
                <div class="form-item">
                    <label for="last_name"> 
                        <strong>@lang('Last name')</strong>
                        <span class="form-required" title="This field is required.">*</span>
                    </label>
                    <input class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" id="last_name" name="last_name"  value="{{ old('last_name') }}" type="text" required>
                </div>
            </div>
            <div class="right-side">
                <div class="form-item">
                    <label for="gender">
                        <strong>@lang('Gender')</strong>
                        <span class="form-required" title="This field is required.">*</span>
                    </label>
                    <select class="form-control{{ $errors->has('gender') ? ' is-invalid' : '' }}" name="gender" id="gender" required>
                        <option value="">- @lang('None') -</option>
                        <option value="Male" {{ old('gender') == "Male" ? "selected" : "" }}>@lang('Male')</option>
                        <option value="Female" {{ old('gender') == "Female" ? "selected" : "" }}>@lang('Female')</option>
                        <option value="None" {{ old('gender') == "None" ? "selected" : "" }}>@lang('Prefer not to say')</option>
                    </select>
                </div>
            </div>
            <div class="left-side"></div>
            <div class="right-side">
                <div class="form-item">
                    <label for="country"> 
                        <strong>@lang('Country')</strong>
                        <span class="form-required" title="This field is required.">*</span>
                    </label>
                    <select class="form-control{{ $errors->has('country') ? ' is-invalid' : '' }}" name="country" id="country" onchange="javascript:populate(this,'city', {{ json_encode($provinces) }})" required>
                        <option value="">- @lang('None') -</option>
                        @foreach($countries AS $cn)
                        <option value="{{ $cn->tnid }}" {{ old('country') == $cn->tnid ? "selected" : "" }}>{{ $cn->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="left-side">
                <div class="form-item">
                    <label for="city"> 
                        <strong>@lang('City')</strong>
                    </label>
                    <select class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" id="city">
                        <option value="">- @lang('None') -</option>
                    </select>
                    <input type="text" class="form-control" name="city_other" id="js-text-city" style="display:none;">
                </div>
            </div>
            @if(env('CAPTCHA') == 'yes')
            <div class="left-side">
                <div class="form-item">
                    {!! NoCaptcha::display() !!}
                </div>
            </div>
            @endif
            <div class="left-side">
                <input class="form-control submit-button btn btn-primary" type="submit" value="@lang('Submit')">
            </div>
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
