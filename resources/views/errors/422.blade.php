@extends('layouts.main')
@section('content')
    <section class="generalContent">
        <header>
            <h2>{{ __('422 Unprocessable Entity') }}</h2>
        </header>
        <article>
            <strong>
                {{  $exception->getMessage() }}
                {{ __('Please <a href="'.URL::to('contact-us').'">contact us</a> for more information.') }}
            </strong>
        </article>
    </section>
@endsection
