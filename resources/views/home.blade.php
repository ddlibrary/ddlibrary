@extends('layouts.main')
@section('title')
@lang('Darakht-e Danish Online Library')
@endsection
@section('description')
@lang('The Darakht-e Danesh Online Library for Educators is a repository of open educational resources for teachers, teacher trainers, school administrators, literacy workers and others involved in furthering education in Afghanistan.')
@endsection
@section('page_image')
{{ asset('storage/files/logo-dd.png') }}
@endsection
@section('search')
    @include('layouts.search')
@endsection
@section('content')
<section class="main-section">
    @include('layouts.messages')
    <div class="ddl-top-news">
        <div>
            <p>@lang('Help us get Afghan teacher colleges online: For a limited time your donation will be matched!') <a href="https://www.crowdrise.com/o/en/campaign/helping-female-student-teachers-to-be-better-teachers-through-internet-access" title="Donate">@lang('Learn More')</a></p>
        </div>
    </div>
    <header>
        <h2>@lang('Explore our subjects')</h2>
    </header>
    <hr>
    <div class="section-content">
        @foreach($subjectAreas as $subject)
        <a href="{{ URL::to('resources/list?=&subject_area[]='.$subject->subject_area) }}" title="{{ $subject->name }}">
            <article class="home-subject-areas">
                <img src="{{ Storage::disk('public')->url($subject->file_name) }}" alt="Subject Area Icon">
                <p>{{ $subject->name }}</p>
                <p class="resource-count">{{ App\Resource::countSubjectAreas($subject->id)->total }} @lang('Resources')</p>
            </article>
        </a>
        @endforeach
    </div>
</section>
<section class="main-section">
    <header>
        <h2>@lang('Featured Resource Collections')</h2>
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
        <a href="{{ URL::to($url) }}" title="{{ $item->name }}">
            <article class="home-subject-areas">
                <i class="{{ $item->icon }} fa-5x" style="color: #ffa300"></i>
                <p>{{ $item->name }}</p>
            </article>
        </a>
        @endforeach
    </div>
</section>
<section class="latest-news">
    <div class="latest-div">
        <header>
            <h2>@lang('Latest News')</h2>
        </header>
        <hr>
        @foreach($latestNews AS $news)
        <article class="latest-content">
            <a href="{{ URL::to('news/'.$news->id) }}" title="{{ $news->title }}"><p>{{ $news->title }}</p></a>
            <i class="news-description">{{ $news->created_at->diffForHumans() }}</i>
        </article>
        @endforeach
    </div>
    <div class="latest-div">
        <header>
            <h2>@lang('Latest Resources')</h2>
        </header>
        <hr>
        @foreach($latestResources AS $resource)
        <article class="latest-content">
            <a href="{{ URL::to('resource/'.$resource->id) }}" title="{{ $resource->title }}"><p>{{ $resource->title }}</p></a>
            <i class="news-description">{{ __($resource->updated_at->diffForHumans())  }}</i>
        </article>
        @endforeach
    </div>
    <div class="useful-links">
        <header>
            <h2>@lang('Useful Links')</h2>
        </header>
        <hr>
        <nav class="latest-content">
            <ul>
                @foreach ($menu->where('location', 'bottom-menu')->where('language', app()->getLocale()) as $bmenu)
                <li>
                    <a href="{{ URL::to($bmenu->path) }}" title="{{ $bmenu->title }}">{{ $bmenu->title }}</a>
                </li>
                @endforeach
            </ul>
        </nav>
    </div>
</section>
@endsection
