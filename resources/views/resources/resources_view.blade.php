@extends('layouts.main')
@section('search')
    @include('layouts.search')
@endsection
@section('content')
<section class="resource-view">
    @if($resource)
    <aside>
        <img class="resource-view-img" src="{{ getImagefromResource($resource->abstract, '282x254') }}">

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
        <p>Added by: <a href="{{ URL::to('users/view/'.$resource->userid) }}">{{ $resource->addedby }}</a>
    </aside>
    <section class="resource-view-information-section">
        <article class="resource-view-title-box">
            <div class="form-required">
                {!! Session::get("msg") !!}
            </div>
            <div class="resource-view-title">
                <header>
                    <h1>{{ $resource->title }}</h1>
                </header>
                <div class="resource-icons">
                    <i class="fas fa-lg fa-star {{ $favorite?"active":"" }}" id="resourceFavorite" onclick="favorite('resourceFavorite','{{ URL::to("resources/favorite/") }}','{{ $resource->resourceid }}','{{ Auth::id() }}')"></i>
                    <i class="fas fa-lg fa-share-square"></i>
                    <i class="fas fa-lg fa-flag"></i>
                </div>

                <!-- The Share Modal -->
                <div id="shareModal" class="modal">
                    <!-- Modal content -->
                    <div class="modal-content">
                        <div class="modal-header">
                            <span class="close" id="share-close">&times;</span>
                            <h2>Share this item</h2>
                        </div>
                        <div class="modal-body">
                            <div class="modal-body">
                                <div class="social-share">
                                    <i class="fab fa-twitter fa-4x" title="Share to Twitter" onclick="window.location.href='https://twitter.com/intent/tweet?url={{ Request::url() }}'"></i>
                                    <i class="fab fa-facebook fa-4x" title="Share to Facebook" onclick="window.location.href='https://www.facebook.com/sharer/sharer.php?u={{ Request::url() }}'"></i>
                                    <i class="fab fa-google-plus-g fa-4x" title="Share to Google+" onclick="window.location.href='https://plus.google.com/share?url={{ Request::url() }}'"></i>
                                    <i class="fab fa-reddit fa-4x" title="Share to Reddit" onclick="window.location.href='https://reddit.com/submit?url={{ Request::url() }}'"></i>
                                    <i class="fab fa-tumblr fa-4x" title="Share to Tumblr" onclick="window.location.href='https://www.tumblr.com/widgets/share/tool?canonicalUrl={{ Request::url() }}'"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- The favorite Modal -->
                <div id="favoriteModal" class="modal">
                    <!-- Modal content -->
                    <div class="modal-content">
                        <div class="modal-header">
                            <span class="close" id="favorite-close">&times;</span>
                            <h2>Favorite this item</h2>
                        </div>
                        <div class="modal-body">
                            <div class="modal-body">
                                <h2>In order to favorite a resource, you are required to <a href="{{ URL::to('login') }}">login</a>.</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- The Flag Modal -->
                <div id="flagModal" class="modal">
                    <!-- Modal content -->
                    <div class="modal-content">
                        <div class="modal-header">
                            <span class="close" id="flag-close">&times;</span>
                            <h2>Flag this item</h2>
                        </div>
                        <div class="modal-body">
                            <div class="modal-body">
                                <section class="ddl-forms">
                                    <div class="content-body">
                                        <form method="POST" action="{{ route('flag') }}">
                                        @csrf
                                            <div class="form-item">
                                                <label for="type"> 
                                                    <strong>Type</strong>
                                                    <span class="form-required" title="This field is required.">*</span>
                                                </label>
                                                <select name="type" class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" required>
                                                    <option value="">- None -</option>
                                                    <option value="1">Graphic Violence</option>
                                                    <option value="2">Graphic Sexual Content</option>
                                                    <option value="3">Spam, Scam or Fraud</option>
                                                    <option value="4">Broken or Empty Data</option>
                                                </select>
                                            </div>
                                            <div class="form-item">
                                                <label for="details"> 
                                                    <strong>Details</strong>
                                                    <span class="form-required" title="This field is required.">*</span>
                                                </label>
                                                <textarea name="details" class="form-control" cols="40" rows="5" required></textarea>
                                            </div>
                                            <input type="hidden" value="{{ $resource->resourceid }}" name="resourceid">
                                            <input type="hidden" value="{{ Auth::id() }}" name="userid">
                                            <div class="left-side">
                                                <input class="form-control normalButton" type="submit" value="Submit">
                                            </div>
                                        </form>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
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
            <h3>License</h3>
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

    <section class="resource-view-comment">
        <header>
            <h2>Comments</h2>
            <h2>{{ count($comments) }} comment(s) so far</h2>
        </header>
        @foreach($comments AS $cm)
        <article>
            <div>
                <strong>{{ $cm->username }}</strong>
            </div>
            <div>
                {{ $cm->comment }}
            </div>
            <div>
                {{ Carbon\Carbon::createFromTimestamp($cm->created)->diffForHumans() }}
            </div>
        </article>
        <hr>
        @endforeach
        @if (Auth::check())
        <form method="POST" action="{{ route('comment') }}">
        @csrf
            <article>
                <textarea name="comment" cols="40" rows="10"></textarea>
            </article>
            <input type="hidden" value="{{ $resource->resourceid }}" name="resourceid">
            <input type="hidden" value="{{ Auth::id() }}" name="userid">
            <div class="left-side">
                <input class="form-control normalButton" type="submit" value="Submit">
            </div>
        </form>
        @else
        <h2>Please <a href="{{ URL::to('login') }}">login</a> to add comment.</h2>
        @endif
    </section>
</section>
@endsection