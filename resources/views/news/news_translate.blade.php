@extends('layouts.main')
@section('content')
<section class="general-content">
    @include('layouts.messages')
    <header>
        <h1>Available Translations</h1>
    </header>
    <article class="translate">
        <div>
            <span>Title</span>
            <span>Language</span>
            <span>Action</span>
        </div>
        @foreach($news as $news)
                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    <div>
                        @if($localeCode == $news->language)
                            <span>{{ $news->title }}</span>
                            <span>{{ fixLanguage($localeCode) }}</span>
                            <span><a href="{{ URL::to('news/'.$news->id) }}">View</a></span>
                        @else
                            <span>{{ $news->title }}</span>
                            <span>{{ fixLanguage($localeCode) }}</span>
                            <span><a href="{{ URL::to('add/translate/'.$news->tnid) }}">Add</a></span>
                        @endif
                    </div>
                @endforeach
        @endforeach
    </article>
</section>
@endsection 