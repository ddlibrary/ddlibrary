@extends('layouts.main')
@section('title')
{{ trim(strip_tags($resource->title)) }}
@endsection
@section('description')
{{ html_entity_decode(trim(strip_tags(fixImage($resource->abstract, $resource->id)))) }}
@endsection
@section('page_image')
{{ getImagefromResource($resource->abstract, '282x254') }}
@endsection
@section('search')
    @include('layouts.search')
@endsection
@section('content')
<section class="resource-view">
    @if($resource)
    <section class="resource-view-information-section">
        <article class="resource-view-title-box">
            @include('layouts.messages')
            <div class="resource-view-title">
                <header>
                    <h2>{{ $resource->title }}</h2>
                </header>
                <div class="resource-icons">
                    <div class="resource-icons-group">
                        @if (isLibraryManager() or isAdmin())
                        <a href="{{ URL::to($resource->language.'/resources/edit/step1/'.$resource->id) }}">@lang('Edit')</a>
                        @endif
                        <i class="fas fa-lg fa-star {{ count($resource->favorites)?"active":"" }}" title="@lang('Favorite this resource')" id="resourceFavorite" onclick="favorite('resourceFavorite','{{ URL::to("resources/favorite/") }}','{{ $resource->id }}','{{ Auth::id() }}')"></i>
                        <i class="fas fa-lg fa-share-square"  title="@lang('Share this resource')"></i>
                        <i class="fas fa-lg fa-flag" title="@lang('Flag this resource')"></i>
                    </div>
                </div>

                <!-- The Share Modal -->
                <div id="shareModal" class="modal">
                    <!-- Modal content -->
                    <div class="modal-content">
                        <div class="modal-header">
                            <span class="close" id="share-close">&times;</span>
                            <h2>@lang('Share this item')</h2>
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
                            <h2>@lang('Favorite this item')</h2>
                        </div>
                        <div class="modal-body">
                            <div class="modal-body">
                                <h2>@lang('In order to favorite a resource, you are required to') <a href="{{ URL::to('login') }}" title="@lang('login')">@lang('login')</a>.</h2>
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
                            <h2>@lang('Flag this item')</h2>
                        </div>
                        <div class="modal-body">
                            <div class="modal-body">
                                <section class="ddl-forms">
                                    <div class="content-body">
                                        <form method="POST" action="{{ route('flag') }}">
                                        @csrf
                                            <div class="form-item">
                                                <label for="type"> 
                                                    <strong>@lang('Type')</strong>
                                                    <span class="form-required" title="This field is required.">*</span>
                                                </label>
                                                <select name="type" class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" required>
                                                    <option value="">- @lang('None') -</option>
                                                    <option value="1">@lang('Graphic Violence')</option>
                                                    <option value="2">@lang('Graphic Sexual Content')</option>
                                                    <option value="3">@lang('Spam, Scam or Fraud')</option>
                                                    <option value="4">@lang('Broken or Empty Data')</option>
                                                </select>
                                            </div>
                                            <div class="form-item">
                                                <label for="details"> 
                                                    <strong>@lang('Details')</strong>
                                                    <span class="form-required" title="This field is required.">*</span>
                                                </label>
                                                <textarea name="details" class="form-control" cols="40" rows="5" required></textarea>
                                            </div>
                                            <input type="hidden" value="{{ $resource->id }}" name="resource_id">
                                            <input type="hidden" value="{{ Auth::id() }}" name="userid">
                                            <div class="left-side">
                                                <input class="form-control normalButton" type="submit" value="@lang('Submit')">
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
            {!! fixImage($resource->abstract, $resource->id) !!}
        </article>
        <article class="resource-view-details">
            <div class="resource-view-download">
            <h3 style="display: inline;">@lang('Download')</h3>
            <a href="/glossary" class="glossary-icon"><i class="fas fa-globe" title="@lang('DDL Glossary')" ><span class="glossary-text">&nbsp;@lang('Glossary')</span> </i></a>
            </div>
            <div class="download-box">
                @if (Auth::check())
                    @if($resource->attachments)
                        @foreach($resource->attachments as $file)
                            <h4>@lang('File :id', ['id' => $loop->iteration])</h4>
                            <h4>
                            <span class="badge badge-secondary">
                                @php
                                    /* @var $file */
                                    echo(pathinfo($file->file_name, PATHINFO_EXTENSION));
                                @endphp
                            </span>
                            </h4>
                            @if($file->file_mime=="application/pdf")
                                <iframe src="{{ URL::to(config('constants.ddlmain_s3_file_storage_url').'/resources/'.$file->file_name) }}#toolbar=0" height="500" width="100%"></iframe>
                            @elseif($file->file_mime == "application/msword" || $file->file_mime == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" )
                                <iframe src="{{ URL::to(config('constants.google_doc_viewer_url').config('constants.ddlmain_s3_file_storage_url').'/resources/'.$file->file_name.'&embedded=true') }}" height="500" width="100%"></iframe>
                            @elseif($file->file_mime == "audio/mpeg")
                                <span class="download-item">
                                <audio controls>
                                    <source src="{{ URL::to(config('constants.ddlmain_s3_file_storage_url').'/resources/'.$file->file_name) }}" type="audio/mpeg">
                                </audio>
                            </span>
                            @else
                                <span class="download-item no-preview">@lang('No preview available.')</span>
                            @endif

                            {{-- revert to older direct download format until we have the correct packages installed for PDF watermarking <span class="download-item"><a class="btn btn-primary"
                                                            href="{{ URL::to('resource/'.$resource->id.'/download/'.$file->id) }}"><i
                                class="fa fa-download" aria-hidden="true"></i> @lang('Download') ({{ formatBytes($file->file_size) }})</a>
                            <br>
                            <hr>
                            </span>--}}
                            <span class="download-item"><a class="btn btn-primary" href="{{ URL::to(config('constants.ddlmain_s3_file_storage_url').'/resources/'.$file->file_name) }}"><i class="fa fa-download" aria-hidden="true"></i> @lang('Download') ({{ formatBytes($file->file_size) }})</a><br></span>
                        @endforeach
                    @endif
                @else
                    <h4 class="download-resource">@lang('Please login to download this resource.')</h4>
                @endif
            </div>
        </article><br>
        <h3 style="background-color: #77777742; padding: 0 7px 0 7px;">@lang('About this resource')</h3>
        @if($resource->authors->count())
        <article class="resource-view-details">
            <h3>@lang('Author')</h3>
            @foreach ($resource->authors AS $author)
            <p>{{ $author->name }}</p>
            @endforeach
        </article>
        @endif

        @if($resource->translators->count())
        <article class="resource-view-details">
            <h3>@lang('Translator')</h3>
            @foreach ($resource->translators AS $translator)
            <p>{{ $translator->name }}</p>
            @endforeach
        </article>
        @endif

        <article class="resource-view-details">
            <h3>@lang('Resource Level')</h3>
            @foreach ($resource->levels AS $level)
            <p><a href="{{ URL::to('resources/list?level='.$level->id) }}" title="{{ $level->name }}">{{ $level->name }}</a></p>
            @endforeach
        </article>
        <article class="resource-view-details">
            <h3>@lang('Subject Area')</h3>
            @foreach ($resource->subjects AS $subject)
            <p><a href="{{ URL::to('resources/list?subject_area='.$subject->id) }}" title="{{ $subject->name }}">{{ $subject->name }}</a></p>
            @endforeach
        </article>
        <article class="resource-view-details">
            <h3>@lang('Learning Resource Type')</h3>
            @foreach($resource->LearningResourceTypes AS $ltype)
            <p><a href="{{ URL::to('resources/list?type='.$ltype->id) }}" title="{{ $ltype->name }}">{{ $ltype->name }}</a></p>
            @endforeach
        </article>
        <article class="resource-view-details">
            <h3>@lang('Publisher')</h3>
            @foreach($resource->publishers AS $publisher)
            <p><a href="{{ URL::to('resources/list?publisher='.$publisher->id) }}" title="{{ $publisher->name }}">{{ $publisher->name }}</a></p>
            @endforeach
        </article>
        <article class="resource-view-details">
            <h3>@lang('Languages Available')</h3>
        
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
                    <a rel="alternate" title="language" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, $newUrl, [], true) }}">
                    {{ $properties['native'] }}
                    </a>
                </p>
            @else
                <p>
                    <a rel="alternate" title="language" style="text-decoration: line-through;" hreflang="{{ $localeCode }}">
                    {{ $properties['native'] }}
                    </a>
                </p>
            @endif
            @endforeach
        </article>
        <article class="resource-view-details">
            <h3>@lang('License')</h3>
            <p>{{ count($resource->creativeCommons)?$resource->creativeCommons[0]->name:"" }}</p>
        </article>
    </section>
    <aside>
        <img class="resource-view-img" src="{{ getImagefromResource($resource->abstract, '282x254') }}" alt="Resource Main Image">

        <div class="resource-view-related-items">
            <header>
                <h2>@lang('Related Items')</h2>
            </header>
            <div class="resource-related-items-box">
                @foreach ($relatedItems AS $item)
                <div class="related-item">
                    <img class="related-items-img" src="{{ getImagefromResource($item->abstract,'55x50') }}" alt="Resource Image">
                    <span><a title="Resource Title" href="{{ URL::to('resource/'.$item->id) }}">{{ $item->title }}</a><br/>
                    {!! str_limit(strip_tags($item->abstract), 25) !!}</span>
                </div>
                @endforeach
            </div>
        </div>
        @if (isAdmin())
        <p>@lang('Added by'): <a href="{{ route('user-view',isset($resource->user)?$resource->user->id:"") }}">{{ isset($resource->user)?$resource->user->username:"" }}</a>
        @endif
        @if (isAdmin())
        <div>
            <br>
            <form method="post" action="{{ route('updatetid', $resource->id) }}">
            @csrf
            If this resource is translated, write down the translated resource id and click submit:
            <input type="number" name="link" class="form-control tnid-input">
            <input type="submit" class="form-control normalButton" value="Submit">
            </form>
        </div>
        @endif
    </aside>
    <section class="resource-view-comment">
        <header>
            <h2>@lang('Comments')</h2>
            <h2>{{ count($comments) }} @lang('comment(s) so far')</h2>
        </header>
        @foreach($comments AS $cm)
        <article style="border:1px solid #f1f1f1; border-radius: 5px;">
            <div>
                <strong>{{ $cm->user->username }}</strong>
            </div>
            <span style="font-size: 10px; color: #8795a1;">Posted {{ $cm->created_at->diffForHumans() }}</span>
            <div style="padding-top: 10px;">
                {{ $cm->comment }}
            </div>
        </article>
        <br>
        @endforeach
        @if (Auth::check())
        <form method="POST" action="{{ route('comment') }}">
        @csrf
            <article>
                <textarea name="comment" cols="40" rows="10" required></textarea>
            </article>
            <input type="hidden" value="{{ $resource->id }}" name="resource_id">
            <input type="hidden" value="{{ Auth::id() }}" name="userid">
            <div class="left-side">
                <input class="form-control normalButton" type="submit" value="Submit">
            </div>
        </form>
        @else
        <h4 class="download-resource">@lang('Please login to add comments.')</h4>
        @endif
    </section>
    @else
        <h1>@lang('Resource not found or is not yet translated!')</h1>
        @endif
</section>
@endsection




