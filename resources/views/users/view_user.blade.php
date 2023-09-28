@extends('layouts.main')
@section('content')
    <section class="general-content">
        <header>
            <h1>@lang('Users Details for') <strong>{{ $user->username }}</strong></h1>
        </header>
        <article>
            @include('users.user_nav')

            <form method="POST" action="{{ route('user-profile-update') }}" autocomplete="off">
                @csrf
                @if (Session::has('success'))
                    <div
                        style="background: #dff0d8;border:1px solid #d6e9c6;color:#3c763d; border-radius:5px;padding:15px;margin: 10px 0px;">
                        {{ Session::get('success') }}
                    </div>
                @elseif(Session::has('warning'))
                    <div
                        style="background: f2dede;color:#a94442; border-radius:5px;padding:15px;border:1px solid #ebccd1;margin:10px 0px">
                        {{ Session::get('warning') }}
                    </div>
                @endif
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

                        <input class="form-control user-password {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"
                            type="password" size="38" placeholder="@lang('Only fill this if you want to change your password')" autocomplete="off">
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
                    <input class="form-control  confirm-user-password {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
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

                <div>
                    <span><strong>@lang('Status'):</strong></span>
                    <span>{{ $user->status == 0 ? 'Not Active' : 'Active' }}</span>
                </div>
                <div>
                    <span><strong>@lang('Created'):</strong></span>
                    <span>{{ $user->created_at }}</span>
                </div>
                <div>
                    <span><strong>@lang('Access'):</strong></span>
                    <span>{{ $user->accessed_at }}</span>
                </div> <br>

                <div class="left-side">
                    <input class="form-control normalButton" type="submit" value="@lang('Update')">
                </div>

            </form>
        </article>
    </section>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
@endsection
