@extends('layouts.main')
@section('search')
    @include('layouts.search')
@endsection
@section('content')
<section class="resourceView">
    @if($resource)
    <aside>
        <img class="resourceViewImg" src="{{ getImagefromResource($resource->abstract, '282x254') }}">
        <div class="ResourceSocialMedia">
            <h3>Share</h3>
            <i class="fab fa-facebook fa-2x"></i>
            <i class="fab fa-twitter fa-2x"></i>
            <i class="fas fa-print fa-2x"></i>
            <i class="fas fa-at fa-2x"></i>
        </div>

        <div class="ResourceViewRelatedItems">
            <header>
                <h3>Related Items</h3>
            </header>
            <div class="ResourceRelatedItemsBox">
                @foreach ($relatedItems AS $item)
                <div class="relatedItem">
                    <img class="relatedItemsImg" src="{{ getImagefromResource($item->abstract,'55x50') }}">
                    <span><a href="{{ URL::to('resources/view/'.$item->resourceid) }}">{{ $item->title }}</a><br/>
                    {!! str_limit(strip_tags($item->abstract), 25) !!}</span>
                </div>
                @endforeach
            </div>
        </div>
    </aside>
    <section class="resourceViewInformationSection">
        <article class="resourceViewTitleBox">
            <header>
                <h1>{{ $resource->title }}</h1>
            </header>
            <hr>
            {!! fixImage($resource->abstract) !!}
        </article>
        <article class="resourceViewDetails">
            <h2>Authors</h2>
            @foreach ($resourceAuthors AS $author)
            <p>{{ $author->name }}</p>
            @endforeach
        </article>
        <article class="resourceViewDetails">
            <h2>Resource Level</h2>
            @foreach ($resourceLevels AS $level)
            <p><a href="{{ URL::to('resources/list?=&level[]='.$level->tid) }}">{{ $level->name }}</a></p>
            @endforeach
        </article>
        <article class="resourceViewDetails">
            <h2>Subject Area</h2>
            @foreach ($resourceSubjectAreas AS $subject)
            <p><a href="{{ URL::to('resources/list?=&subject_area[]='.$subject->tid) }}">{{ $subject->name }}</a></p>
            @endforeach
        </article>
        <article class="resourceViewDetails">
            <h2>Learning Resource Type</h2>
            @foreach($resourceLearningResourceTypes AS $ltype)
            <p><a href="{{ URL::to('resources/list?=&type[]='.$ltype->tid) }}">{{ $ltype->name }}</a></p>
            @endforeach
        </article>
        <article class="resourceViewDetails">
            <h2>Publisher</h2>
            @foreach($resourcePublishers AS $publisher)
            <p>{{ $publisher->name }}</p>
            @endforeach
        </article>
        <article class="resourceViewDetails">
            <h2>Languages Available</h2>
        
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
        <article class="resourceViewDetails">
            <h2>License By</h2>
            <p>CC BY-NC / CC BY-NC-SA</p>
        </article>
        <article class="resourceViewDetails">
            <h2>Download</h2>
            <div class="downloadBox">
            @if($resourceAttachments)
            <span class="downloadItem">File Name</strong></span>
            <span class="downloadItem"><strong>File Size</strong></span>
            @foreach($resourceAttachments as $file)
            <span class="downloadItem"><a href="{{ Storage::disk('private')->url($file->file_name) }}">{{ $file->file_name }}</a></span>
            <span class="downloadItem">{{ formatBytes($file->file_size) }}</span>
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