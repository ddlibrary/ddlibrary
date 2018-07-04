@extends('layouts.main')
@section('search')
    @include('layouts.search')
@endsection
@section('content')
<section class="main-section">
    <div class="ddl-top-news">
        <div>
            <p>Help us get Afghan teacher colleges online: For a limited time your donation will be matched! <a href="https://www.crowdrise.com/o/en/campaign/helping-female-student-teachers-to-be-better-teachers-through-internet-access">Learn More</a></p>
        </div>
    </div>
    <header>
        <h2>Browse by Subject</h2>
    </header>
    <hr>
    <div class="section-content">
        @foreach($subjectAreas as $subject)
        <article class="home-subject-areas" onclick="location.href='{{ URL::to('resources/list?=&subject_area[]='.$subject->subject_area) }}'">
            <img src="{{ Storage::disk('public')->url($subject->file_name) }}">
            <p>{{ $subject->name }}</p>
            <p class="resource-count">{{ $subject->total }} Resources</p>
        </article>
        @endforeach
    </div>
</section>
<section class="main-section">
    <header>
        <h2>Browse by Collection</h2>
    </header>
    <hr>
    <div class="section-content">
        @foreach($featured AS $item)
        <?php
        if($item->url){
            $url = URL::to($item->url);
        }elseif($item->type_id){
            $url = URL::to('resources/list?type[]='.$item->type_id);
        }elseif($item->subject_id){
            $url = URL::to('resources/list?subject_area[]='.$item->subject_id);  
        }elseif($item->level_id){
            $url = URL::to('resources/list?level[]='.$item->level_id);    
        }else{
            $url = URL::to('/');
        }
        ?>
        <article class="home-subject-areas" onclick="location.href='{{ URL::to($url) }}'">
            <img src="{{ Storage::disk('public')->url($item->icon) }}">
            <p>{{ $item->name }}</p>
        </article>
        @endforeach
    </div>
</section>
<section class="latest-news">
    <div class="latest-div">
        <header>
            <h2>Latest News</h2>
        </header>
        <hr>
        @foreach($latestNews AS $news)
        <article class="latest-content">
            <a href="{{ URL::to('news/view/'.$news->newsid) }}"><p>{{ $news->title }}</p></a>
            <i class="news-description">{{ \Carbon\Carbon::parse(Carbon\Carbon::createFromTimestamp($news->created))->format('F dS, Y') }}</i>
        </article>
        @endforeach
    </div>
    <div class="latest-div">
        <header>
            <h2>Latest Resources</h2>
        </header>
        <hr>
        @foreach($latestResources AS $resource)
        <article class="latest-content">
            <a href="{{ URL::to('resources/view/'.$resource->resourceid) }}"><p>{{ $resource->title }}</p></a>
            <i class="news-description">{{ \Carbon\Carbon::parse(Carbon\Carbon::createFromTimestamp($resource->created))->format('F dS, Y') }}</i>
        </article>
        @endforeach
    </div>
    <div class="useful-links">
        <header>
            <h2>Useful Links</h2>
        </header>
        <hr>
        <nav class="latest-content">
            <ul>
                <li>
                    <a href="{{ URL::to('pages/view/16') }}">About DD Library</a>
                </li>
                <li>
                    <a href="{{ URL::to('pages/view/16') }}">How to use the Library</a>
                </li>
                <li>
                    <a href="{{ URL::to('pages/view/21') }}">Support the Library</a>
                </li>
                <li>
                    <a href="{{ URL::to('pages/view/35') }}">Disclaimer</a>
                </li>
                <li>
                    <a href="{{ URL::to('pages/view/33') }}">Terms of Use</a>
                </li>
                <li>
                    <a href="{{ URL::to('pages/view/34') }}">Privacy Policy</a>
                </li>
                <li>
                    <a href="{{ URL::to('pages/view/812') }}">Links</a>
                </li>
            </ul>
        </nav>
    </div>
</section>
@endsection
