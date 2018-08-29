@extends('layouts.main')
@section('title')
{{ trim(strip_tags($page->title)) }}
@endsection
@section('description')
{{ trim(strip_tags(fixImage($page->summary))) }}
@endsection
@section('page_image')
{{ asset('storage/files/logo-dd.png') }}
@endsection
@section('content')
<section class="general-content">
    <header>
        <h1>{{ $page->title }}</h1>
    </header>
    <article>
        {!! fixImage($page->body) !!}
    </article>
    @if (isAdmin())
    <a href="{{ URL::to('page/edit/'.$page->id) }}">Edit</a>
    <a href="{{ URL::to('page/translate/'.$page->id.'/'.$page->tnid) }}">Translate</a>
    @endif
</section>
@endsection 