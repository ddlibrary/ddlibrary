@extends('layouts.main')
@section('title')
{{ trim(strip_tags($news->title)) }}
@endsection
@section('description')
{{ trim(strip_tags(fixImage($news->summary, $news->id))) }}
@endsection
@section('page_image')
{{ asset('storage/files/logo-dd.png') }}
@endsection
@section('content')
<section class="general-content">
    @include('layouts.messages')
    <header>
        <h1>{{ $news->title }}</h1>
    </header>
    <article>
        {!! fixImage($news->body, $news->id) !!}
    </article>
    @if (isAdmin())
    <a href="{{ URL::to('news/edit/'.$news->id) }}">Edit</a>
    <a href="{{ URL::to('news/translate/'.$news->id.'/'.$news->tnid) }}">Translate</a>
    @endif
</section>
@endsection 