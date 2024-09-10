@extends('layouts.main')
@section('title')
    {{ __('Darakht-e Danesh Online Library') }}
@endsection
@section('description')
    {{ __('The Darakht-e Danesh Online Library for Educators is a repository of open educational resources for teachers, teacher trainers, school administrators, literacy workers and others involved in furthering education in Afghanistan.') }}
@endsection
@section('page_image')
    {{ asset('storage/files/logo-dd.png') }}
@endsection
@section('search')
    @include('layouts.search')
@endsection
@section('content')
    <section class="main-section">
        <div class="ddl-top-news border-radius-top-5">
            <div>
                <p>{{ __("Access children's storybooks through Storyweaver, click here") }}:
                    <a href="{{ route('storyweaver-confirm', ['landing_page' => 'storyweaver_default']) }}"
                        title="StoryWeaver">
                        <img src="{{ URL::to(config('constants.ddlmain_s3_file_storage_url') . '/public/img/storyweaver-logo.svg') }}"
                            class="storyweaver-logo">
                        {{ __('StoryWeaver Library') }}
                    </a>
                </p>
            </div>
        </div>
        <header class="home-header border-b-lightgray ">
            <h2>{{ __('Explore our subjects') }}</h2>
        </header>
        <div class="section-content">
            @foreach ($subjectAreas as $subject)
                <a href="{{ URL::to('resources/list?=&subject_area[]=' . $subject->subject_area) }}"
                    title="{{ $subject->name }}">
                    <article class="home-subject-areas">
                        <img src="{{ Storage::disk('public')->url($subject->file_name) }}" alt="Subject Area Icon">
                        <p>{{ $subject->name }}</p>
                        <p class="resource-count">{{ App\Models\Resource::countSubjectAreas($subject->id)->total }}
                            {{ __('Resources') }}</p>
                    </article>
                </a>
            @endforeach
        </div>
    </section>
    <section class="main-section mt-2">

        <header class="border-radius-top-5 border-b-lightgray home-header">
            <h2>{{ __('Featured Resource Collections') }}</h2>
        </header>
        <div class="section-content">
            @foreach ($featured as $item)
                <?php
                if ($item->url) {
                    $url = URL::to($item->url);
                } elseif ($item->type_id) {
                    $url = URL::to('resources/list?type=' . $item->type_id);
                } elseif ($item->subject_id) {
                    $url = URL::to('resources/list?subject_area=' . $item->subject_id);
                } elseif ($item->level_id) {
                    $url = URL::to('resources/list?level=' . $item->level_id);
                } else {
                    $url = URL::to('/');
                }
                ?>
                <a href="{{ URL::to($url) }}" title="{{ $item->name }}">
                    <article class="home-subject-areas">
                        <i class="{{ $item->icon }} fa-4x" style="color: #ffa300"></i>
                        <p>{{ $item->name }}</p>
                    </article>
                </a>
            @endforeach
            <a href="{{ URL::to('glossary') }}" title="Glossary">
                <article class="home-subject-areas">
                    <i class="fa fa-search fa-4x" style="color: #ffa300"></i>
                    <p>{{ __('DDL Glossary') }}</p>
                </article>
            </a>

            <?php
            $covid_url = 'page/4137';
            if (Lang::locale() == 'fa') {
                $covid_url = 'page/4133';
            } elseif (Lang::locale() == 'ps') {
                $covid_url = 'page/4134';
            } elseif (Lang::locale() == 'pa') {
                $covid_url = 'page/4135';
            } elseif (Lang::locale() == 'uz') {
                $covid_url = 'page/4136';
            }
            ?>
            <a href="{{ URL::to($covid_url) }}" title="Covid-19">
                <article class="home-subject-areas">
                    <img src="{{ Storage::disk('public')->url('covid19.png') }}" alt="COVID19 Icon" style="height:52px">
                    <p>{{ __('COVID19') }}</p>
                </article>
            </a>
            @php
                $newcomers_support_url = 'page/4141';
            @endphp
            <a href="{{ URL::to($newcomers_support_url) }}" title="{{ __('Newcomers support URL') }}">
                <article class="home-subject-areas">
                    <i class="fas fa-hands-helping fa-4x" style="color: #ffa300"></i>
                    <p>{{ __('Resources For Afghan Newcomers') }}</p>
                </article>
            </a>
        </div>
    </section>
    <section class="main-section mt-2">

        <header class="border-radius-top-5 border-b-lightgray home-header">
            <h2>{{ __('StoryWeaver Library') }}</h2>
        </header>
        <div class="storyweaver-homepage">
            <h3>{{ __('Explore the StoryWeaver Collections') }}</h3>
        </div>
        <div class="section-content">
            {{-- The route() landing_page parameters are keys from config/constants.php, and as such, must match with the keys to work --}}
            <a href="{{ route('storyweaver-confirm', ['landing_page' => 'storyweaver_family_and_friends']) }}"
                title="Family & Friends">
                <article class="home-subject-areas">
                    <img src="{{ Storage::disk('public')->url('StoryWeaver family and friends.svg') }}"
                        alt="Family & Friends collection">
                    <p>{{ __('Family & Friends') }}</p>
                </article>
            </a>
            <a href="{{ route('storyweaver-confirm', ['landing_page' => 'storyweaver_growing_up']) }}" title="Growing Up">
                <article class="home-subject-areas">
                    <img src="{{ Storage::disk('public')->url('StoryWeaver growing up.svg') }}"
                        alt="Growing Up collection">
                    <p>{{ __('Growing Up') }}</p>
                </article>
            </a>
            <a href="{{ route('storyweaver-confirm', ['landing_page' => 'storyweaver_funny']) }}" title="Funny">
                <article class="home-subject-areas">
                    <img src="{{ Storage::disk('public')->url('StoryWeaver funny.svg') }}" alt="Funny collection">
                    <p>{{ __('Funny') }}</p>
                </article>
            </a>
            <a href="{{ route('storyweaver-confirm', ['landing_page' => 'storyweaver_stem']) }}" title="STEM">
                <article class="home-subject-areas">
                    <img src="{{ Storage::disk('public')->url('StoryWeaver STEM.svg') }}" alt="STEM collection">
                    <p>{{ __('STEM') }}</p>
                </article>
            </a>
        </div>
        <div class="storyweaver-homepage">
            <h3>{{ __('Translate English Storybooks to Afghan languages') }}</h3>
        </div>

        <div class="section-content">
            <a href="{{ route('storyweaver-confirm', ['landing_page' => 'storyweaver_pashto']) }}" title="Pashto">
                <article class="home-subject-areas">
                    <img src="{{ Storage::disk('public')->url('StoryWeaver pashto.svg') }}" alt="Pashto translation">
                    <p>{{ __('Pashto') }}</p>
                </article>
            </a>
            <a href="{{ route('storyweaver-confirm', ['landing_page' => 'storyweaver_dari']) }}" title="Dari/Farsi">
                <article class="home-subject-areas">
                    <img src="{{ Storage::disk('public')->url('StoryWeaver dari.svg') }}" alt="Dari/Farsi translation">
                    <p>{{ __('Dari/Farsi') }}</p>
                </article>
            </a>
        </div>
    </section>
    <section class="main-section mt-2">
        <header class="border-radius-top-5 border-b-lightgray home-header">
            <h2>{{ __('Quickstart videos') }}</h2>
        </header>
        <br>
        <div class="section-content" style="display: flex;">
            <div style="flex:1">
                <header class="padding-b">
                    <h4>{{ __('Watch a video to learn more about our work in Afghanistan') }}</h4>
                </header>
                <div style="margin: 20px;" class="thumbnail mt-2">
                    <iframe width="100%" height="315" class="border-radius-5" src="https://www.youtube.com/embed/bF5dpED9W64" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
                </div>
            </div>

            <div style="flex:1">
                <header class="padding-b">
                    <h4>{{ __('How to Use the Darakht-e Danesh Library') }}</h4>
                </header>
                <div style="margin: 20px;" class="thumbnail mt-2">
                    <iframe width="100%" height="315" class="border-radius-5"
                        src="https://www.youtube.com/embed/{{ Lang::locale() == 'en' ? '-PgQmUX2vbs' : (Lang::locale() == 'ps' ? 'EhoGbreiCjo' : '-JM5lzeDWrE') }}"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </section>
    <section class="latest-news">
        <div class="latest-div">
            <header class="border-b-lightgray">
                <h2>{{ __('Latest News') }}</h2>
            </header>
            @foreach ($latestNews as $news)
                <article class="latest-content">
                    <a href="{{ URL::to('news/' . $news->id) }}" title="{{ $news->title }}">
                        <p>{{ $news->title }}</p>
                    </a>
                    <i class="time">{{ $news->created_at->diffForHumans() }}</i>
                </article>
            @endforeach
        </div>
        <div class="latest-div">
            <header class="border-b-lightgray">
                <h2>{{ __('Latest Resources') }}</h2>
            </header>
            @foreach ($latestResources as $resource)
                <article class="latest-content">
                    <a href="{{ URL::to('resource/' . $resource->id) }}" title="{{ $resource->title }}">
                        <p>{{ $resource->title }}</p>
                    </a>
                    <i class="time">{{ __($resource->updated_at->diffForHumans()) }}</i>
                </article>
            @endforeach
        </div>
        <div class="useful-links">
            <header class="border-b-lightgray">
                <h2>{{ __('Useful Links') }}</h2>
            </header>
            <nav class="latest-content">
                <ul>
                    @if ($menu)
                        @foreach ($menu->where('location', 'bottom-menu')->where('status', 1)->where('language', app()->getLocale()) as $bmenu)
                            <li>
                                <a href="{{ URL::to($bmenu->path) }}"
                                    title="{{ $bmenu->title }}">{{ $bmenu->title }}</a>
                            </li>
                        @endforeach
                    @endif
                    <li>
                        <a href="{{ url('subscribe') }}" class="btn btn-outline-secondary btn-md float-xl-right"
                            title="{{ __('Subscribe') }}">{{ __('Subscribe') }}</a>
                    </li>
                </ul>
            </nav>
        </div>
    </section>

@endsection
