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
            
            <div class="form-item">
                <label for="email"> 
                    <strong>@lang('Email')</strong>
                    <span class="form-required" title="This field is required.">*</span>
                </label>
                <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" size="40" type="text" value="{{ $user->email }}" required>
                @if ($errors->has('email'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span><br>
                @endif
            </div>
            
            <div class="form-item">
                <label for="username"> 
                    <strong>@lang('Username')</strong>
                    <span class="form-required" title="This field is required.">*</span>
                </label>
                <input class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" size="40" type="text" value="{{ $user->username }}" required>
                @if ($errors->has('username'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span><br>
                @endif
            </div>
            
            <div class="form-item">
                <label for="password"> 
                    <strong>@lang('Password')</strong>
                    <span class="form-required" title="This field is required.">*</span>
                </label>
                <input class="form-control" name="password" type="password" size="40" placeholder="@lang("Only fill this if you want to change your password")" autocomplete="off">
            </div>

            <div>
                <span><strong>@lang('Status'):</strong></span>
                <span>{{ ($user->status==0?"Not Active":"Active") }}</span>
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