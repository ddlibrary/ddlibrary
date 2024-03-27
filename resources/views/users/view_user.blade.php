@extends('layouts.main')
@section('content')
    <section class="general-content">
        
        <header>
            <h1>@lang('Users Details for') <strong>{{ $user->username }}</strong></h1>
        </header>
        <article>

            @include('users.user_nav')
            <div class="border-yellow p-5 border-radius-5">

                <form method="POST" action="{{ route('user-profile-update') }}" autocomplete="off">
                    @csrf
                    <div class="form-item">
                        <label for="email">
                            <strong>@lang('Email')</strong>
                            <span class="form-required" title="This field is required.">*</span>
                        </label>
                        <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                            size="40" type="text" value="{{ $user->email }}" required>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>

                    @csrf

                    <div class="form-item">
                        <label for="username">
                            <strong>@lang('Username')</strong>
                            <span class="form-required" title="This field is required.">*</span>
                        </label>
                        <input class="form-control {{ $errors->has('username') ? ' is-invalid' : '' }}" name="username"
                            size="40" type="text" value="{{ $user->username }}" required>
                        @if ($errors->has('username'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('username') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-item">
                        <label for="password">
                            <strong>@lang('Password')</strong>
                            <span class="form-required" title="This field is required."></span>
                        </label>
                        <div class="position-relative d-inline-block">

                            <input class="form-control user-password {{ $errors->has('password') ? ' is-invalid' : '' }}"
                                name="password" type="password" size="38" placeholder="@lang('Only fill this if you want to change your password')"
                                autocomplete="off">
                            <span class="fa fa-eye-slash password-toggle-icon text-gray" aria-hidden="true"
                                onclick="togglePassword()"></span>
                        </div>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-item">
                        <label for="confirm">
                            <strong>@lang('Confirm')</strong>
                            <span class="form-required" title="This field is required."></span>
                        </label>
                        <div class="position-relative d-inline-block">
                            <input
                                class="form-control  confirm-user-password {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
                                name="password_confirmation" type="password" size="38" placeholder="@lang('Only fill this if you want to change your password')"
                                autocomplete="off">
                            <span class="fa fa-eye-slash confirm-password-toggle-icon" aria-hidden="true"
                                onclick="togglePassword('confirm-password-toggle-icon', 'confirm-user-password')"></span>
                        </div>
                        @if ($errors->has('password_confirmation'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="display-flex flex-direction-column gap-5">

                        <div class="display-flex gap-5">
                            <div class="flex-1">
                                <strong>@lang('Status'):</strong>
                            </div>
                            <div class="flex-5">
                                
                                @if ($user->status == 1)
                                    <span class="user-state bg-lightseagreen">Active
                                        <span class="fa fa-check-circle"></span></span>
                                @endif
                            </div>
                        </div>
                        <div class="display-flex gap-5">
                            <div class="flex-1">
                                <strong>@lang('Created'):</strong>
                            </div>
                            <div class="flex-5">
                                {{ $user->created_at }}
                            </div>
                        </div>

                        <div class="display-flex gap-5">
                            <div class="flex-1">
                                <strong>@lang('Access'):</strong>
                            </div>
                            <div class="flex-5">
                                {{ $user->accessed_at }}
                            </div>
                        </div>
                    </div>

                    <div class="left-side mt-5">
                        <input class="form-control normalButton" type="submit" value="@lang('Update')">
                    </div>

                </form>
            </div>
        </article>
    </section>
@endsection
