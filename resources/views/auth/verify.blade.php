@extends('layouts.main')

@section('content')
<section class="ddl-forms login">
    <div class="content-body">
        <div class="form-item"><h3>{{ __('Verify Your Email Address') }}</h3></div>

        <div class="form-item">
            @if (session('resent'))
                <div style="color: #39af53; font-weight: bold;">
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
            @endif

            {{ __('Before proceeding, please check your email for a verification link.') }}
            {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.
        </div>
    </div>
</section>
@endsection
