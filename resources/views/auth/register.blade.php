@extends('layouts.main')

@section('content')
<section class="ddl-forms">
    <header>
        <h1>Register an Account with DDL</h1>
    </header>
    <div class="content-body">
        <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-item">
            <label for="email"> 
                <strong>Email</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email" size="40" maxlength="40" type="email" value="{{ old('email') }}" required autofocus>
            @if ($errors->has('email'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('email') }}</strong>
                </span><br>
            @endif
            <div class="description">
                    Please enter a valid e-mail address. All e-mails from the system will be sent to this address. The e-mail address is not made public and will only be used if you wish to receive a new password or if you agree to receive certain news or notifications by e-mail. If you do not have an email address, please enter this one: library@darakhtdanesh.org. If you get an email address later, you can edit your profile and replace this email with your own email address
            </div>
        </div>
        <div class="form-item">
            <label for="username"> 
                <strong>Username</strong>
                <span class="form-required" title="This field is required.">*</span>
            </label>
            <input class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" id="username" name="username" value="{{ old('username') }}" size="40" maxlength="40" type="text" required>
            <div class="description">
                Spaces are allowed; punctuation is not allowed except for periods, hyphens, apostrophes, and underscores.
            </div>
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
        <div class="personal-information">
            <div class="right-side">
                <div class="form-item">
                    <label for="first_name"> 
                        <strong>First Name</strong>
                        <span class="form-required" title="This field is required.">*</span>
                    </label>
                    <input class="form-control" id="first_name" name="first_name"  value="{{ old('first_name') }}" size="40" maxlength="40" type="text" required>
                </div>
            </div>
            <div class="left-side">
                <div class="form-item">
                    <label for="last_name"> 
                        <strong>Last Name</strong>
                        <span class="form-required" title="This field is required.">*</span>
                    </label>
                    <input class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" id="last_name" name="last_name"  value="{{ old('last_name') }}" size="40" maxlength="40" type="text" required>
                </div>
            </div>
            <div class="right-side">
                <div class="form-item">
                    <label for="age"> 
                        <strong>Age</strong>
                    </label>
                    <input class="form-control{{ $errors->has('age') ? ' is-invalid' : '' }}" id="age" name="age"  value="{{ old('age') }}" size="40" maxlength="40" type="number" style="width: 327px;" required>
                    @if ($errors->has('age'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('age') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="left-side">
                <div class="form-item">
                    <label for="gender"> 
                        <strong>Gender</strong>
                        <span class="form-required" title="This field is required.">*</span>
                    </label>
                    <select class="form-control{{ $errors->has('gender') ? ' is-invalid' : '' }}" name="gender" id="gender" required>
                        <option value="">- None -</option>
                        <option value="Male" {{ old('gender') == "Male" ? "selected" : "" }}>Male</option>
                        <option value="Female" {{ old('gender') == "Female" ? "selected" : "" }}>Female</option>
                    </select>
                </div>
            </div>
            <div class="right-side">
                <div class="form-item">
                    <label for="country"> 
                        <strong>Country</strong>
                        <span class="form-required" title="This field is required.">*</span>
                    </label>
                    <select class="form-control{{ $errors->has('country') ? ' is-invalid' : '' }}" name="country" id="country" onchange="javascript:populate(this,'city', {{ json_encode($provinces) }})" required>
                        <option value="">- None -</option>
                        @foreach($countries AS $cn)
                        <option value="{{ $cn->tid }}" {{ old('country') == $cn->name ? "selected" : "" }}>{{ $cn->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="left-side">
                <div class="form-item">
                    <label for="city"> 
                        <strong>Province</strong>
                    </label>
                    <select class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" id="city" required>
                        <option value="">- None -</option>
                    </select>
                    <input type="text" class="form-control" name="city" id="js-text-city" size="40" maxlength="40" style="display:none;">
                </div>
            </div>
            <div class="left-side">
                <input class="form-control" type="submit" value="Submit">
            </div>
        </div>
        </form>
    </div>
</section>
@endsection
