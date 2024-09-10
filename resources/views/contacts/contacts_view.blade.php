@extends('layouts.main')
@if (env('CAPTCHA') == 'yes')
    <script src="https://www.google.com/recaptcha/api.js"></script>
@endif
@section('title')
    {{ __('Contact Us') }} - {{ __('Darakht-e Danesh Online Library') }}
@endsection
@section('description')
    {{ __('The Darakht-e Danesh Online Library for Educators is a repository of open educational resources for teachers, teacher trainers, school administrators, literacy workers and others involved in furthering education in Afghanistan.') }}
@endsection
@section('page_image')
    {{ asset('storage/files/logo-dd.png') }}
@endsection
@section('content')
    <section class="ddl-forms">
        <header>
            <h1>{{ __('Contact Us') }}</h1>
        </header>
        <div class="content-body" style="display: flex;flex-wrap: wrap;">
            <form method="POST" action="{{ route('contact') }}" style="flex: 1;" id="contact-form">
                @honeypot
                @csrf
                @include('layouts.messages')
                <div class="form-item">
                    <label for="name">
                        <strong>{{ __('Full Name') }}</strong>
                        <span class="form-required" title="This field is required.">*</span>
                    </label>
                    <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" id="name" name="name"
                        size="40" type="text" value="{{ old('name', $fullname) }}" required autofocus>
                    @if ($errors->has('name'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span><br>
                    @endif
                </div>
                <div class="form-item">
                    <label for="email">
                        <strong>{{ __('Email') }}</strong>
                        <span class="form-required" title="This field is required.">*</span>
                    </label>
                    <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email"
                        name="email" size="40" type="email" value="{{ old('email', $email) }}" required>
                    @if ($errors->has('email'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span><br>
                    @endif
                </div>
                <div class="form-item">
                    <label for="subject">
                        <strong>{{ __('Subject') }}</strong>
                        <span class="form-required" title="This field is required.">*</span>
                    </label>
                    <input class="form-control{{ $errors->has('subject') ? ' is-invalid' : '' }}" id="subject"
                        name="subject" size="40" type="text" value="{{ old('subject') }}" required>
                    @if ($errors->has('subject'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('subject') }}</strong>
                        </span><br>
                    @endif
                </div>
                <div class="form-item">
                    <label for="message">
                        <strong>{{ __('Message') }}</strong>
                        <span class="form-required" title="This field is required.">*</span>
                    </label>
                    <div id="editor">
                        <textarea class="w-100 form-control{{ $errors->has('message') ? ' is-invalid' : '' }}" name="message"
                            style="height: 200px; width: 350px" required>{{ old('message') }}</textarea>
                        @if ($errors->has('message'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('message') }}</strong>
                            </span><br>
                        @endif
                        @if ($errors->has('g-recaptcha-response'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                            </span><br>
                        @endif
                    </div>
                </div>

                <div class="left-side">
                    <div>
                        @if (env('CAPTCHA') == 'yes')
                            <button class="g-recaptcha form-control login-submit btn btn-primary"
                                data-sitekey="{{ config('services.recaptcha_v3.site_key') }}" data-callback='onSubmit'
                                data-action='register'>{{ __('Send') }}</button>
                        @else
                            <button class="form-control login-submit btn btn-primary">{{ __('Send') }}</button>
                        @endif
                    </div>
                </div>
            </form>
            <div style="flex:2; flex-direction:column;">
                <div class="sidebar" style="padding: 1em;">
                    <h3>{{ __('Want to schedule a demo of the DD Library at your school, college or institution? Send us a request using the contact form on this page.') }}</h3>
                </div>

                <div class="sidebar" style="padding: 1em;">
                    <h2>{{ __('Want to receive our newsletter?') }}</h2>
                    <p>
                        {{ __('About three times a year we send out the DDL newsletter. If you are a registered user of the Library, you will automatically receive the newsletter. If you are not a registered library user but would like to subscribe to our newsletter, please') }}
                        <a href="https://darakhtdanesh.us11.list-manage.com/subscribe?u=abbdaa95e801980b608399770&id=9bf90f679d"
                            target="_blank">click here.</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
    @push('scripts')
        <script>
            function onSubmit(token) {
                document.getElementById("contact-form").submit();
            }
        </script>
    @endpush
@endsection
