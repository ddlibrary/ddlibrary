@extends('layouts.main')
@section('content')
<section class="general-content">
    <header>
        <h1>{{ $news->title }}</h1>
    </header>
    <article>
        {!! fixImage($news->body) !!}
    </article>
</section>
@endsection 