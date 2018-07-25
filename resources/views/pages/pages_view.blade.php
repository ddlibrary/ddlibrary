@extends('layouts.main')
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
    @endif
</section>
@endsection 