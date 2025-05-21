@extends('layouts.main')
@section('title')
{{ trim(strip_tags($page->title)) }}
@endsection
@section('description')
{{ trim(strip_tags(fixImage($page->summary, $page->id))) }}
@endsection
@section('page_image')
{{ asset('storage/files/logo-dd.png') }}
@endsection
@section('content')
<div class="container my-3" style="background-color: #ffffff;">
    <h2 class="pt-3">{{ $page->title }}</h2>
    <article>
        {!! fixImage($page->body, $page->id) !!}
    </article>
    @if (isAdmin())
    <a href="{{ URL::to('page/edit/'.$page->id) }}">Edit</a>
    <a href="{{ URL::to('page/translate/'.$page->id.'/'.$page->tnid) }}">Translate</a>
    @endif
</div>
@endsection 