@extends('layouts.main')
@section('search')
    @include('layouts.search')
@endsection
@section('content')
<section class="subjects">
    <header>
        <h2>Browse by Subjects</h2>
    </header>
    <hr>
    <div class="sectionContent">
        @foreach($subjectAreas as $subject)
        <article class="homeSubjectAreas" onclick="location.href='{{ URL::to('resources/list?subject_area[]='.$subject->subject_area) }}'">
            <img src="{{ Storage::disk('public')->url($subject->file_name) }}">
            <p>{{ $subject->name }}</p>
            <p>{{ $subject->total }} Resources</p>
        </article>
        @endforeach
    </div>
</section>
<section class="collections">
    <header>
        <h2>Browse by Collections</h2>
    </header>
    <hr>
    <div class="sectionContent">
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
        <article class="homeSubjectAreas" onclick="location.href='{{ URL::to($url) }}'">
            <img src="{{ Storage::disk('public')->url($item->icon) }}">
            <p>{{ $item->name }}</p>
        </article>
        @endforeach
    </div>
</section>
<section class="latestNews">
    <div class="latestNewsDiv">
        <header>
            <h2>Latest News</h2>
        </header>
        <hr>
        @foreach($latestNews AS $news)
        <article class="latestNewsContent">
            <a href="{{ URL::to('news/view/'.$news->tnid) }}"><p>{{ $news->title }}</p></a>
            <i class="newsDescription">{{ \Carbon\Carbon::parse(Carbon\Carbon::createFromTimestamp($news->created))->format('F dS, Y') }}</i>
        </article>
        @endforeach
    </div>
    <div class="ddlVideo">
        <header>
            <h2>DDL Video</h2>
        </header>
        <hr>
        <article class="ddlVideoContent">
            <iframe width="560" height="315" src="https://www.youtube.com/embed/bF5dpED9W64" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        </article>
    </div>
    <div class="ddlVideo">
        <header>
            <h2>CW4WAfghan Video</h2>
        </header>
        <hr>
        <article class="ddlVideoContent">
            <iframe width="560" height="315" src="https://www.youtube.com/embed/Kl37icKnzd4" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        </article>
    </div>
</section>
@endsection
