@extends('layouts.main')
<script src="https://www.google.com/recaptcha/api.js"></script>
@section('title')
    {{ __('Subscribe') }} - {{ __('Darakht-e Danesh Library') }}
@endsection
@section('description')
    {{ __('The Darakht-e Danesh Online Library for Educators is a repository of open educational resources for teachers, teacher trainers, school administrators, literacy workers and others involved in furthering education in Afghanistan.') }}
@endsection
@section('page_image')
    {{ asset('storage/files/logo-dd.png') }}
@endsection
@section('content')

    <div class="display-flex justify-content-center">
        <div>
            <header>
                <h2 class="text-center">{{ __('Subscribe to our newsletter') }}</h2>
                <p class="text-center">{{ __("You'll receive our monthly newsletter with our latest updates, highlights, top resources, educational content and other relevant news.") }}</p>
            </header>
            <section class="p-8 d-block ddl-forms register-form">
                <div>
                    <div>
                        <form method="POST" action="{{ route('subscribe.store') }}" id="subscribe-form">
                            @csrf
                            @honeypot

                            {{-- Name --}}
                            <div class="form-item">
                                <input type="text"
                                    class="form-control w-100 {{ $errors->has('name') ? ' is-invalid' : '' }}" id="name"
                                    name="name" autocomplete="username" spellcheck="false"
                                    placeholder="{{ __('Please enter your name') }}" size="40"
                                    value="{{ auth()->check() ? auth()->user()->username : '' }}" autofocus>
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback text-start">
                                        <span>{{ $errors->first('name') }}</span>
                                    </span>
                                @endif
                            </div>

                            {{-- Email --}}
                            <div class="form-item">
                                <input type="text"
                                    class="form-control w-100 {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                    id="email" name="email" autocomplete="email" spellcheck="false"
                                    placeholder="{{ __('Please enter your email') }}" size="40"
                                    value="{{ auth()->check() ? auth()->user()->email : '' }}" autofocus>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback text-start">
                                        <span>{{ $errors->first('email') }}</span>
                                    </span>
                                @endif
                            </div>

                            {{-- Submit Button --}}
                            <div class="form-item">
                                <input type="submit" value="{{ __('Subscribe') }}"
                                    class="g-recaptcha form-control login-submit btn btn-primary w-100"
                                    data-sitekey="{{ config('services.recaptcha_v3.site_key') }}" data-callback='onSubmit'
                                    data-action='subscribe'>

                            </div>

                            {{-- Mailchimp --}}
                            <small style="color:gray">
                                <p>
                                    {{ __('Your email will be shared with MailChimp.') }}
                                </p>
                                <a href="https://mailchimp.com/legal/">{{ __('Their Privacy Policy') }}</a>
                            </small>
                        </form>
                    </div>

                </div>
            </section>
        </div>
    </div>

    @push('scripts')
        <script>
            function onSubmit(token) {
                document.getElementById("subscribe-form").submit();
            }
        </script>
    @endpush
@endsection
