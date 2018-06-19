@extends('layouts.main')
@section('content')
<section class="general-content">
    <header>
        <h1>{{ $page->title }}</h1>
    </header>
    <article>
        {!! fixImage($page->body,"content-images") !!}
    </article>
</section>
@endsection 