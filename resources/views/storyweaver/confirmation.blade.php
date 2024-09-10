@extends('layouts.main')
@section('title')
    {{ __('StoryWeaver redirect confirmation') }} - {{ __('Darakht-e Danesh Library') }}
@endsection
@section('description')
    {{ __('The Darakht-e Danesh Online Library for Educators is a repository of open educational resources for teachers, teacher trainers, school administrators, literacy workers and others involved in furthering education in Afghanistan.') }}
@endsection

@section('content')
    <section class="storyweaver-confirm">
        @if (! $email)
        {{ __('You do not have a registered email address. StoryWeaver currently does not support users without an email address. <br>If you\'d like to add your email address to your profile, please <a href=":contact">contact us</a>.', ['contact' => URL::to('contact-us')]) }}
        @else
            <p>
                <strong>{{ __('Once you click \'Confirm\', you\'ll be redirected to our partner\'s external site: <a href="https://ddl.storyweaver.org.in" target="_blank">https://ddl.storyweaver.org.in</a>.') }}</strong>
            </p>
            <p class="disclaimer">
                ({{ __('Your email, name and language preference will be shared with StoryWeaver. Before proceeding, please read StoryWeaver\'s <a href="https://ddl.storyweaver.org.in/privacy_policy" target="_blank">privacy policy</a> and <a href="https://ddl.storyweaver.org.in/terms_and_conditions" target="_blank">terms of use</a>.') }})
            </p> <br>
            <a href="{{ route('storyweaver-auth') }}" class="btn btn-primary">{{ __('Confirm') }}</a>
        @endif
    </section>
@endsection
