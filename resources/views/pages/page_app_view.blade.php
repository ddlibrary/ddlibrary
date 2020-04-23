@extends('layouts.main')
@section('title')
{{ trim(strip_tags($page->title)) }}
@endsection
@section('description')
{{ trim(strip_tags(fixImage($page->summary, $page->id))) }}kk
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
        {!! fixImage($page->body, $page->id) !!}
    </article>
</section>
<style>
    .header{
        display:none;
    }
</style>

<script>
    $('document').ready(function(){
        $('footer').remove();
        $('.hr-class').remove();
    });
</script>
@endsection 