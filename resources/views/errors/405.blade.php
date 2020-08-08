@extends('layouts.main')
@section('content')
    <section class="generalContent">
        <header>
            <h2>@lang('405 Not Allowed')</h2>
        </header>
        <article>
            <strong>{{  $exception->getMessage() }}</strong>
        </article>
    </section>
@endsection
