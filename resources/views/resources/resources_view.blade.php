@extends('layouts.main')
@section('search')
    @include('layouts.search')
@endsection
@section('content')
<section class="resourceView">
    <aside>
        <img class="resourceViewImg" src="{{ getImagefromResource($resource->abstract, '282x254') }}">
        <div class="downloadBox">Download</div>
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
                    <img class="relatedItemsImg" src="{{ getImagefromResource($resource->abstract,'55x50') }}">
                    <span><a href="{{ URL::to('resources/view/'.$item->resourceid) }}">{{ $item->title }}</a></span><br>
                    {!! str_limit(strip_tags($item->abstract), 25) !!}
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
            <p>{{ $author->author_name }}</p>
            @endforeach
        </article>
        <article class="resourceViewDetails">
            <h2>Resource Level</h2>
            @foreach ($resourceLevels AS $level)
            <p>{{ $level->resource_level }}</p>
            @endforeach
        </article>
        <article class="resourceViewDetails">
            <h2>Subject Area</h2>
            @foreach ($resourceSubjectAreas AS $subject)
            <p>{{ $subject->subject_area }}</p>
            @endforeach
        </article>
        <article class="resourceViewDetails">
            <h2>Learning Resource Type</h2>
            @foreach($resourceLearningResourceTypes AS $ltype)
            <p>{{ $ltype->learning_resource_type }}</p>
            @endforeach
        </article>
        <article class="resourceViewDetails">
            <h2>Publisher</h2>
            @foreach($resourcePublishers AS $publisher)
            <p>{{ $publisher->publisher_name }}</p>
            @endforeach
        </article>
        <article class="resourceViewDetails">
            <h2>Languages Available</h2>
            <p>English</p>
            <p>Farsi</p>
            <p>Pashto</p>
        </article>
        <article class="resourceViewDetails">
            <h2>License By</h2>
            <p>CC BY-NC / CC BY-NC-SA</p>
        </article>
    </section>
    <section class="ResourceViewComment">
        <header>
            Comments
        </header>
        <div>Add Comment</div>
        <article>
            <p>Jamshid Hashimi</p>
            <i>Feb 15, 2018</i>
            <p>Brilliant resource, I really like it. It was really useful for me in my assignments.</p>
        </article>
    </section>
</section>
@endsection 