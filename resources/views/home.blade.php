@extends('layouts.main')
@section('title')
    @lang('Darakht-e Danesh Online Library')
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
    <div class="container pt-2" id="homepage-main-container">
        <h4 class="@if(LaravelLocalization::getCurrentLocaleDirection() == 'ltr') main-heading @else main-heading-rtl @endif p-2">
            @lang('Explore our subjects')
        </h4>
        <div class="row justify-content-center my-4">
            @foreach($subjectAreas as $subject)
                <a href="{{ URL::to('resources/list?=&subject_area[]='.$subject->subject_area) }}"
                   title="{{ $subject->name }}"
                   class="col-6 col-sm-4 col-lg-2 text-center"
                >
                    <div class="home-subject-areas">
                        <img src="{{ Storage::disk('public')->url($subject->file_name) }}" alt="Subject Area Icon">
                        <p>{{ $subject->name }}</p>
                        <p class="resource-count">{{ App\Models\Resource::countSubjectAreas($subject->id)->total }}
                            @lang('Resources')</p>
                    </div>
                </a>
            @endforeach
        </div>
        <h4 class="@if(LaravelLocalization::getCurrentLocaleDirection() == 'ltr') main-heading @else main-heading-rtl @endif p-2">
            @lang('Featured resource collections')
        </h4>
        <div class="row justify-content-center my-4">
            @foreach ($featured as $item)
                <?php
                if (isset($item)) {
                    if($item->url){
                        $url = URL::to($item->url);
                    }elseif($item->type_id){
                        $url = URL::to('resources/list?type='.$item->type_id);
                    }elseif($item->subject_id){
                        $url = URL::to('resources/list?subject_area='.$item->subject_id);
                    }elseif($item->level_id){
                        $url = URL::to('resources/list?level='.$item->level_id);
                    }else{
                        $url = URL::to('/');
                    }
                }
                ?>
                <a href="{{ URL::to($url) }}"
                   title="{{ $item->name }}"
                   class="col-6 col-sm-4 col-lg-2 text-center"
                >
                    <div class="home-subject-areas">
                        <i class="{{ $item->icon }} fa-4x" style="color: #ffa300"></i>
                        <p>{{ $item->name }}</p>
                    </div>
                </a>
            @endforeach
            <a href="{{ URL::to('glossary') }}"
               title="Glossary"
               class="col-6 col-sm-4 col-lg-2 text-center"
            >
                <div class="home-subject-areas">
                    <i class="fa fa-globe fa-4x" style="color: #ffa300"></i>
                    <p>@lang('Glossary')</p>
                </div>
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
            <a href="{{ URL::to($covid_url) }}"
               title="COVID-19"
               class="col-6 col-sm-4 col-lg-2 text-center"
            >
                <div class="home-subject-areas">
                    <i class="fas fa-first-aid fa-4x" style="color: #ffa300"></i>
                    <p>@lang('COVID-19')</p>
                </div>
            </a>
            @php
                $newcomers_support_url = 'page/4141';
            @endphp
            <a href="{{ URL::to($newcomers_support_url) }}"
               title="Newcomers support URL"
               class="col-6 col-sm-4 col-lg-2 text-center"
            >
                <div class="home-subject-areas">
                    <i class="fas fa-hands-helping fa-4x" style="color: #ffa300"></i>
                    <p>@lang('Resources For Afghan Newcomers')</p>
                </div>
            </a>
        </div>
        <h4 class="@if(LaravelLocalization::getCurrentLocaleDirection() == 'ltr') main-heading @else main-heading-rtl @endif p-2">
            @lang('StoryWeaver Library')
        </h4>
        <div class="sub-heading">
            <h6>@lang('Explore the StoryWeaver collections')</h6>
        </div>

        <div class="row justify-content-center my-4">
            {{-- The route() landing_page parameters are keys from config/constants.php, and as such, must match with the keys to work --}}
            <a href="{{ route('storyweaver-confirm', ['landing_page' => 'storyweaver_family_and_friends']) }}"
               title="Family & friends"
               class="col-6 col-sm-4 col-lg-2 text-center"
            >
                <div class="home-subject-areas">
                    <img src="{{ Storage::disk('public')->url('StoryWeaver family and friends.svg') }}" alt="Family & friends collection">
                    <p>@lang('Family & Friends')</p>
                </div>
            </a>
            <a href="{{ route('storyweaver-confirm', ['landing_page' => 'storyweaver_growing_up']) }}"
               title="Growing up"
               class="col-6 col-sm-4 col-lg-2 text-center"
            >
                <div class="home-subject-areas">
                    <img src="{{ Storage::disk('public')->url('StoryWeaver growing up.svg') }}" alt="Growing up collection">
                    <p>@lang('Growing Up')</p>
                </div>
            </a>
            <a href="{{ route('storyweaver-confirm', ['landing_page' => 'storyweaver_funny']) }}"
               title="Funny"
               class="col-6 col-sm-4 col-lg-2 text-center"
            >
                <div class="home-subject-areas">
                    <img src="{{ Storage::disk('public')->url('StoryWeaver funny.svg') }}" alt="Funny collection">
                    <p>@lang('Funny')</p>
                </div>
            </a>
            <a href="{{ route('storyweaver-confirm', ['landing_page' => 'storyweaver_stem']) }}"
               title="STEM"
               class="col-6 col-sm-4 col-lg-2 text-center"
            >
                <div class="home-subject-areas">
                    <img src="{{ Storage::disk('public')->url('StoryWeaver STEM.svg') }}" alt="STEM collection">
                    <p>@lang('STEM')</p>
                </div>
            </a>
        </div>
        <div class="sub-heading">
            <h6>@lang('Translate English Storybooks to Afghan languages')</h6>
        </div>

        <div class="row justify-content-center">
            <a href="{{ route('storyweaver-confirm', ['landing_page' => 'storyweaver_pashto']) }}"
               title="Pashto"
               class="col-6 col-sm-4 col-lg-2 text-center"
            >
                <div class="home-subject-areas">
                    <img src="{{ Storage::disk('public')->url('StoryWeaver pashto.svg') }}" alt="Pashto translation">
                    <p>@lang('Pashto')</p>
                </div>
            </a>
            <a href="{{ route('storyweaver-confirm', ['landing_page' => 'storyweaver_dari']) }}"
               title="Dari/Farsi"
               class="col-6 col-sm-4 col-lg-2 text-center"
            >
                <div class="home-subject-areas">
                    <img src="{{ Storage::disk('public')->url('StoryWeaver dari.svg') }}" alt="Dari/Farsi translation" width="65" height="54">
                    <p>@lang('Dari/Farsi')</p>
                </div>
            </a>
        </div>
        <h4 class="@if(LaravelLocalization::getCurrentLocaleDirection() == 'ltr') main-heading @else main-heading-rtl @endif p-2">
            @lang('Quickstart videos')
        </h4>
        <div class="row mt-4 pb-4">
            <div class="col-md-6">
                <div class="sub-heading">
                    <h6>@lang('Our work in Afghanistan')</h6>
                </div>
                <iframe width="100%"
                        height="315"
                        style="border: none;"
                        src="https://www.youtube-nocookie.com/embed/bF5dpED9W64"
                        allow="clipboard-write; encrypted-media; picture-in-picture"
                        allowfullscreen
                ></iframe>
            </div>

            <div class="col-md-6">
                <div class="sub-heading">
                    <h6>@lang('How to use our library')</h6>
                </div>
                <iframe width="100%"
                        height="315"
                        style="border: none;"
                        src="https://www.youtube-nocookie.com/embed/{{ (Lang::locale() == 'en') ? '-PgQmUX2vbs' : ( (Lang::locale() == 'ps') ? 'EhoGbreiCjo' : '-JM5lzeDWrE') }}"
                        allow="clipboard-write; encrypted-media; picture-in-picture"
                        allowfullscreen
                ></iframe>
            </div>
        </div>
    </div>
    <div class="container pt-2 mt-3" id="homepage-sub-container">
        <div class="row">
            <div class="col-md-4">
                <h4>@lang('Latest news')</h4>
                <hr>
                @foreach($latestNews AS $news)
                    <a href="{{ URL::to('news/'.$news->id) }}" title="{{ $news->title }}">
                        <p>{{ $news->title }}<br><span class="badge text-bg-secondary">{{ __($news->created_at->diffForHumans()) }}</span></p>
                    </a>
                @endforeach
            </div>
            <div class="col-md-4">
                <h4>@lang('Latest resources')</h4>
                <hr>
                @foreach($latestResources AS $resource)
                    <a href="{{ URL::to('resource/'.$resource->id) }}" title="{{ $resource->title }}">
                        <p>{{ $resource->title }}<br><span class="badge text-bg-secondary">{{ __($resource->updated_at->diffForHumans()) }}</span></p>
                    </a>
                @endforeach
            </div>
            <div class="col-md-4">
                <h4>@lang('Useful links')</h4>
                <hr>
                @foreach ($menu->where('location', 'bottom-menu')->where('language', app()->getLocale()) as $bmenu)
                    <a href="{{ URL::to($bmenu->path) }}" title="{{ $bmenu->title }}">{{ $bmenu->title }}</a><br>
                @endforeach
            </div>
        </div>
    </div>

@endsection
