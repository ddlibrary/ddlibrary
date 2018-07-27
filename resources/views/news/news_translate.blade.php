@extends('layouts.main')
@section('content')
<section class="general-content">
    @include('layouts.messages')
    <header>
        <h1>Available Translations</h1>
    </header>
    <article class="translate">
        <div class="translate-head">
            <span>Title</span>
            <span>Language</span>
            <span>Action</span>
        </div>
        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            <?php $item = $news->firstWhere('language',$localeCode);?>
            @if(count($item))
            <div>
                <span>{{ $item->title }}</span>
                <span>{{ fixLanguage($item->language) }}</span>
                <a href="{{ URL::to($item->language.'/news/'.$item->id) }}">View</a>
            </div>
            @else
                <div>
                    <span>{{ $news_self->title }}</span>
                    <span>{{ fixLanguage($localeCode) }}</span>
                    <span><a href="{{ URL::to($localeCode.'/news/add/translate/'.$news_self->id.'/'.$localeCode) }}">Add</a></span>
                </div>
            @endif
        @endforeach
    </article>
</section>
@endsection 