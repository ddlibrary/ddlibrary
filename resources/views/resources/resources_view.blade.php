@extends('layouts.main')
@section('search')
    @include('layouts.search')
@endsection
@section('content')
<section class="resource-view">
    @if($resource)
    <aside>
        <img class="resource-view-img" src="{{ getImagefromResource($resource->abstract, '282x254') }}">
        <div class="resource-social-media">
            <h2>Share</h2>
            <i class="fab fa-facebook fa-2x"></i>
            <i class="fab fa-twitter fa-2x"></i>
            <i class="fas fa-print fa-2x"></i>
            <i class="fas fa-at fa-2x"></i>
        </div>

        <div class="resource-view-related-items">
            <header>
                <h2>Related Items</h2>
            </header>
            <div class="resource-related-items-box">
                @foreach ($relatedItems AS $item)
                <div class="related-item">
                    <img class="related-items-img" src="{{ getImagefromResource($item->abstract,'55x50') }}">
                    <span><a href="{{ URL::to('resources/view/'.$item->resourceid) }}">{{ $item->title }}</a><br/>
                    {!! str_limit(strip_tags($item->abstract), 25) !!}</span>
                </div>
                @endforeach
            </div>
        </div>
    </aside>
    <section class="resource-view-information-section">
        <article class="resource-view-title-box">
            <div class="resource-view-title">
                <header>
                    <h1>{{ $resource->title }}</h1>
                </header>
                <div class="resource-icons">
                    <i class="fas fa-lg fa-star {{ $resource->favorite?"active":"" }}" id="resourceFavorite" onclick="favorite('resourceFavorite','{{ URL::to("resources/favorite/") }}','{{ $resource->resourceid }}','{{ Auth::id() }}')"></i>
                    <i class="fas fa-lg fa-share-square"></i>
                    <i class="fas fa-lg fa-flag"></i>
                </div>
            </div>
            <hr>
            {!! fixImage($resource->abstract) !!}
        </article>
        <article class="resource-view-details">
            <h3>Authors</h3>
            @foreach ($resourceAuthors AS $author)
            <p>{{ $author->name }}</p>
            @endforeach
        </article>
        <article class="resource-view-details">
            <h3>Resource Level</h3>
            @foreach ($resourceLevels AS $level)
            <p><a href="{{ URL::to('resources/list?=&level[]='.$level->tid) }}">{{ $level->name }}</a></p>
            @endforeach
        </article>
        <article class="resource-view-details">
            <h3>Subject Area</h3>
            @foreach ($resourceSubjectAreas AS $subject)
            <p><a href="{{ URL::to('resources/list?=&subject_area[]='.$subject->tid) }}">{{ $subject->name }}</a></p>
            @endforeach
        </article>
        <article class="resource-view-details">
            <h3>Learning Resource Type</h3>
            @foreach($resourceLearningResourceTypes AS $ltype)
            <p><a href="{{ URL::to('resources/list?=&type[]='.$ltype->tid) }}">{{ $ltype->name }}</a></p>
            @endforeach
        </article>
        <article class="resource-view-details">
            <h3>Publisher</h3>
            @foreach($resourcePublishers AS $publisher)
            <p>{{ $publisher->name }}</p>
            @endforeach
        </article>
        <article class="resource-view-details">
            <h3>Languages Available</h3>
        
            <?php
            $supportedLocals = array();
            $newId = array();
                foreach($app['config']->get('laravellocalization.localesOrder') as $localeCode)
                {
                    $supportedLocals[] = $localeCode;
                }
                
                if(isset($translations)){
                    foreach($translations AS $tr){
                        if(in_array($tr->language, $supportedLocals)){
                            $newId[$tr->language] = $tr->id;
                        }
                    }
                }
            ?>

            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            @if(isset($newId[$localeCode]) && count($newId) > 0)
                <?php 
                    $currentUrl = explode('/',url()->current());
                    $index = count($currentUrl) - 1;
                    $value = $currentUrl[$index];
                    $currentUrl[$index] = $newId[$localeCode];
                    $newUrl = implode($currentUrl, '/');
                ?>
                <p>
                    <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, $newUrl, [], true) }}">
                    {{ $properties['native'] }}
                    </a>
                </p>
            @else
                <p>
                    <a rel="alternate" style="text-decoration: line-through;" hreflang="{{ $localeCode }}">
                    {{ $properties['native'] }}
                    </a>
                </p>
            @endif
            @endforeach
        </article>
        <article class="resource-view-details">
            <h3>License By</h3>
            <p>CC BY-NC / CC BY-NC-SA</p>
        </article>
        <article class="resource-view-details">
            <h3>Download</h3>
            <div class="download-box">
            @if($resourceAttachments)
            <span class="download-item">File Name</strong></span>
            <span class="download-item"><strong>File Size</strong></span>
            @foreach($resourceAttachments as $file)
            <span class="download-item"><a href="{{ Storage::disk('private')->url($file->file_name) }}">{{ $file->file_name }}</a></span>
            <span class="download-item">{{ formatBytes($file->file_size) }}</span>
            @endforeach
            @endif
            </div>
        </article>
        @else
        <h1>Resource not found or is not yet translated!</h1>
        @endif
    </section>
</section>
@endsection 