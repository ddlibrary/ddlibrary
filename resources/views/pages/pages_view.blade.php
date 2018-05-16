@extends('layouts.main')
@section('content')
<section class="generalContent">
    <header>
        <h1>{{ $page->title }}</h1>
    </header>
    <article>
        {!! fixImage($page->body,"content-images") !!}
    </article>
</section>
@endsection 