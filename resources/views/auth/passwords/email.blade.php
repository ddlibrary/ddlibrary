@extends('layouts.main')
@if (config('settings.captcha') == 'yes')
    <script src="https://www.google.com/recaptcha/api.js"></script>
@endif
@section('title')
    {{ __('Reset your password') }} - {{ __('Darakht-e Danesh Library') }}
@endsection
@section('content')
    <section class="ddl-forms">
        <header>
            <h3>{{ __('Reset your password') }}</h3>
        </header>
        <div class="content-body">
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                @include('layouts.messages')

                <form method="POST" action="{{ route('password.email') }}" id="reset-password-form">
                    @honeypot
                    @csrf

                    <div class="form-item">
                        <label for="email"
                            class="col-md-4 col-form-label text-md-right">{{ __('Your email address') }}</label>

                        <div class="col-md-8">
                            <input id="email" placeholder="{{ __('Please enter your email') }}" type="email"
                                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" style="width: 377px;"
                                name="email" value="{{ old('email') }}" required>

                        </div>
                    </div>

                    <div class="form-item">
                        <div>
                            @if (config('settings.captcha') == 'yes')
                                <button class="g-recaptcha form-control submit-button btn btn-primary"
                                    data-sitekey="{{ config('services.recaptcha_v3.site_key') }}" data-callback='onSubmit'
                                    data-action='register'>{{ __('Send password reset link') }}</button>
                            @else
                                <button class="form-control submit-button btn btn-primary">{{ __('Send password reset link') }}</button>
                            @endif
                        </div>
                    </div>
                </form>
                <span style="font-size: 0.9rem;">{{ __('If you registered using a phone number, please contact us.') }}</span>
            </div>
        </div>
    </section>
    @push('scripts')
        <script>
            function onSubmit(token) {
                document.getElementById("reset-password-form").submit();
            }
        </script>
    @endpush
@endsection
