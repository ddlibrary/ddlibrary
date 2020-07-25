@extends('layouts.main')
@section('content')
    <section class="generalContent">
        <header>
            <h2>@lang('422 Unprocessable Entity')</h2>
        </header>
        <article>
            <strong>
                {{  $exception->getMessage() }}
                @lang('Please <a href="'.URL::to('contact-us').'">contact us</a> for more information.')
            </strong>
        </article>
    </section>
@endsection
