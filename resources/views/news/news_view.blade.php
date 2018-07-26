@extends('layouts.main')
@section('content')
<section class="general-content">
    @include('layouts.messages')
    <header>
        <h1>{{ $news->title }}</h1>
    </header>
    <article>
        {!! fixImage($news->body) !!}
    </article>
    @if (isAdmin())
    <a href="{{ URL::to('news/edit/'.$news->id) }}">Edit</a>
    <a href="{{ URL::to('news/translate/'.$news->id) }}">Translate</a>
    @endif
</section>
@endsection 